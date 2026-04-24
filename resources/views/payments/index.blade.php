<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Payment Received') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center px-5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 active:scale-95">
                    <svg class="h-4 w-4 mr-2.5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
            
            <!-- Payment Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Revenue -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Total Revenue') }}</div>
                            <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 leading-tight">
                                {{ Auth::user()->currency_symbol }}{{ number_format($summary['onlineTotal'] + $summary['cashTotal'], 0) }}
                            </div>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-[10px] text-gray-500 dark:text-gray-400 font-bold tracking-widest uppercase">{{ $summary['onlineCount'] + $summary['cashCount'] }} {{ __('transactions') }}</div>
                </div>

                <!-- Online Payments -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Online Payments') }}</div>
                            <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 leading-tight">
                                {{ Auth::user()->currency_symbol }}{{ number_format($summary['onlineTotal'], 0) }}
                            </div>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/40 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-[10px] text-gray-500 dark:text-gray-400 font-bold tracking-widest uppercase">{{ $summary['onlineCount'] }} {{ __('online transactions') }}</div>
                </div>

                <!-- Cash Payments -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Cash Payments') }}</div>
                            <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 leading-tight">
                                {{ Auth::user()->currency_symbol }}{{ number_format($summary['cashTotal'], 0) }}
                            </div>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-xl">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-[10px] text-gray-500 dark:text-gray-400 font-bold tracking-widest uppercase">{{ $summary['cashCount'] }} {{ __('cash transactions') }}</div>
                </div>
            </div>
 
            <!-- Analytics Section -->
            <div id="analyticsSection" class="hidden mb-8 transition-all duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bar Chart -->
                    <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Daily Payment Distribution') }}</h3>
                            <div class="p-1.5 bg-green-100 dark:bg-green-900/40 rounded-lg">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="h-[250px] w-full">
                            <canvas id="paymentsBarChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Line Chart -->
                    <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Total Revenue Trend') }}</h3>
                            <div class="p-1.5 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                            </div>
                        </div>
                        <div class="h-[250px] w-full">
                            <canvas id="paymentsLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History Table -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 transition-all duration-300">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-50 dark:bg-gray-800/50 gap-4">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ __('Payment History') }}</h3>
                    
                    <form method="GET" action="{{ route('dashboard.payments') }}" class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-3 w-full md:w-auto">
                        <div class="flex items-center space-x-2">
                            <label for="start_date" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">{{ __('From') }}</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="text-sm rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label for="end_date" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">{{ __('To') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="text-sm rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5">
                        </div>
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white bg-gray-800 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-sm transition-all">
                            {{ __('Filter') }}
                        </button>
                        
                        <div class="flex flex-wrap items-center mt-2 sm:mt-0 sm:ml-2 border-t sm:border-t-0 sm:border-l border-gray-300 dark:border-gray-700 pt-3 sm:pt-0 sm:pl-4 gap-2 w-full sm:w-auto">
                            <button type="button" id="toggleAnalytics" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all" title="{{ __('Show Analytics') }}">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                {{ __('Analytics') }}
                            </button>
                            <a href="{{ route('dashboard.payments.export.pdf', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-all" title="{{ __('Export to PDF') }}">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('PDF') }}
                            </a>
                            <a href="{{ route('dashboard.payments.export.csv', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all" title="{{ __('Export to Excel/CSV') }}">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Excel') }}
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Invoice / Product') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Customer') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Total Amount') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Payment Method') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black text-blue-600 dark:text-blue-400">{{ $invoice->invoice_number }}</div>
                                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $invoice->stock->name ?? __('Unknown Product') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->customer_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">{{ $invoice->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-900 dark:text-white">
                                            {{ Auth::user()->currency_symbol }}
                                            {{ number_format($invoice->amount, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invoice->payment_method === 'online')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 uppercase tracking-widest">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                {{ __('Online') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 uppercase tracking-widest">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ __('Cash') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                        <a href="{{ route('invoice.download', $invoice->id) }}" class="inline-flex items-center justify-center px-3 py-1 border border-transparent text-xs font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-300">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            {{ __('PDF') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">{{ __('No payments found') }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 font-medium">{{ __('Get started by using the stock usage tab to generate invoices.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 pb-6 text-center text-[11px] font-black tracking-widest text-gray-500 dark:text-gray-400 uppercase">
                &copy; {{ date('Y') }} <span class="text-gray-900 dark:text-white">{{ __('STOCK') }}</span><span class="text-blue-600 dark:text-blue-500">{{ __('PRONEX') }}</span>. {{ __('SYSTEM MANAGED SECURELY.') }}
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let barChart = null;
        let lineChart = null;

        document.getElementById('toggleAnalytics').addEventListener('click', function() {
            const section = document.getElementById('analyticsSection');
            const isHidden = section.classList.contains('hidden');
            
            if (isHidden) {
                section.classList.remove('hidden');
                section.style.opacity = '0';
                setTimeout(() => {
                    section.style.opacity = '1';
                }, 10);
                
                this.innerHTML = `
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('Close') }}
                `;
                this.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                this.classList.add('bg-gray-600', 'hover:bg-gray-700');
                
                fetchAnalyticsData();
            } else {
                section.classList.add('hidden');
                this.innerHTML = `
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('Analytics') }}
                `;
                this.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                this.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        });

        function fetchAnalyticsData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const url = `{{ route('dashboard.payments.analytics') }}?start_date=${startDate}&end_date=${endDate}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    renderCharts(data);
                })
                .catch(error => console.error('Error fetching analytics:', error));
        }

        function renderCharts(data) {
            const ctxBar = document.getElementById('paymentsBarChart').getContext('2d');
            const ctxLine = document.getElementById('paymentsLineChart').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
            const textColor = isDark ? '#9ca3af' : '#64748b';

            if (barChart) barChart.destroy();
            if (lineChart) lineChart.destroy();

            // Bar Chart Configuration
            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: '{{ __('Online') }}',
                            data: data.online,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                        {
                            label: '{{ __('Cash') }}',
                            data: data.cash,
                            backgroundColor: 'rgba(107, 114, 128, 0.6)',
                            borderColor: 'rgb(107, 114, 128)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                color: textColor,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { weight: '600', size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#1f2937',
                            bodyColor: isDark ? '#9ca3af' : '#64748b',
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: true,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10, weight: '600' } }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { size: 10, weight: '600' } }
                        }
                    }
                }
            });

            // Line Chart Configuration
            lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: '{{ __('Total Revenue') }}',
                        data: data.total,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#3b82f6',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                color: textColor,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { weight: '600', size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#1f2937',
                            bodyColor: isDark ? '#9ca3af' : '#64748b',
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: true,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10, weight: '600' } }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { size: 10, weight: '600' } }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
