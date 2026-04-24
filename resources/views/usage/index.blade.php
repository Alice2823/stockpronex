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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 mb-6">
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ __('Usage Log') }}</h3>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto mt-4 sm:mt-0">
                    <a href="{{ route('usage.export', 'excel') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-bold rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Export Excel') }}
                    </a>
                    <a href="{{ route('usage.export', 'pdf') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-bold rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        {{ __('Export PDF') }}
                    </a>
                    <a href="{{ route('usage.barcode') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-bold rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4" />
                        </svg>
                        {{ __('Use via Barcode') }}
                    </a>
                    <a href="{{ route('usage.create') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        {{ __('Record Usage') }}
                    </a>
                </div>
            </div>

            <!-- Usage Table -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 transition-all">
                <div class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    @if($usages->count() > 0)
                        <div class="overflow-x-auto">
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $usage->stock->name }}</div>
                                                @if(isset($usage->combined_tracked_items) && count($usage->combined_tracked_items) > 0)
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        @foreach($usage->combined_tracked_items as $item)
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                                                {{ $item }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    -{{ number_format($usage->quantity) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $usage->created_at->setTimezone('Asia/Kolkata')->format('M d, Y h:i A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400" style="white-space: pre-line;">{{ $usage->notes ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                &copy; {{ date('Y') }} <span class="text-gray-900 dark:text-white">STOCK</span><span class="text-blue-600 dark:text-blue-500">PRONEX</span>. SYSTEM MANAGED SECURELY.
            </div>
        </div>
    </div>
</x-app-layout>