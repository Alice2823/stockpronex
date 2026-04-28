<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('Stock Usage History') }}
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 mb-6">
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200 uppercase tracking-tight">{{ __('Usage Log') }}</h3>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-4 md:mt-0">
                    
                    <!-- Export Buttons (Grid on mobile, inline on sm) -->
                    <div class="grid grid-cols-2 gap-3 w-full sm:w-auto">
                        <a href="{{ route('usage.export', 'excel') }}"
                            class="flex items-center justify-center w-full px-4 py-2.5 bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all active:scale-95 group">
                            <div class="p-1.5 mr-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('Excel') }}</span>
                        </a>
                        <a href="{{ route('usage.export', 'pdf') }}"
                            class="flex items-center justify-center w-full px-4 py-2.5 bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all active:scale-95 group">
                            <div class="p-1.5 mr-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('PDF') }}</span>
                        </a>
                    </div>

                    <!-- Action Buttons (Grid on mobile, inline on sm) -->
                    <div class="grid grid-cols-2 sm:flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('usage.barcode') }}"
                            class="flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-gray-800/80 border border-blue-200 dark:border-blue-900/50 rounded-xl shadow-sm hover:shadow-md hover:bg-blue-50 dark:hover:bg-gray-700/80 transition-all active:scale-95 group">
                            <div class="p-1.5 mr-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-blue-700 dark:text-blue-400">{{ __('Barcode') }}</span>
                        </a>
                        <a href="{{ route('usage.create') }}"
                            class="flex items-center justify-center w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 hover:from-blue-500 hover:to-indigo-500 transition-all active:scale-95 text-white group">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            <span class="text-xs sm:text-sm font-bold tracking-wide">{{ __('Record') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Usage Table -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 transition-all">
                <div class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    @if($usages->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Stock Item') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Quantity Used') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Date') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Notes') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Invoice') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                    @foreach ($usages as $usage)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $usage->stock->name }}</div>
                                                @if(isset($usage->combined_tracked_items) && count($usage->combined_tracked_items) > 0)
                                                    <div class="flex flex-wrap gap-1.5 mt-2 max-w-[240px] sm:max-w-xs">
                                                        @foreach($usage->combined_tracked_items as $item)
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 tracking-tight" title="{{ $item }}">
                                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4"></path></svg>
                                                                {{ str_replace('Barcode: ', '', $item) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-1 inline-flex text-xs font-black rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 shadow-sm">
                                                    -{{ formatIndianNumber($usage->quantity) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-300">
                                                    {{ $usage->created_at->setTimezone('Asia/Kolkata')->format('M d, Y h:i A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top">
                                                <div class="text-sm text-gray-500 dark:text-gray-400 min-w-[240px] sm:min-w-[320px] whitespace-pre-wrap leading-relaxed">{{ $usage->notes ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                                @if($usage->consolidated_invoice)
                                                    <a href="{{ route('invoice.download', $usage->consolidated_invoice->id) }}" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ __('Download Invoice') }}
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">{{ __('No Invoice') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden flex flex-col gap-4 p-4 bg-gray-50 dark:bg-gray-950/50">
                            @foreach ($usages as $usage)
                                <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 shadow-sm">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-base font-black text-gray-900 dark:text-white">{{ $usage->stock->name }}</div>
                                            <div class="text-xs font-bold text-gray-500 mt-0.5">{{ $usage->created_at->setTimezone('Asia/Kolkata')->format('M d, Y h:i A') }}</div>
                                        </div>
                                        <span class="px-2.5 py-1 inline-flex text-sm font-black rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 shadow-sm">
                                            -{{ formatIndianNumber($usage->quantity) }}
                                        </span>
                                    </div>

                                    @if(isset($usage->combined_tracked_items) && count($usage->combined_tracked_items) > 0)
                                        <div class="flex flex-wrap gap-1.5 mb-3">
                                            @foreach($usage->combined_tracked_items as $item)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 tracking-tight">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4"></path></svg>
                                                    {{ str_replace('Barcode: ', '', $item) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($usage->notes)
                                        <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700/50 text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap leading-relaxed mb-3">
                                            {{ $usage->notes }}
                                        </div>
                                    @endif

                                    <div class="pt-3 border-t border-gray-100 dark:border-gray-800">
                                        @if($usage->consolidated_invoice)
                                            <a href="{{ route('invoice.download', $usage->consolidated_invoice->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl text-sm font-bold border border-blue-100 dark:border-blue-800/50 hover:bg-blue-100 transition-colors">
                                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ __('Download Invoice') }}
                                            </a>
                                        @else
                                            <div class="text-center w-full py-1">
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('No Invoice') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No usage recorded') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Record when you use stock to track your history.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 pb-6 text-center text-[11px] font-black tracking-widest text-gray-500 dark:text-gray-400 uppercase">
                &copy; {{ date('Y') }} <span class="text-gray-900 dark:text-white">{{ __('STOCK') }}</span><span class="text-blue-600 dark:text-blue-500">{{ __('PRONEX') }}</span>. {{ __('SYSTEM MANAGED SECURELY.') }}
            </div>
        </div>
    </div>
</x-app-layout>