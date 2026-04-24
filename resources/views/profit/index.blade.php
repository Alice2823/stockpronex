<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight flex items-center">
                <svg class="w-7 h-7 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                {{ __('Profit Management') }}
            </h2>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                <div class="flex flex-row items-center gap-2 mr-0 sm:mr-4 w-full sm:w-auto">
                    <a href="{{ route('dashboard.profit.export.pdf') }}" class="w-full sm:w-auto flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6l-4-4H9z"/><path d="M12 2.5V6a1 1 0 001 1h3.5L12 2.5z"/></svg>
                        PDF
                    </a>
                    <a href="{{ route('dashboard.profit.export.excel') }}" class="w-full sm:w-auto flex items-center justify-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        EXCEL
                    </a>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div class="w-full sm:w-auto bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 font-bold px-4 py-2 rounded-lg border border-red-200 dark:border-red-800 shadow-sm flex items-center justify-between sm:justify-start">
                        <span class="mr-2 text-[10px] sm:text-xs uppercase tracking-wider">{{ __('Total Discounts:') }}</span>
                        <span class="text-lg">₹{{ number_format($totalOverallDiscount, 2) }}</span>
                    </div>
                    <div class="w-full sm:w-auto bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 font-bold px-4 py-2 rounded-lg border border-green-200 dark:border-green-800 shadow-sm flex items-center justify-between sm:justify-start">
                        <span class="mr-2 text-[10px] sm:text-xs uppercase tracking-wider">{{ __('Net Profit Earned:') }}</span>
                        <span class="text-xl">₹{{ number_format($totalOverallProfit, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                            <span class="inline-flex items-center"><span class="font-bold text-gray-700 dark:text-gray-300 mr-1">{{ __('Cost (MRP):') }}</span> ₹{{ number_format($stock->mrp ?? 0, 2) }}</span>
                                            <span class="inline-flex items-center"><span class="font-bold text-gray-700 dark:text-gray-300 mr-1">{{ __('Selling Price:') }}</span> ₹{{ number_format($stock->price, 2) }}</span>
                                            <span class="inline-flex items-center text-blue-600 dark:text-blue-400"><span class="mr-1">{{ __('Total Units Sold:') }}</span> {{ number_format($stock->units_sold ?? 0) }}</span>
                                        </div>
                                        @if(($stock->total_discount ?? 0) > 0)
                                            <div class="mt-2 flex items-center">
                                                <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter border border-red-100 dark:border-red-900/50">
                                                    {{ __('Total Discounts Given:') }} ₹{{ number_format($stock->total_discount, 2) }}
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
                                                ₹{{ number_format($stock->gross_profit ?? 0, 2) }}
                                            </p>
                                        </div>
                                    @endif
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Net Profit') }}</p>
                                        <p class="text-2xl font-black {{ $stock->calculated_profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $stock->calculated_profit >= 0 ? '+' : '-' }}₹{{ number_format(abs($stock->calculated_profit), 2) }}
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
                                                    <td class="py-2 pr-4 text-xs text-right font-medium text-gray-700 dark:text-gray-300">₹{{ number_format($sale->gross_profit, 2) }}</td>
                                                    <td class="py-2 pr-4 text-xs text-right font-bold text-red-500 dark:text-red-400">₹{{ number_format($sale->discount, 2) }}</td>
                                                    <td class="py-2 text-xs text-right font-black tracking-tight {{ $sale->net_profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $sale->net_profit >= 0 ? '+' : '-' }}₹{{ number_format(abs($sale->net_profit), 2) }}
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
