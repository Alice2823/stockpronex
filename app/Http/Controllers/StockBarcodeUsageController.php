<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockBarcode;
use App\Models\StockUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockBarcodeUsageController extends Controller
{
    /**
     * Show barcode usage page
     */
    public function index()
    {
        return view('usage.barcode');
    }

    /**
     * Unified method to handle barcode usage (Scan and Manual)
     */
    public function useBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'customer_name' => 'required|string',
            'company_name' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'nullable|string|in:cash,online',
        ]);

        return $this->processBarcode(trim($request->barcode), $request->all());
    }

    /**
     * Common function to process single barcode consumption
     */
    private function processBarcode($barcodeValue, $customerData)
    {
        try {
            DB::beginTransaction();

            $data = $this->getProductData($barcodeValue);
            $stock = $data['stock'];
            $unit = $data['unit'] ?? null;
            $barcodeModel = $data['barcode'] ?? null;

            $usage = $this->recordUsage($stock, $unit, $barcodeModel, $barcodeValue, $customerData);
            
            $taxPercentage = Auth::user()->getTaxPercentage();
            $subtotal = (float)$customerData['amount'];
            $discountPercentage = (float)($customerData['discount'] ?? 0);
            $discountAmount = ($subtotal * $discountPercentage) / 100;
            $discountedSubtotal = $subtotal - $discountAmount;
            
            $taxAmount = ($discountedSubtotal * $taxPercentage) / 100;
            $totalAmount = $discountedSubtotal + $taxAmount;

            $mrp = $stock->mrp ?? 0;
            $processedItems = [[
                'barcode' => $unit->barcode ?? $barcodeModel->barcode ?? $barcodeValue,
                'name' => $stock->name,
                'unit_price' => $subtotal,
                'mrp' => $mrp,
                'discount_amount' => $discountAmount,
                'usage_id' => $usage->id,
                'imei' => $unit->imei_number ?? null,
                'serial' => $unit->serial_number ?? null,
                'brand' => $stock->business_attributes['brand'] ?? null,
                'model' => $stock->business_attributes['model_number'] ?? null,
            ]];

            $invoiceNumber = $this->generateInvoiceNumber();
            $invoice = \App\Models\Invoice::create([
                'user_id' => Auth::id(),
                'stock_id' => $stock->id,
                'usage_id' => $usage->id,
                'barcode' => $processedItems[0]['barcode'],
                'customer_name' => $customerData['customer_name'],
                'company_name' => $customerData['company_name'] ?? null,
                'phone' => $customerData['phone'],
                'address' => $customerData['address'],
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'tax_percentage' => $taxPercentage,
                'total_amount' => $totalAmount,
                'amount' => $totalAmount,
                'payment_method' => $customerData['payment_method'] ?? 'cash',
                'status' => 'paid',
                'items' => $processedItems,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Product used successfully. Invoice generated: " . $invoiceNumber,
                'data' => [
                    'stock_name' => $stock->name,
                    'remaining_quantity' => $stock->quantity,
                    'invoice_number' => $invoiceNumber,
                    'invoice_id' => $invoice->id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Process multiple items for a single invoice
     */
    public function useBarcodeMulti(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barcode' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'customer_name' => 'required|string',
            'company_name' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|string|in:cash,online',
        ]);

        try {
            DB::beginTransaction();

            $processedItems = [];
            $totalSubtotal = 0;
            $firstStockId = null;
            $firstUsageId = null;

            foreach ($request->items as $item) {
                $data = $this->getProductData($item['barcode']);
                $stock = $data['stock'];
                $unit = $data['unit'] ?? null;
                $barcodeModel = $data['barcode'] ?? null;

                $usage = $this->recordUsage($stock, $unit, $barcodeModel, $item['barcode'], $request->all());
                
                if (!$firstStockId) {
                    $firstStockId = $stock->id;
                    $firstUsageId = $usage->id;
                }

                $processedItems[] = [
                    'stock_id' => $stock->id,
                    'barcode' => $item['barcode'],
                    'name' => $stock->name,
                    'unit_price' => (float)$item['unit_price'],
                    'mrp' => (float)($stock->mrp ?? 0),
                    'usage_id' => $usage->id,
                    'imei' => $unit->imei_number ?? null,
                    'serial' => $unit->serial_number ?? null,
                    'brand' => $stock->business_attributes['brand'] ?? null,
                    'model' => $stock->business_attributes['model_number'] ?? null,
                ];
                $totalSubtotal += $item['unit_price'];
            }

            $taxPercentage = Auth::user()->getTaxPercentage();
            $discountPercentage = (float)$request->input('discount', 0);
            $totalDiscountAmount = ($totalSubtotal * $discountPercentage) / 100;

            // Enforce proportional discount on each item for accurate profit tracking
            foreach ($processedItems as &$pItem) {
                $pItem['discount_amount'] = ($pItem['unit_price'] * $discountPercentage) / 100;
            }
            
            $discountedSubtotal = $totalSubtotal - $totalDiscountAmount;
            $taxAmount = ($discountedSubtotal * $taxPercentage) / 100;
            $totalAmount = $discountedSubtotal + $taxAmount;

            $invoiceNumber = $this->generateInvoiceNumber();
            $invoice = \App\Models\Invoice::create([
                'user_id' => Auth::id(),
                'stock_id' => $firstStockId, // Reference the first item
                'usage_id' => $firstUsageId,
                'barcode' => $request->items[0]['barcode'],
                'invoice_number' => $invoiceNumber,
                'subtotal' => $totalSubtotal,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $totalDiscountAmount,
                'tax_amount' => $taxAmount,
                'tax_percentage' => $taxPercentage,
                'total_amount' => $totalAmount,
                'amount' => $totalAmount,
                'customer_name' => $request->customer_name,
                'company_name' => $request->company_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'status' => 'paid',
                'items' => $processedItems,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Batch invoice generated successfully: ' . $invoiceNumber,
                'data' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoiceNumber
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    private function getProductData($barcodeValue)
    {
        $barcodeValue = trim($barcodeValue);
        $unit = \App\Models\StockUnit::where('user_id', Auth::id())
            ->where('status', 'available')
            ->where(function ($query) use ($barcodeValue) {
                $query->where('barcode', $barcodeValue)
                    ->orWhere('imei_number', $barcodeValue)
                    ->orWhere('serial_number', $barcodeValue);
            })->with('stock')->first();

        if ($unit) {
            return ['stock' => $unit->stock, 'unit' => $unit];
        }

        $barcode = StockBarcode::where('barcode', $barcodeValue)
            ->where('user_id', Auth::id())
            ->where('status', 'available')
            ->first();

        if ($barcode) {
            return ['stock' => $barcode->stock, 'barcode' => $barcode];
        }

        throw new \Exception("Invalid barcode or already used: $barcodeValue");
    }

    private function recordUsage($stock, $unit, $barcodeModel, $barcodeValue, $customerData)
    {
        if (!$stock || $stock->quantity <= 0) {
            throw new \Exception("Product {$stock->name} is out of stock.");
        }

        if ($unit) {
            $unit->update(['status' => 'sold']);
        } elseif ($barcodeModel) {
            $barcodeModel->update(['status' => 'used']);
        }

        $stock->decrement('quantity', 1);

        $notes = "Barcode: $barcodeValue\nCustomer: {$customerData['customer_name']}\nPhone: {$customerData['phone']}";
        
        // Add Brand and Model from Stock attributes if available
        if (isset($stock->business_attributes['brand'])) $notes .= "\nBrand: " . $stock->business_attributes['brand'];
        if (isset($stock->business_attributes['model_number'])) $notes .= "\nModel: " . $stock->business_attributes['model_number'];

        if ($unit) {
            if ($unit->imei_number) $notes .= "\nIMEI: " . $unit->imei_number;
            if ($unit->serial_number) $notes .= "\nSERIAL: " . $unit->serial_number;
        }

        return \App\Models\StockUsage::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'quantity' => 1,
            'notes' => $notes,
        ]);
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
     * Fetch stock details by barcode for auto-fill
     */
    public function getDetails($barcodeValue)
    {
        $barcodeValue = trim($barcodeValue);
        // Check StockUnit first (IMEI, Serial, or specific unit Barcode)
        $unit = \App\Models\StockUnit::where('user_id', Auth::id())
            ->where('status', 'available')
            ->where(function ($query) use ($barcodeValue) {
                $query->where('barcode', $barcodeValue)
                    ->orWhere('imei_number', $barcodeValue)
                    ->orWhere('serial_number', $barcodeValue);
            })
            ->with('stock')
            ->first();

        $stock = null;
        if ($unit) {
            $stock = $unit->stock;
        } else {
            // Fallback to general StockBarcode
            $barcode = StockBarcode::where('barcode', $barcodeValue)
                ->where('user_id', Auth::id())
                ->where('status', 'available')
                ->first();
            
            if ($barcode) {
                $stock = $barcode->stock;
            }
        }

        if (!$stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid barcode or already used.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'stock_id' => $stock->id,
                'name' => $stock->name,
                'price' => $stock->price,
                'attributes' => $stock->business_attributes ?? []
            ]
        ]);
    }
}
