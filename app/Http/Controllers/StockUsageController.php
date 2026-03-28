<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockBarcode;
use App\Models\StockUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StockUsageController extends Controller
{
    /**
     * Show usage history
     */
    public function index()
    {
        // Get all usages
        $rawUsages = StockUsage::where('user_id', Auth::id())
            ->with(['stock', 'invoice'])
            ->latest()
            ->get();

        // Group by invoice and stock to show consolidated quantity
        // We use a combination of invoice_number (if exists) and created_at/stock_id
        $grouped = $rawUsages->groupBy(function($usage) {
            // Find the invoice this usage belongs to (either directly or via JSON items)
            $invoiceId = $usage->invoice ? $usage->invoice->id : null;
            
            if (!$invoiceId) {
                // Check if it's part of a multi-item invoice
                $multiInvoice = \App\Models\Invoice::where('items', 'LIKE', '%"usage_id":' . $usage->id . '%')->first();
                $invoiceId = $multiInvoice ? $multiInvoice->id : 'none_' . $usage->id;
            }

            return $invoiceId . '_' . $usage->stock_id;
        });

        $usages = $grouped->map(function($group) {
            $first = $group->first();
            $totalQuantity = $group->sum('quantity');
            
            // Extract tracking items from ALL members of this group
            $allTrackedItems = [];
            foreach ($group as $usage) {
                if ($usage->notes && str_contains($usage->notes, 'Tracked Items:')) {
                    $trackedPart = explode('|', explode("\n", $usage->notes)[0])[0];
                    $items = array_map('trim', explode(',', str_replace('Tracked Items:', '', $trackedPart)));
                    $allTrackedItems = array_merge($allTrackedItems, $items);
                } elseif ($usage->notes) {
                    $lines = explode("\n", $usage->notes);
                    foreach($lines as $line) {
                        if (str_starts_with($line, 'Barcode: ') || str_starts_with($line, 'IMEI: ') || str_starts_with($line, 'SERIAL: ') || str_starts_with($line, 'Used via Barcode Scan: ')) {
                            $item = trim(str_replace('Used via Barcode Scan: ', 'Barcode: ', $line));
                            $allTrackedItems[] = $item;
                        }
                    }
                }
            }
            
            // Re-fetch or link invoice for the grouped result
            $invoice = $first->invoice;
            if (!$invoice) {
                $invoice = \App\Models\Invoice::where('items', 'LIKE', '%"usage_id":' . $first->id . '%')->first();
            }
            
            // Set shared properties
            $first->quantity = $totalQuantity;
            $first->consolidated_invoice = $invoice;
            $first->combined_tracked_items = array_values(array_unique(array_filter($allTrackedItems)));
            
            return $first;
        })->values()->sortByDesc('created_at');

        return view('usage.index', compact('usages'));
    }

    /**
     * Show record usage form
     */
    public function create()
    {
        $stocks = Stock::where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->get();

        return view('usage.create', compact('stocks'));
    }

    /**
     * Store stock usage and trigger low stock email
     */
    public function store(Request $request)
    {
        $businessType = Auth::user()->business_type;

        $rules = [
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
            'business_attributes' => 'nullable|array',
            'payment_method' => 'required|in:cash,online',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
        ];

        // Dynamic Validation Rules based on Business Type
        if ($businessType == 'Gold / Jewellery') {
            $rules['business_attributes.*.weight'] = 'required';
            $rules['business_attributes.*.purity'] = 'required';
            $rules['business_attributes.*.making_charges'] = 'required';
        } elseif ($businessType == 'Mobile Shop') {
            $rules['business_attributes.*.imei'] = 'required';
        } elseif ($businessType == 'Electronics') {
            $rules['business_attributes.*.serial_number'] = 'required';
        } elseif ($businessType == 'Medical Store' || $businessType == 'Grocery') {
            $rules['business_attributes.*.expiry_date'] = 'required';
        } elseif ($businessType == 'Clothing') {
            $rules['business_attributes.*.size'] = 'required';
            $rules['business_attributes.*.color'] = 'required';
        }

        $request->validate($rules);

        // Get stock safely using Eloquent
        $stock = Stock::where('id', $request->stock_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if enough stock available
        if ($stock->quantity < $request->quantity) {
            $msg = 'Not enough stock available.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 400);
            }
            return back()->withErrors(['quantity' => $msg]);
        }

        // --- NEW: Unified Barcode/Unit Auto-Assignment Logic ---
        // 1. Try to find units in the new Per-Unit Tracking system first
        $units = \App\Models\StockUnit::where('stock_id', $stock->id)
            ->where('user_id', Auth::id())
            ->where('status', 'available')
            ->limit($request->quantity)
            ->get();

        $consumedCount = $units->count();
        $trackingIdentifiers = [];
        $rawTrackingData = [];

        foreach ($units as $unit) {
            $unit->status = 'sold';
            $unit->save();
            
            $val = $unit->barcode ?: ($unit->imei_number ?: ($unit->serial_number ?: $unit->id));
            $label = $unit->barcode ? 'Barcode: ' : ($unit->imei_number ? 'IMEI: ' : ($unit->serial_number ? 'S/N: ' : 'Unit ID: '));
            
            $trackingIdentifiers[] = $label . $val;
            $rawTrackingData[] = $val;
        }

        // 2. If we still need more items, look for regular barcodes
        if ($consumedCount < $request->quantity) {
            $remainingNeeded = $request->quantity - $consumedCount;
            
            $barcodes = StockBarcode::where('stock_id', $stock->id)
                ->where('user_id', Auth::id())
                ->where('status', 'available')
                ->limit($remainingNeeded)
                ->get();

            if ($barcodes->count() < $remainingNeeded) {
                // Not enough total tracking data available
                $totalAvailable = $consumedCount + $barcodes->count();
                $msg = "Not enough unused tracking data (Barcodes/Units) available (Total Available: $totalAvailable).";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => $msg], 400);
                }
                return back()->withErrors(['quantity' => $msg]);
            }

            foreach ($barcodes as $barcode) {
                $barcode->status = 'used';
                $barcode->save();
                $trackingIdentifiers[] = 'Barcode: ' . $barcode->barcode;
                $rawTrackingData[] = $barcode->barcode;
                $consumedCount++;
            }
        }

        // Format final notes with tracking identifiers and business attributes
        $attrString = "";
        if ($request->has('business_attributes') && is_array($request->business_attributes)) {
            foreach ($request->business_attributes as $index => $attrs) {
                $itemNum = $index + 1;
                $attrString .= "\nItem #{$itemNum}: ";
                if (is_array($attrs)) {
                    foreach ($attrs as $key => $value) {
                        if ($value) {
                            $attrString .= ucwords(str_replace('_', ' ', $key)) . ": " . $value . " ";
                        }
                    }
                }
            }
        }

        $trackingString = "Tracked Items: " . implode(', ', $trackingIdentifiers);
        $finalNotes = $trackingString . " " . $attrString . ($request->notes ? " | Description: " . $request->notes : "");
        // ------------------------------------------

        /**
         * IMPORTANT FIX:
         * Use save() instead of decrement()
         * This triggers Stock model events → LowStockNotification email
         */
        $stock->quantity = $stock->quantity - $request->quantity;

        $stock->save(); // 🔥 This triggers email automatically

        /**
         * Record stock usage history
         */
        $usage = StockUsage::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'quantity' => $request->quantity,
            'notes' => $finalNotes,
        ]);

        // --- NEW: Automatic Invoice Generation for Regular Usage ---
        $taxPercentage = Auth::user()->getTaxPercentage();
        $subtotal = $stock->price * $request->quantity;
        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $totalAmount = $subtotal + $taxAmount;
        $invoiceNumber = $this->generateInvoiceNumber();

        // Construct items array for detailed invoice tracking
        $invoiceItems = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $attrs = $request->business_attributes[$i] ?? [];
            $invoiceItems[] = [
                'name' => $stock->name,
                'brand' => $attrs['brand'] ?? $stock->business_attributes['brand'] ?? null,
                'model' => $attrs['model'] ?? $stock->business_attributes['model'] ?? null,
                'imei' => $attrs['imei'] ?? null,
                'serial' => $attrs['serial_number'] ?? null,
                'barcode' => $rawTrackingData[$i] ?? 'N/A', // Use RAW data here
                'unit_price' => $stock->price,
            ];
        }

        $invoice = \App\Models\Invoice::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'usage_id' => $usage->id,
            'barcode' => $rawTrackingData[0] ?? 'N/A', // Use RAW data here
            'customer_name' => $request->customer_name ?: 'General Customer',
            'company_name' => null,
            'phone' => $request->customer_phone ?: 'N/A',
            'address' => $request->customer_address ?: 'N/A',
            'amount' => $totalAmount,
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'invoice_number' => $invoiceNumber,
            'payment_method' => $request->payment_method ?? 'cash',
            'items' => $invoiceItems, // Store the detailed items list
        ]);
        // ---------------------------------------------------------

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Stock usage recorded successfully.',
                'data' => [
                    'stock_name' => $stock->name,
                    'remaining_quantity' => $stock->quantity,
                    'invoice_id' => $invoice->id
                ]
            ]);
        }

        return redirect()
            ->route('usage.index')
            ->with('success', 'Stock usage recorded successfully.');
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = \App\Models\Invoice::where('invoice_number', 'like', "INV-$year-%")
            ->latest()
            ->first();

        if (!$lastInvoice) {
            $number = 1;
        } else {
            $parts = explode('-', $lastInvoice->invoice_number);
            $number = intval(end($parts)) + 1;
        }

        return "INV-$year-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Export usage data
     */
    public function export($type)
    {
        $date = date('Y-m-d');

        if ($type == 'excel') {

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\StockUsageExport,
                'stock_usage_' . $date . '.xlsx'
            );

        } elseif ($type == 'pdf') {
            $usages = StockUsage::where('user_id', Auth::id())
                ->with('stock', 'invoice')
                ->latest()
                ->get();

            $pdf = Pdf::loadView('pdf.stock-usage-report', compact('usages'))
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'DejaVu Sans'
                ]);

            return $pdf->download('stock_usage_report_' . $date . '.pdf');
        }

        abort(404);
    }
}