<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProfitExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitController extends Controller
{
    /**
     * Display the profit analytics for all user's stocks
     */
    public function index()
    {
        if (!Auth::user()->hasFeature('profit_manage')) {
            return redirect()->route('subscription.index');
        }
        $data = $this->getProfitData();
        return view('profit.index', $data);
    }

    /**
     * Export profit breakdown to professional PDF
     */
    public function exportPdf()
    {
        $data = $this->getProfitData();
        $pdf = Pdf::loadView('profit.pdf', $data);
        return $pdf->download('Profit_Report_' . now()->format('d_M_Y') . '.pdf');
    }

    /**
     * Export profit data to Excel spreadsheet
     */
    public function exportExcel()
    {
        $data = $this->getProfitData();
        return Excel::download(new ProfitExport($data), 'Profit_Data_' . now()->format('d_M_Y') . '.xlsx');
    }

    /**
     * Accurate profit calculation based on Invoice records
     */
    private function getProfitData()
    {
        $user = Auth::user();
        
        // 1. Fetch all invoices for the user with their usage records
        $invoices = Invoice::with(['usage', 'stock' => function($q) {
            $q->withTrashed();
        }])->where('user_id', $user->id)->get();

        $totalOverallDiscount = 0;
        $totalOverallProfit = 0;
        
        // Map to store aggregated profit per stock ID
        $stockMetrics = [];

        foreach ($invoices as $invoice) {
            $items = $invoice->items;
            
            // Handle both new multi-item format and old single-item format
            if (!is_array($items)) {
                $stockId = $invoice->stock_id;
                if (!$stockId) continue;
                
                $qty = $invoice->usage ? $invoice->usage->quantity : 1;
                $mrp = $invoice->stock ? $invoice->stock->mrp : 0;
                $price = $invoice->stock ? $invoice->stock->price : 0;
                $discount = (float)($invoice->discount_amount ?? 0);
                
                $gross = ($price - $mrp) * $qty;
                $net = $gross - $discount;
                
                $this->updateStockMetric($stockMetrics, $stockId, $gross, $discount, $net, $qty, $invoice);
                
                $totalOverallDiscount += $discount;
                $totalOverallProfit += $net;
                continue;
            }

            foreach ($items as $item) {
                $stockId = $item['stock_id'] ?? $invoice->stock_id;
                if (!$stockId) continue;

                $mrp = (float)($item['mrp'] ?? 0);
                $price = (float)($item['unit_price'] ?? 0);
                $discount = (float)($item['discount_amount'] ?? 0);
                
                $gross = ($price - $mrp); // unit gross profit
                $net = $gross - $discount; // unit net profit
                
                $this->updateStockMetric($stockMetrics, $stockId, $gross, $discount, $net, 1, $invoice);
                
                $totalOverallDiscount += $discount;
                $totalOverallProfit += $net;
            }
        }
        
        // 2. Fetch all stocks (active + deleted ones that have sales)
        $stockIds = array_keys($stockMetrics);
        $activeStockIds = Stock::where('user_id', $user->id)->pluck('id')->toArray();
        $allRelevantStockIds = array_unique(array_merge($stockIds, $activeStockIds));
        
        $stocks = Stock::withTrashed()
                    ->whereIn('id', $allRelevantStockIds)
                    ->where('user_id', $user->id)
                    ->get();
                    
        foreach ($stocks as $stock) {
            $metrics = $stockMetrics[$stock->id] ?? ['gross' => 0, 'discount' => 0, 'net' => 0, 'units' => 0, 'history' => []];
            
            $stock->gross_profit = $metrics['gross'];
            $stock->total_discount = $metrics['discount'];
            $stock->calculated_profit = $metrics['net'];
            $stock->units_sold = $metrics['units'];
            $stock->sales_history = collect($metrics['history'])->sortByDesc('date')->values();
        }
        
        // Only return stocks that have either sales OR are still active
        $stocks = $stocks->filter(function($s) {
            return $s->units_sold > 0 || !$s->deleted_at;
        })->sortByDesc('calculated_profit');

        return [
            'stocks' => $stocks,
            'totalOverallProfit' => $totalOverallProfit,
            'totalOverallDiscount' => $totalOverallDiscount,
            'generationDate' => now()->format('d-m-Y H:i')
        ];
    }

    /**
     * Helper to update aggregated metrics
     */
    private function updateStockMetric(&$metrics, $stockId, $gross, $discount, $net, $qty, $invoice)
    {
        if (!isset($metrics[$stockId])) {
            $metrics[$stockId] = [
                'gross' => 0, 
                'discount' => 0, 
                'net' => 0, 
                'units' => 0,
                'history' => []
            ];
        }
        
        $metrics[$stockId]['gross'] += $gross;
        $metrics[$stockId]['discount'] += $discount;
        $metrics[$stockId]['net'] += $net;
        $metrics[$stockId]['units'] += $qty;
        
        // Add to history (group by invoice)
        $existingHistoryKey = collect($metrics[$stockId]['history'])->search(fn($h) => $h->invoice_number == $invoice->invoice_number);
        
        if ($existingHistoryKey !== false) {
            $metrics[$stockId]['history'][$existingHistoryKey]->qty += $qty;
            $metrics[$stockId]['history'][$existingHistoryKey]->gross_profit += $gross;
            $metrics[$stockId]['history'][$existingHistoryKey]->discount += $discount;
            $metrics[$stockId]['history'][$existingHistoryKey]->net_profit += $net;
        } else {
            $metrics[$stockId]['history'][] = (object)[
                'date' => $invoice->created_at,
                'invoice_number' => $invoice->invoice_number,
                'qty' => $qty,
                'mrp' => ($qty > 0) ? ($invoice->stock ? $invoice->stock->mrp : 0) : 0, // Fallback
                'price' => ($qty > 0) ? ($invoice->stock ? $invoice->stock->price : 0) : 0, // Fallback
                'discount' => $discount,
                'gross_profit' => $gross,
                'net_profit' => $net
            ];
        }
    }
}
