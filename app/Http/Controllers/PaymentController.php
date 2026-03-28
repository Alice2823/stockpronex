<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display the payments page with history
     */
    public function index(Request $request)
    {
        if (!Auth::user()->hasFeature('payments')) {
            return redirect()->route('subscription.index')->with('error', 'Payment Received dashboard is a Premium feature. Please upgrade to access.');
        }

        $query = Invoice::where('user_id', Auth::id())->with('stock');

        // Apply date filtering if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        $onlineCount = $invoices->where('payment_method', 'online')->count();
        $onlineTotal = $invoices->where('payment_method', 'online')->sum('amount');
        $cashCount = $invoices->where('payment_method', 'cash')->count();
        $cashTotal = $invoices->where('payment_method', 'cash')->sum('amount');
        $summary = compact('onlineCount', 'onlineTotal', 'cashCount', 'cashTotal');

        return view('payments.index', compact('invoices', 'summary'));
    }

    /**
     * Export payment history to CSV (Excel compatible)
     */
    public function exportCsv(Request $request)
    {
        if (!Auth::user()->hasFeature('payments')) {
            return redirect()->route('subscription.index')->with('error', 'Payment history export is a Premium feature.');
        }

        $query = Invoice::where('user_id', Auth::id())->with('stock');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        $onlineCount = $invoices->where('payment_method', 'online')->count();
        $onlineTotal = $invoices->where('payment_method', 'online')->sum('amount');
        $cashCount = $invoices->where('payment_method', 'cash')->count();
        $cashTotal = $invoices->where('payment_method', 'cash')->sum('amount');
        $totalCount = $invoices->count();
        $totalAmount = $invoices->sum('amount');

        $filename = "payment_history_" . now('Asia/Kolkata')->format('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [__('Date'), __('Time'), __('Invoice Number'), __('Product Name'), __('Customer Name'), __('Phone'), __('Amount'), __('Payment Method')];

        $callback = function() use($invoices, $columns, $onlineCount, $onlineTotal, $cashCount, $cashTotal, $totalCount, $totalAmount) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [__('PAYMENT SUMMARY')]);
            fputcsv($file, [__('Method'), __('Transactions'), __('Total Amount')]);
            fputcsv($file, [__('ONLINE'), $onlineCount, $onlineTotal]);
            fputcsv($file, [__('CASH'), $cashCount, $cashTotal]);
            fputcsv($file, [__('TOTAL'), $totalCount, $totalAmount]);
            fputcsv($file, []); // Empty row for separation
            
            fputcsv($file, $columns);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->created_at->setTimezone('Asia/Kolkata')->format('M d, Y'),
                    $invoice->created_at->setTimezone('Asia/Kolkata')->format('h:i A'),
                    $invoice->invoice_number,
                    $invoice->stock ? $invoice->stock->name : __('Unknown Product'),
                    $invoice->customer_name,
                    $invoice->phone,
                    $invoice->amount,
                    __($invoice->payment_method === 'online' ? 'ONLINE' : 'CASH')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export payment history to PDF
     */
    public function exportPdf(Request $request)
    {
        if (!Auth::user()->hasFeature('payments')) {
            return redirect()->route('subscription.index')->with('error', 'Payment history export is a Premium feature.');
        }

        $query = Invoice::where('user_id', Auth::id())->with('stock');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $onlineCount = $invoices->where('payment_method', 'online')->count();
        $onlineTotal = $invoices->where('payment_method', 'online')->sum('amount');
        $cashCount = $invoices->where('payment_method', 'cash')->count();
        $cashTotal = $invoices->where('payment_method', 'cash')->sum('amount');
        $summary = compact('onlineCount', 'onlineTotal', 'cashCount', 'cashTotal');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.pdf', compact('invoices', 'startDate', 'endDate', 'summary'))
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans'
            ]);
        
        return $pdf->download("payment_history_" . now('Asia/Kolkata')->format('Y-m-d_H-i-s') . ".pdf");
    }

    /**
     * Get analytics data for payments (AJAX)
     */
    public function getAnalyticsData(Request $request)
    {
        if (!Auth::user()->hasFeature('payments')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = Auth::id();
        
        // Default to last 30 days if no dates provided
        $startDate = $request->filled('start_date') 
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay() 
            : \Carbon\Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = $request->filled('end_date') 
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay() 
            : \Carbon\Carbon::now()->endOfDay();

        $payments = Invoice::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, payment_method, SUM(amount) as total')
            ->groupBy('date', 'payment_method')
            ->orderBy('date')
            ->get();

        $dates = $payments->pluck('date')->unique()->sort()->values();
        
        $onlineData = [];
        $cashData = [];
        $totalData = [];
        $labels = [];

        foreach ($dates as $date) {
            $online = $payments->where('date', $date)->where('payment_method', 'online')->first()->total ?? 0;
            $cash = $payments->where('date', $date)->where('payment_method', 'cash')->first()->total ?? 0;
            
            $onlineData[] = (float)$online;
            $cashData[] = (float)$cash;
            $totalData[] = (float)($online + $cash);
            $labels[] = \Carbon\Carbon::parse($date)->format('M d');
        }

        return response()->json([
            'labels' => $labels,
            'online' => $onlineData,
            'cash' => $cashData,
            'total' => $totalData,
            'summary' => [
                'total_revenue' => array_sum($totalData),
                'online_total' => array_sum($onlineData),
                'cash_total' => array_sum($cashData),
            ]
        ]);
    }
}
