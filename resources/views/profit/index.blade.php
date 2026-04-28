<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight flex items-center">
                <svg class="w-7 h-7 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                {{ __('Profit Management') }}
            </h2>
            
            <div class="flex flex-col sm:flex-row items-center gap-6 mt-4 sm:mt-0 w-full sm:w-auto">
                <!-- Export Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard.profit.export.pdf') }}" class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 rounded-xl hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300 shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ __('PDF Export') }}</span>
                    </a>
                    <a href="{{ route('dashboard.profit.export.excel') }}" class="flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30 rounded-xl hover:bg-green-600 hover:text-white dark:hover:bg-green-600 dark:hover:text-white transition-all duration-300 shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Excel Export') }}</span>
                    </a>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <!-- Discount Card -->
                    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-3 pr-8 shadow-sm border border-red-100 dark:border-red-900/30 group transition-all duration-300 flex items-center gap-3">
                        <div class="absolute right-[-5%] top-[-10%] opacity-[0.03] group-hover:scale-110 transition-transform duration-500 text-red-600">
                             <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="h-9 w-9 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-600 dark:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">{{ __('Total Discounts') }}</p>
                            <p class="text-base font-black text-red-600 dark:text-red-400 mt-1">{{ Auth::user()->currency_symbol }}{{ formatIndianNumber($totalOverallDiscount, 2) }}</p>
                        </div>
                    </div>

                    <!-- Profit Card -->
                    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-3 pr-8 shadow-sm border border-green-100 dark:border-green-900/30 group transition-all duration-300 flex items-center gap-3">
                        <div class="absolute right-[-5%] top-[-10%] opacity-[0.03] group-hover:scale-110 transition-transform duration-500 text-green-600">
                             <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="h-9 w-9 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">{{ __('Net Profit Earned') }}</p>
                            <p class="text-lg font-black text-green-600 dark:text-green-400 mt-1">{{ Auth::user()->currency_symbol }}{{ formatIndianNumber($totalOverallProfit, 2) }}</p>
                        </div>
                    </div>
                </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200 uppercase tracking-tight">{{ __('Stock Wise Profit Breakdowns') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Real-time profit tracking calculated as (Selling Price - MRP) minus any Discounts given.') }}</p>
            </div>

            @if($stocks->count() > 0)
                <div class="space-y-4">
                    @foreach($stocks as $stock)
                        <!-- Single Stock Bar item -->
                        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow sm:rounded-xl border border-gray-100 dark:border-gray-800 transition-all hover:shadow-lg flex flex-col p-5 relative group">
                            
                            <!-- Small Accent bar on left -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $stock->calculated_profit > 0 ? 'bg-green-500' : ($stock->calculated_profit < 0 ? 'bg-red-500' : 'bg-gray-300 dark:bg-gray-700') }} z-0"></div>
                            
                            <div class="flex flex-col sm:flex-row justify-between items-center w-full relative z-10">
                                <!-- Left: Stock Details & Name -->
                                <div class="flex items-center mb-4 sm:mb-0 w-full sm:w-auto">
                                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xl mr-4">
                                        {{ substr($stock->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $stock->name }}</h4>
                                        <div class="flex flex-wrap text-sm text-gray-500 dark:text-gray-400 mt-1 gap-x-4 font-medium">
                                            <span class="inline-flex items-center"><span class="font-bold text-gray-700 dark:text-gray-300 mr-1">{{ __('Cost (MRP):') }}</span> {{ Auth::user()->currency_symbol }}{{ formatIndianNumber($stock->mrp ?? 0, 2) }}</span>
                                            <span class="inline-flex items-center"><span class="font-bold text-gray-700 dark:text-gray-300 mr-1">{{ __('Selling Price:') }}</span> {{ Auth::user()->currency_symbol }}{{ formatIndianNumber($stock->price, 2) }}</span>
                                            <span class="inline-flex items-center text-blue-600 dark:text-blue-400"><span class="mr-1">{{ __('Total Units Sold:') }}</span> {{ formatIndianNumber($stock->units_sold ?? 0) }}</span>
                                        </div>
                                        @if(($stock->total_discount ?? 0) > 0)
                                            <div class="mt-2 flex items-center">
                                                <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter border border-red-100 dark:border-red-900/50">
                                                    {{ __('Total Discounts Given:') }} {{ Auth::user()->currency_symbol }}{{ formatIndianNumber($stock->total_discount, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Right: Profit Readout -->
                                <div class="w-full sm:w-auto mt-4 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-800 flex justify-between sm:justify-end items-center sm:space-x-8">
                                    @if(($stock->total_discount ?? 0) > 0)
                                        <div class="text-left sm:text-right hidden sm:block">
                                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('Gross Profit') }}</p>
                                            <p class="text-base font-bold text-gray-700 dark:text-gray-300">
                                                {{ Auth::user()->currency_symbol }}{{ formatIndianNumber($stock->gross_profit ?? 0, 2) }}
                                            </p>
                                        </div>
                                    @endif
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Net Profit') }}</p>
                                        <p class="text-2xl font-black {{ $stock->calculated_profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $stock->calculated_profit >= 0 ? '+' : '-' }}{{ Auth::user()->currency_symbol }}{{ formatIndianNumber(abs($stock->calculated_profit), 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if(isset($stock->sales_history) && $stock->sales_history->count() > 0)
                            <details class="w-full mt-5 pt-4 border-t border-gray-100 dark:border-gray-800/60 group relative z-10">
                                <summary class="flex items-center text-[11px] font-black text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 uppercase tracking-widest cursor-pointer list-none focus:outline-none w-max">
                                    <svg class="w-4 h-4 mr-1 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    {{ __('View Sales Breakdown') }}
                                </summary>
                                <div class="mt-4 overflow-x-auto pb-2">
                                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800/50">
                                        <thead>
                                            <tr>
                                                <th class="text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Date') }}</th>
                                                <th class="text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Invoice') }}</th>
                                                <th class="text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Qty') }}</th>
                                                <th class="text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Gross') }}</th>
                                                <th class="text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Discount') }}</th>
                                                <th class="text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase pb-2">{{ __('Net Profit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800/30">
                                            @foreach($stock->sales_history as $sale)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                                    <td class="py-2 pr-4 text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $sale->date->setTimezone('Asia/Kolkata')->format('d M y, h:ia') }}</td>
                                                    <td class="py-2 pr-4 text-xs font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">{{ $sale->invoice_number }}</td>
                                                    <td class="py-2 pr-4 text-xs text-right font-medium text-gray-700 dark:text-gray-300">{{ $sale->qty }}</td>
                                                    <td class="py-2 pr-4 text-xs text-right font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->currency_symbol }}{{ formatIndianNumber($sale->gross_profit, 2) }}</td>
                                                    <td class="py-2 pr-4 text-xs text-right font-bold text-red-500 dark:text-red-400">{{ Auth::user()->currency_symbol }}{{ formatIndianNumber($sale->discount, 2) }}</td>
                                                    <td class="py-2 text-xs text-right font-black tracking-tight {{ $sale->net_profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $sale->net_profit >= 0 ? '+' : '-' }}{{ Auth::user()->currency_symbol }}{{ formatIndianNumber(abs($sale->net_profit), 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                            <style>
                                details > summary::-webkit-details-marker {
                                    display: none;
                                }
                            </style>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow sm:rounded-xl border border-gray-100 dark:border-gray-800 text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ __('No stocks available') }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Profit tracking metrics will appear here once you add stock inventory.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
