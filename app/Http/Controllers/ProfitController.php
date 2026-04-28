<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProfitExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ReportService;

class ProfitController extends Controller
{
    /**
     * Display the profit analytics for all user's stocks
     */
    public function index(ReportService $reportService)
    {
        if (!Auth::user()->hasFeature('profit_manage')) {
            return redirect()->route('subscription.index');
        }
        $data = $reportService->getProfitData(Auth::user());
        return view('profit.index', $data);
    }

    /**
     * Export profit breakdown to professional PDF
     */
    public function exportPdf(ReportService $reportService)
    {
        $data = $reportService->getProfitData(Auth::user());
        $pdf = Pdf::loadView('profit.pdf', $data);
        return $pdf->download('Profit_Report_' . now()->format('d_M_Y') . '.pdf');
    }

    /**
     * Export profit data to Excel spreadsheet
     */
    public function exportExcel(ReportService $reportService)
    {
        $data = $reportService->getProfitData(Auth::user());
        return Excel::download(new ProfitExport($data), 'Profit_Data_' . now()->format('d_M_Y') . '.xlsx');
    }
}
