<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::where('user_id', Auth::id())->get();
        return view('dashboard', compact('stocks'));
    }

    public function create()
    {
        return view('stocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'business_attributes' => 'nullable|array',
            'units' => 'nullable|array',
            'enable_tracking' => 'nullable|boolean',
        ]);

        if (!Auth::user()->canAddStock()) {
            return redirect()->back()->with('error', 'You have reached the stock limit for your current plan. Please upgrade to add more stocks.');
        }

        $stock = Stock::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'quantity' => $request->quantity,
            'mrp' => $request->mrp,
            'price' => $request->price,
            'description' => $request->description,
            'business_attributes' => $request->business_attributes,
        ]);

        // Handle Per-Unit Tracking
        if ($request->has('enable_tracking') && $request->has('units')) {
            foreach ($request->units as $unitData) {
                // Ensure we don't save completely empty units
                if (!empty(array_filter($unitData))) {
                    \App\Models\StockUnit::create([
                        'stock_id' => $stock->id,
                        'user_id' => Auth::id(),
                        'barcode' => $unitData['barcode'] ?? null,
                        'imei_number' => $unitData['imei_number'] ?? null,
                        'serial_number' => $unitData['serial_number'] ?? null,
                        'batch_number' => $unitData['batch_number'] ?? null,
                        'expiry_date' => $unitData['expiry_date'] ?? null,
                        'status' => 'available',
                        'additional_attributes' => collect($unitData)->except(['barcode', 'imei_number', 'serial_number', 'batch_number', 'expiry_date'])->toArray(),
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Stock added successfully.');
    }

    public function edit(Stock $stock)
    {
        if ($stock->user_id !== Auth::id()) {
            abort(403);
        }
        return view('stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        if ($stock->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'business_attributes' => 'nullable|array',
        ]);

        $stock->update($request->all());
        
        // Ensure business_attributes is handled if not in request
        if (!$request->has('business_attributes')) {
            $stock->business_attributes = null;
            $stock->save();
        }

        return redirect()->route('dashboard')->with('success', 'Stock updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        if ($stock->user_id !== Auth::id()) {
            abort(403);
        }

        $stock->delete();

        return redirect()->route('dashboard')->with('success', 'Stock deleted successfully.');
    }

    public function export($type)
    {
        $date = date('Y-m-d');
        if ($type == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StockExport, 'stocks_' . $date . '.xlsx');
        } elseif ($type == 'pdf') {
            $stocks = Stock::where('user_id', Auth::id())->get();
            $pdf = Pdf::loadView('pdf.stock-entry-report', compact('stocks'))
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'DejaVu Sans'
                ]);

            return $pdf->download('inventory_report_' . $date . '.pdf');
        }
        abort(404);
    }

    public function show(Stock $stock)
    {
        if ($stock->user_id !== Auth::id()) {
            abort(403);
        }

        // Include available tracking units
        $stock->load(['units' => function($query) {
            $query->where('status', 'available')->orderBy('id', 'asc');
        }]);

        return response()->json($stock);
    }
}
