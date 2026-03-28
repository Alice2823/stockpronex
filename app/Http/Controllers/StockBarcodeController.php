<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\StockBarcode;
use App\Models\StockUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StockBarcodeController extends Controller
{
    public function index($id)
    {
        if (!Auth::user()->hasFeature('barcode')) {
            return redirect()->route('subscription.index')->with('error', 'Barcode generation is a Pro feature. Please upgrade to access.');
        }

        $stock = Stock::where('user_id', Auth::id())->findOrFail($id);

        // Fetch regular barcodes
        $regularBarcodes = StockBarcode::where('stock_id', $id)->get()->toBase()->map(function($b) {
            return (object) [
                'barcode' => $b->barcode,
                'status' => $b->status,
                'created_at' => $b->created_at,
                'type' => 'regular',
                'imei' => null,
                'serial' => null
            ];
        });

        // Fetch unit-specific barcodes
        $unitBarcodes = \App\Models\StockUnit::where('stock_id', $id)->get()->toBase()->map(function($u) {
            return (object) [
                'barcode' => $u->barcode,
                'status' => ($u->status == 'sold' ? 'used' : 'available'),
                'created_at' => $u->created_at,
                'type' => 'unit',
                'imei' => $u->imei_number,
                'serial' => $u->serial_number
            ];
        });

        // Unified barcodes collection
        $barcodes = $regularBarcodes->merge($unitBarcodes)->sortByDesc('created_at');

        return view('barcode.index', compact('stock', 'barcodes'));
    }

    public function generate(Request $request, $id)
    {
        if (!Auth::user()->hasFeature('barcode')) {
            return redirect()->route('subscription.index')->with('error', 'Barcode generation is a Pro feature. Please upgrade to access.');
        }

        $stock = Stock::where('user_id', Auth::id())->findOrFail($id);

        $quantity = $stock->quantity;
        
        // Count existing barcodes in both tables
        $regularCount = StockBarcode::where('stock_id', $id)->count();
        $unitCount = \App\Models\StockUnit::where('stock_id', $id)->whereNotNull('barcode')->count();
        $totalExisting = $regularCount + $unitCount;
        
        if ($totalExisting >= $quantity) {
            return back()->with('success', 'Barcodes already exist for all current units.');
        }

        $toGenerate = $quantity - $totalExisting;
        $generated = 0;

        for ($i = 0; $i < $toGenerate; $i++) {
            $uniqueNumber = str_pad($totalExisting + $i + 1, 4, '0', STR_PAD_LEFT);
            $barcodeValue = "STK-{$stock->id}-{$uniqueNumber}";

            // Ensure uniqueness across both tables
            while (
                StockBarcode::where('barcode', $barcodeValue)->exists() || 
                \App\Models\StockUnit::where('barcode', $barcodeValue)->exists()
            ) {
                $barcodeValue = "STK-{$stock->id}-" . Str::random(5);
            }

            StockBarcode::create([
                'user_id' => Auth::id(),
                'stock_id' => $stock->id,
                'barcode' => $barcodeValue,
                'status' => 'available',
            ]);
            $generated++;
        }

        return back()->with('success', "$generated barcodes generated successfully.");
    }

    public function scan()
    {
        if (!Auth::user()->hasFeature('barcode')) {
            return redirect()->route('subscription.index')->with('error', 'Barcode scanning is a Pro feature. Please upgrade to access.');
        }

        return view('barcode.scan'); // Optional if we put scan in the same page, but the requirement says Option 2.
    }

    public function markUsed(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = StockBarcode::where('barcode', trim($request->barcode))
            ->where('user_id', Auth::id())
            ->where('status', 'available')
            ->first();

        if (!$barcode) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid barcode or already used.'
            ], 404);
        }

        $stock = $barcode->stock;

        if ($stock->quantity <= 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Stock is already empty.'
            ], 400);
        }

        // Reduce stock quantity and trigger notifications via save()
        $stock->quantity = $stock->quantity - 1;
        $stock->save();

        // Record stock usage
        StockUsage::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'quantity' => 1,
            'notes' => 'Used via Barcode Scan: ' . $barcode->barcode,
        ]);

        // Mark barcode as used
        $barcode->status = 'used';
        $barcode->save();

        return response()->json([
            'success' => true, 
            'message' => "Stock unit used successfully. Updated quantity: {$stock->quantity}",
            'stock_name' => $stock->name
        ]);
    }
}
