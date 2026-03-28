<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Analytics Dashboard') }}
            </h2>
            @if(Auth::user()->business_name || Auth::user()->business_type)
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->business_name)
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ Auth::user()->business_name }}</span>
                    @endif
                    @if(Auth::user()->business_type)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            {{ Auth::user()->business_type }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <x-label for="start_date" :value="__('Start Date')" />
                        <x-input type="date" id="start_date" name="start_date" 
                            class="mt-1 block w-full"
                            value="{{ \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}" />
                    </div>
                    <div>
                        <x-label for="end_date" :value="__('End Date')" />
                        <x-input type="date" id="end_date" name="end_date"
                            class="mt-1 block w-full"
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                    </div>
                    <div>
                        <x-label for="product_id" :value="__('Product')" />
                        <x-select id="product_id" name="product_id" class="mt-1 block w-full">
                            <option value="">{{ __('All Products') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="fetchDashboardData()"
                            class="flex-1 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02] active:scale-95">
                            {{ __('Apply Filters') }}
                        </button>
                        <a href="{{ route('dashboard.analytics.export') }}" id="export_link"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-md text-sm font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform hover:scale-[1.02] active:scale-95">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('PDF') }}
                        </a>
                    </div>

                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Stock -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-blue-500 dark:border-blue-600 transition-colors duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-tight">{{ __('Remaining Stock') }}</p>
                            <p class="text-2xl font-extrabold text-gray-800 dark:text-white" id="card_total_stock">-</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Current Snapshot') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Used -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-red-500 dark:border-red-600 transition-colors duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-tight">{{ __('Total Used') }}</p>
                            <p class="text-2xl font-extrabold text-gray-800 dark:text-white" id="card_total_used">-</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">In Selected Period</p>
                        </div>
                    </div>
                </div>

                <!-- Total Added -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-green-500 dark:border-green-600 transition-colors duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-tight">{{ __('Stock Added') }}</p>
                            <p class="text-2xl font-extrabold text-gray-800 dark:text-white" id="card_total_added">-</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">In Selected Period</p>
                        </div>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-yellow-500 dark:border-yellow-600 transition-colors duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-tight">{{ __('Low Stock Items') }}</p>
                            <p class="text-2xl font-extrabold text-gray-800 dark:text-white" id="card_low_stock">-</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">&lt; 10 {{ __('Units') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Stock Added vs Used -->
                <div class="bg-white dark:bg-gray-900 p-6 shadow-md rounded-lg border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200 mb-4">{{ __('Stock Added vs. Used') }}</h3>
                    <div class="h-64">
                        <canvas id="chartAddedVsUsed"></canvas>
                    </div>
                </div>

                <!-- Daily Usage -->
                <div class="bg-white dark:bg-gray-900 p-6 shadow-md rounded-lg border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200 mb-4">{{ __('Daily Stock Usage') }}</h3>
                    <div class="h-64">
                        <canvas id="chartDailyUsage"></canvas>
                    </div>
                </div>

                <!-- Remaining Stock Trend -->
                <div class="bg-white dark:bg-gray-900 p-6 shadow-md rounded-lg border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200 mb-4">{{ __('Remaining Stock Trend') }}</h3>
                    <div class="h-64">
                        <canvas id="chartStockTrend"></canvas>
                    </div>
                </div>

                <!-- Top 5 Products -->
                <div class="bg-white dark:bg-gray-900 p-6 shadow-md rounded-lg border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200 mb-4">{{ __('Top 5 Most Used Products') }}</h3>
                    <div class="h-64">
                        <canvas id="chartTopProducts"></canvas>
                    </div>
                </div>
            </div>

            <!-- Footer Area -->
            <div class="mt-8 pb-6 text-center text-[11px] font-black tracking-widest text-gray-500 dark:text-gray-400 uppercase">
                &copy; {{ date('Y') }} <span class="text-gray-900 dark:text-white">STOCK</span><span class="text-blue-600 dark:text-blue-500">PRONEX</span>. SYSTEM MANAGED SECURELY.
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let charts = {};

        document.addEventListener('DOMContentLoaded', function () {
            fetchDashboardData();
        });

        function fetchDashboardData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const productId = document.getElementById('product_id').value;
 
            // Update export link with current filters
            const exportUrl = `{{ route('dashboard.analytics.export') }}?start_date=${startDate}&end_date=${endDate}&product_id=${productId}`;
            document.getElementById('export_link').href = exportUrl;

            const url = `{{ route('dashboard.analytics.data') }}?start_date=${startDate}&end_date=${endDate}&product_id=${productId}`;
 
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    updateCards(data.cards);
                    updateCharts(data.charts);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateCards(cards) {
            document.getElementById('card_total_stock').innerText = cards.total_stock.toLocaleString();
            document.getElementById('card_total_used').innerText = cards.total_used.toLocaleString();
            document.getElementById('card_total_added').innerText = cards.total_added.toLocaleString();
            document.getElementById('card_low_stock').innerText = cards.low_stock.toLocaleString();
        }

        function updateCharts(data) {
            // 1. Added vs Used
            renderChart('chartAddedVsUsed', 'line', {
                labels: data.usage_vs_added.labels,
                datasets: [
                    {
                        label: '{{ __('Stock Added') }}',
                        data: data.usage_vs_added.added,
                        borderColor: 'rgb(34, 197, 94)', // Green
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.3,
                        pointRadius: 4,
                        fill: false
                    },
                    {
                        label: '{{ __('Stock Used') }}',
                        data: data.usage_vs_added.used,
                        borderColor: 'rgb(239, 68, 68)', // Red
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        pointRadius: 4,
                        fill: false
                    }
                ]
            });

            // 2. Daily Usage
            renderChart('chartDailyUsage', 'bar', {
                labels: data.daily_usage.labels,
                datasets: [{
                    label: '{{ __('Units Used') }}',
                    data: data.daily_usage.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)', // Blue
                    borderColor: 'rgb(59, 130, 246)',
                    borderRadius: 8,
                    borderWidth: 1
                }]
            });

            // 3. Stock Trend (Area Chart)
            renderChart('chartStockTrend', 'line', {
                labels: data.stock_trend.labels,
                datasets: [{
                    label: '{{ __('Stock Level') }}',
                    data: data.stock_trend.data,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)', // Indigo area
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 3,
                    fill: 'start',
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }]
            });

            // 4. Top 5 Products (Doughnut Chart)
            renderChart('chartTopProducts', 'doughnut', {
                labels: data.top_products.labels,
                datasets: [{
                    label: '{{ __('Units Used') }}',
                    data: data.top_products.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                    ],
                    borderColor: document.documentElement.classList.contains('dark') ? '#111827' : '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 20
                }]
            }, {
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            });
        }

        function renderChart(canvasId, type, data, options = {}) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
            const textColor = isDark ? '#9ca3af' : '#64748b';

            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            // Determine if the chart should have scales (Cartesian)
            const isRadial = ['pie', 'doughnut', 'polarArea', 'radar'].includes(type);

            charts[canvasId] = new Chart(ctx, {
                type: type,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    ...options,
                    plugins: {
                        ...options.plugins,
                        legend: {
                            labels: {
                                color: textColor,
                                font: { weight: '600', family: "'Inter', sans-serif", size: 11 },
                                boxWidth: 12,
                                boxHeight: 12,
                                usePointStyle: true,
                                padding: 20
                            },
                            ...options.plugins?.legend
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#1f2937',
                            bodyColor: isDark ? '#9ca3af' : '#64748b',
                            borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            cornerRadius: 12,
                            usePointStyle: true
                        }
                    },
                    scales: isRadial ? {} : {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { 
                                color: textColor,
                                font: { size: 10, weight: '600' },
                                padding: 10
                            },
                            title: { display: false },
                            ...options.scales?.y
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: textColor,
                                font: { size: 10, weight: '600' },
                                padding: 10
                            },
                            ...options.scales?.x
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
