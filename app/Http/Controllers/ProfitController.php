<?php

namespace App\Http\Controllers;

use App\Models\Stock;
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

    public function index()
    {
        $data = $this->getProfitData();
        return view('profit.index', $data);
    }

    /**
     * Common logic to calculate profit across all stocks
     */
    private function getProfitData()
    {
        $user = Auth::user();
        $stocks = Stock::where('user_id', $user->id)
                    ->withSum('usages', 'quantity')
                    ->get();
                    
        $invoices = \App\Models\Invoice::where('user_id', $user->id)->get();

        $stockDiscounts = [];
        $totalOverallDiscount = 0;

        foreach ($invoices as $invoice) {
            $items = $invoice->items;
            if (!is_array($items)) {
                if ($invoice->stock_id && $invoice->discount_amount > 0) {
                    $stockDiscounts[$invoice->stock_id] = ($stockDiscounts[$invoice->stock_id] ?? 0) + $invoice->discount_amount;
                    $totalOverallDiscount += $invoice->discount_amount;
                }
                continue;
            }

            foreach ($items as $item) {
                $stockId = $item['stock_id'] ?? $invoice->stock_id;
                if (!$stockId) continue;

                $discount = (float)($item['discount_amount'] ?? 0);
                $stockDiscounts[$stockId] = ($stockDiscounts[$stockId] ?? 0) + $discount;
                $totalOverallDiscount += $discount;
            }
        }

        $totalOverallProfit = 0;

        foreach ($stocks as $stock) {
            $mrp = $stock->mrp ?? 0;
            $sellingPrice = $stock->price ?? 0;
            $qty = $stock->usages_sum_quantity ?? 0;
            $discount = $stockDiscounts[$stock->id] ?? 0;

            $grossProfit = ($sellingPrice - $mrp) * $qty;
            $netProfit = $grossProfit - $discount;

            $stock->calculated_profit = $netProfit;
            $stock->gross_profit = $grossProfit;
            $stock->total_discount = $discount;
            $stock->units_sold = $qty;

            $totalOverallProfit += $netProfit;
        }

        return [
            'stocks' => $stocks,
            'totalOverallProfit' => $totalOverallProfit,
            'totalOverallDiscount' => $totalOverallDiscount,
            'generationDate' => now()->format('d-m-Y H:i')
        ];
    }
}
