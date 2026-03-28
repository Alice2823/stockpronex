<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasFeature('analytics')) {
            return redirect()->route('subscription.index')->with('error', 'Advanced Analytics is a Pro feature. Please upgrade to access.');
        }

        // Only logged-in user's products
        $products = Stock::where('user_id', Auth::id())
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('dashboard.analytics', compact('products'));
    }

    public function getData(Request $request)
    {
        if (!Auth::user()->hasFeature('analytics')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $this->prepareAnalyticsData($request);
        
        if (isset($data['empty']) && $data['empty']) {
            return response()->json([
                'cards' => $data['cards'],
                'charts' => $data['charts']
            ]);
        }

        return response()->json([
            'cards' => $data['cards'],
            'charts' => $data['charts']
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->prepareAnalyticsData($request);
        $date = date('Y-m-d');

        $pdf = Pdf::loadView('pdf.dashboard-report', [
            'cards' => $data['cards'],
            'top_products' => $data['top_products'],
            'filters' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]
        ])->setPaper('A4', 'portrait')
        ->setOptions([
            'dpi' => 150,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('dashboard_analytics_report_' . $date . '.pdf');
    }

    protected function prepareAnalyticsData(Request $request)
    {
        $userId = Auth::id();
        $hasProducts = Stock::where('user_id', $userId)->exists();

        if (!$hasProducts) {
            return [
                'empty' => true,
                'cards' => [
                    'total_stock' => 0,
                    'total_used' => 0,
                    'total_added' => 0,
                    'low_stock' => 0,
                ],
                'charts' => [
                    'usage_vs_added' => ['labels' => [], 'added' => [], 'used' => []],
                    'daily_usage' => ['labels' => [], 'data' => []],
                    'stock_trend' => ['labels' => [], 'data' => []],
                    'top_products' => ['labels' => [], 'data' => []]
                ],
                'top_products' => collect()
            ];
        }

        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $productId = $request->input('product_id');

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT FIX: Filter by logged-in user
        |--------------------------------------------------------------------------
        */

        $stockQuery = Stock::where('user_id', $userId);
        $usageQuery = StockUsage::whereHas('stock', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($productId) {
            $stockQuery->where('id', $productId);
            $usageQuery->where('stock_id', $productId);
        }

        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */

        $remainingStock = (clone $stockQuery)->sum('quantity');
        $totalUsed = (clone $usageQuery)->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        $totalAdded = (clone $stockQuery)->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        $lowStockCount = Stock::where('user_id', $userId)->where('quantity', '<', 10)->count();

        /*
        |--------------------------------------------------------------------------
        | Charts
        |--------------------------------------------------------------------------
        */

        $dailyUsage = (clone $usageQuery)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->groupBy('date')->orderBy('date')->get();

        $dailyAdded = (clone $stockQuery)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->groupBy('date')->orderBy('date')->get();

        $dates = $dailyUsage->pluck('date')->merge($dailyAdded->pluck('date'))->unique()->sort()->values();
        $chartDataAdded = [];
        $chartDataUsed = [];
        foreach ($dates as $date) {
            $chartDataAdded[] = $dailyAdded->firstWhere('date', $date)->total ?? 0;
            $chartDataUsed[] = $dailyUsage->firstWhere('date', $date)->total ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Trend
        |--------------------------------------------------------------------------
        */

        $preStartAdded = Stock::where('user_id', $userId);
        $preStartUsed = StockUsage::whereHas('stock', function ($q) use ($userId) { $q->where('user_id', $userId); });
        if ($productId) {
            $preStartAdded->where('id', $productId);
            $preStartUsed->where('stock_id', $productId);
        }
        $totalAddedBefore = $preStartAdded->where('created_at', '<', $startDate)->sum('quantity');
        $totalUsedBefore = $preStartUsed->where('created_at', '<', $startDate)->sum('quantity');
        $currentBalance = $totalAddedBefore - $totalUsedBefore;

        $trendData = [];
        $trendDates = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dayStr = $date->format('Y-m-d');
            $addedToday = $dailyAdded->where('date', $dayStr)->first()->total ?? 0;
            $usedToday = $dailyUsage->where('date', $dayStr)->first()->total ?? 0;
            $currentBalance += ($addedToday - $usedToday);
            $trendData[] = max(0, $currentBalance);
            $trendDates[] = $dayStr;
        }

        /*
        |--------------------------------------------------------------------------
        | Top Products
        |--------------------------------------------------------------------------
        */

        $topProducts = (clone $usageQuery)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('stock_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('stock:id,name')
            ->groupBy('stock_id')->orderByDesc('total_quantity')->take(5)->get()
            ->map(function ($usage) {
                return ['name' => $usage->stock->name ?? 'Unknown', 'total' => $usage->total_quantity];
            });

        return [
            'cards' => [
                'total_stock' => $remainingStock,
                'total_used' => $totalUsed,
                'total_added' => $totalAdded,
                'low_stock' => $lowStockCount,
            ],
            'charts' => [
                'usage_vs_added' => ['labels' => $dates, 'added' => $chartDataAdded, 'used' => $chartDataUsed],
                'daily_usage' => ['labels' => $dailyUsage->pluck('date'), 'data' => $dailyUsage->pluck('total')],
                'stock_trend' => ['labels' => $trendDates, 'data' => $trendData],
                'top_products' => ['labels' => $topProducts->pluck('name'), 'data' => $topProducts->pluck('total')]
            ],
            'top_products' => $topProducts
        ];
    }

}