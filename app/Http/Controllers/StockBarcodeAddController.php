<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockBarcode;
use App\Models\StockUnit;
use App\Models\StockUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockBarcodeAddController extends Controller
{
    /**
     * Show the barcode adding page.
     */
    public function index()
    {
        if (!Auth::user()->hasFeature('barcode')) {
            return redirect()->route('subscription.index')->with('error', 'Barcode management is a Pro feature. Please upgrade to access.');
        }

        return view('stocks.barcode');
    }

    /**
     * Get stock details for a scanned barcode.
     */
    public function getDetails($barcodeValue)
    {
        $barcodeValue = trim($barcodeValue);

        // 1. Search in StockUnits (IMEI/Serial)
        $unit = StockUnit::where('user_id', Auth::id())
            ->where(function ($query) use ($barcodeValue) {
                $query->where('barcode', $barcodeValue)
                    ->orWhere('imei_number', $barcodeValue)
                    ->orWhere('serial_number', $barcodeValue);
            })
            ->with('stock')
            ->first();

        if ($unit) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'stock_id' => $unit->stock->id,
                    'name' => $unit->stock->name,
                    'current_quantity' => $unit->stock->quantity,
                    'price' => $unit->stock->price,
                    'type' => 'unit',
                    'unit_id' => $unit->id,
                    'imei' => $unit->imei_number,
                    'serial' => $unit->serial_number,
                    'unit_status' => $unit->status // e.g. sold or available
                ]
            ]);
        }

        // 2. Search in StockBarcodes
        $barcode = StockBarcode::where('barcode', $barcodeValue)
            ->where('user_id', Auth::id())
            ->with('stock')
            ->first();

        if ($barcode) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'stock_id' => $barcode->stock->id,
                    'name' => $barcode->stock->name,
                    'current_quantity' => $barcode->stock->quantity,
                    'price' => $barcode->stock->price,
                    'type' => 'regular'
                ]
            ]);
        }

        // 3. Fallback: Check if it's a general stock name match (optional but helpful)
        // For now, if not found, we return error so UI can handle "New Item" logic
        return response()->json([
            'status' => 'error',
            'message' => 'Barcode not found. You might need to add this product manually first.'
        ], 404);
    }

    /**
     * Process batch stock addition.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                $stock = Stock::where('user_id', Auth::id())->findOrFail($item['stock_id']);
                
                // Increment quantity
                $stock->increment('quantity', $item['quantity']);

                // If it was an 'available' unit barcode that was rescanned (maybe error?), 
                // we don't necessarily create a new unit, we just incremented the main stock.
                // But typically, "Add via Barcode" for units means adding a NEW unit.
                // For now, we'll just handle basic replenishment.
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }
}
