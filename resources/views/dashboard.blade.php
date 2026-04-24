<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Dashboard') }}
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

            <!-- Summary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <!-- Total Items -->
                <div
                    class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:shadow-md rounded-2xl sm:rounded-lg p-4 sm:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-2 sm:gap-4">
                        <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <svg class="h-5 w-5 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-[10px] sm:text-sm font-bold uppercase tracking-tight">{{ __('Total Stock Items') }}</p>
                            <p class="text-xl sm:text-3xl font-black text-gray-800 dark:text-white">{{ $stocks->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Units -->
                <div
                    class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:shadow-md rounded-2xl sm:rounded-lg p-4 sm:p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-2 sm:gap-4">
                        <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400">
                            <svg class="h-5 w-5 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-[10px] sm:text-sm font-bold uppercase tracking-tight">{{ __('Total Units') }}</p>
                            <p class="text-xl sm:text-3xl font-black text-gray-800 dark:text-white">{{ number_format($stocks->sum('quantity')) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Value -->
                <div
                    class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:shadow-md rounded-2xl sm:rounded-lg p-4 sm:p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-800 col-span-2 md:col-span-1">
                    <div class="flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-2 sm:gap-4">
                        <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center">
                            <span class="text-xl sm:text-3xl font-black">₹</span>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-[10px] sm:text-sm font-bold uppercase tracking-tight">{{ __('Portfolio Value') }}</p>
                            <p class="text-xl sm:text-3xl font-black text-gray-800 dark:text-white leading-tight">
                                ₹{{ number_format($stocks->sum(function ($stock) {
    return $stock->price * $stock->quantity; }), 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-green-600 hover:text-green-800"
                        onclick="this.parentElement.style.display='none'">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 mb-6">
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200 uppercase tracking-tight">{{ __('Inventory Items') }}</h3>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <div class="grid grid-cols-2 gap-3 w-full sm:w-auto">
                        <a href="{{ route('stocks.export', 'excel') }}"
                            class="flex items-center justify-center w-full px-4 py-2.5 bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all active:scale-95 group">
                            <div class="p-1.5 mr-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('Excel') }}</span>
                        </a>
                        <a href="{{ route('stocks.export', 'pdf') }}"
                            class="flex items-center justify-center w-full px-4 py-2.5 bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all active:scale-95 group">
                            <div class="p-1.5 mr-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('PDF') }}</span>
                        </a>
                    </div>
                    
                    <a href="{{ route('stocks.create') }}"
                        class="flex items-center justify-center w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 hover:from-blue-500 hover:to-indigo-500 transition-all active:scale-95 text-white group">
                        <svg class="h-5 w-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-bold tracking-wide">{{ __('Add Stock') }}</span>
                    </a>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-800 transition-all duration-300">
                <div class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    @if($stocks->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Name') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Quantity') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Price/Unit') }} ({{ Auth::user()->currency == 'INR' ? '₹' : (Auth::user()->currency == 'GBP' ? '£' : (Auth::user()->currency == 'EUR' ? '€' : '$')) }})</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Total Value') }} ({{ Auth::user()->currency == 'INR' ? '₹' : (Auth::user()->currency == 'GBP' ? '£' : (Auth::user()->currency == 'EUR' ? '€' : '$')) }})</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Description') }}</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                    @foreach ($stocks as $stock)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                                                        {{ substr($stock->name, 0, 1) }}
                                                    </div>
                                                     <div class="ml-4">
                                                        <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $stock->name }}
                                                        </div>
                                                        <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400">{{ __('ID') }}: #{{ $stock->id }}</div>
                                                        
                                                        @if($stock->business_attributes)
                                                            <div class="mt-1 flex flex-wrap gap-1">
                                                                 @foreach($stock->business_attributes as $key => $value)
                                                                    @if($value)
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-black bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800 uppercase tracking-tighter">
                                                                            {{ str_replace('_', ' ', $key) }}: {{ $value }}
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white font-bold">
                                                    {{ number_format($stock->quantity) }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-black uppercase tracking-widest {{ $stock->quantity < 5 ? 'text-red-500' : 'text-green-500' }}">
                                                    {{ $stock->quantity < 5 ? __('Low Stock') : __('In Stock') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-300">₹{{ number_format($stock->price, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-black text-gray-800 dark:text-white">
                                                    ₹{{ number_format($stock->price * $stock->quantity, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                                    {{ $stock->description ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                                <a href="{{ route('stock.barcode.index', $stock->id) }}"
                                                    class="text-green-600 dark:text-green-500 hover:text-green-900 dark:hover:text-green-300 mr-3 font-semibold transition-colors">{{ __('Barcode') }}</a>
                                                <a href="{{ route('stocks.edit', $stock) }}"
                                                    class="text-blue-600 dark:text-blue-500 hover:text-blue-900 dark:hover:text-blue-300 mr-3 font-semibold transition-colors">{{ __('Edit') }}</a>
                                                <form action="{{ route('stocks.destroy', $stock) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="{{ __('Delete') }}"
                                                        class="text-red-600 dark:text-red-500 hover:text-red-900 dark:hover:text-red-300 font-semibold transition-colors focus:outline-none"
                                                        onclick="return confirm('{{ __('Are you sure you want to delete') }} {{ $stock->name }}?')">
                                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden flex flex-col gap-4 p-4 bg-gray-50 dark:bg-gray-950/50">
                            @foreach ($stocks as $stock)
                                <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 shadow-sm flex flex-col gap-3">
                                    
                                    <!-- Header: Name & Quantity -->
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                                                {{ substr($stock->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $stock->name }}</div>
                                                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400">{{ __('ID') }}: #{{ $stock->id }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg text-gray-900 dark:text-white font-black leading-none">{{ number_format($stock->quantity) }}</div>
                                            <div class="text-[10px] font-black uppercase tracking-widest mt-1 {{ $stock->quantity < 5 ? 'text-red-500' : 'text-green-500' }}">
                                                {{ $stock->quantity < 5 ? __('Low Stock') : __('In Stock') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attributes -->
                                    @if($stock->business_attributes)
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($stock->business_attributes as $key => $value)
                                                @if($value)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-black bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800 uppercase tracking-tighter">
                                                        {{ str_replace('_', ' ', $key) }}: {{ $value }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Pricing -->
                                    <div class="grid grid-cols-2 gap-3 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700/50 mt-1">
                                        <div>
                                            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Price/Unit') }}</div>
                                            <div class="text-sm font-black text-gray-900 dark:text-white">₹{{ number_format($stock->price, 2) }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Total Value') }}</div>
                                            <div class="text-sm font-black text-blue-600 dark:text-blue-400">₹{{ number_format($stock->price * $stock->quantity, 2) }}</div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($stock->description)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2">
                                            {{ $stock->description }}
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="pt-3 mt-1 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                                        <a href="{{ route('stock.barcode.index', $stock->id) }}" class="inline-flex items-center text-xs font-bold text-green-600 dark:text-green-500 hover:text-green-700 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg border border-green-100 dark:border-green-800/50 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4"></path></svg>
                                            {{ __('Barcode') }}
                                        </a>
                                        
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('stocks.edit', $stock) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 hover:bg-blue-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('stocks.destroy', $stock) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete') }} {{ $stock->name }}?')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800/50 hover:bg-red-100 transition-colors focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No stocks found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Get started by creating a new stock item.') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('stocks.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Add New Stock') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Area -->
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-8 uppercase tracking-widest font-black">
                &copy; {{ date('Y') }} <span class="brand-stock dark:text-gray-100">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>. {{ __('System managed securely.') }}
            </div>

        </div>
    </div>
</x-app-layout>