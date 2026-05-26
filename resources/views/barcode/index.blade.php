<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Barcode Management:') }} <span class="text-blue-600 dark:text-blue-500">{{ $stock->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center px-5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 active:scale-95">
                    <svg class="h-4 w-4 mr-2.5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
            
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm border border-green-100 dark:border-green-800/50 flex items-center" role="alert">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Management Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Generate Option -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl sm:rounded-3xl p-8 border border-gray-100 dark:border-gray-800 border-t-4 border-t-blue-500 flex flex-col">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                             <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ __('Generate Barcodes') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">{{ __('Create unique tracking codes') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 flex-grow flex flex-col">
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase">{{ __('Current Stock:') }}</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ formatIndianNumber($stock->quantity) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase">{{ __('Generated:') }}</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $barcodes->count() }}</span>
                        </div>
                        
                        <form action="{{ route('stock.barcode.generate', $stock->id) }}" method="POST" class="mt-auto">
                            @csrf
                            <button type="submit" 
                                class="w-full mt-4 inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-black rounded-2xl text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40 transition-all shadow-xl active:scale-95 uppercase tracking-widest">
                                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Generate Barcodes') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Scan Option -->
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl sm:rounded-3xl p-8 border border-gray-100 dark:border-gray-800 border-t-4 border-t-green-500 flex flex-col">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ __('Scan Barcode') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">{{ __('Instant inventory usage') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col flex-grow justify-between">
                        <p class="text-gray-500 dark:text-gray-400 mb-8 mt-2 font-medium leading-relaxed">
                            {{ __('Ready to use items specifically? Point your camera at the printed barcode to automatically update stock levels and record usage history in one second.') }}
                        </p>
                        
                        <a href="{{ route('usage.barcode') }}" 
                            class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-black rounded-2xl text-white bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-500 focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900/40 transition-all shadow-xl active:scale-95 uppercase tracking-widest">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Open Scanner') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Barcode Display Area -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-800">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/30 dark:bg-gray-800/20">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ __('Generated Barcodes') }}</h3>
                    @if($barcodes->count() > 0)
                        <button onclick="window.print()" class="inline-flex items-center text-sm font-bold text-blue-600 dark:text-blue-500 hover:text-blue-800 dark:hover:text-blue-400 transition-colors uppercase tracking-widest">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            {{ __('Print Labels') }}
                        </button>
                    @endif
                </div>
                
                <div class="p-8">
                    @if($barcodes->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="printableArea">
                            @foreach($barcodes as $barcode)
                                <div class="relative group p-6 rounded-3xl border-2 {{ $barcode->status == 'used' ? 'bg-gray-100 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700 grayscale' : 'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 hover:border-blue-300 dark:hover:border-blue-500 shadow-sm' }} transition-all flex flex-col items-center">
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-4 right-4">
                                        <span class="px-3 py-1 rounded-full text-[9px] uppercase font-black tracking-widest {{ $barcode->status == 'used' ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400' }}">
                                            {{ __($barcode->status) }}
                                        </span>
                                    </div>

                                    <!-- REAL SCANNER-READY CODES -->
                                    <div class="p-4 bg-white rounded-2xl border border-gray-100 dark:border-gray-700 mb-4 flex flex-col items-center w-full">
                                        <!-- Barcode (1D) -->
                                        <div class="w-full flex flex-col items-center mb-6">
                                            <svg class="barcode-item w-full" 
                                                data-value="{{ $barcode->barcode }}"
                                                data-name="{{ $stock->name }}"></svg>
                                        </div>

                                        <div class="flex items-center space-x-2 mb-4 w-full">
                                            <div class="flex-grow h-px bg-gray-100 dark:bg-gray-800"></div>
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">{{ __('POWER SCAN') }}</span>
                                            <div class="flex-grow h-px bg-gray-100 dark:bg-gray-800"></div>
                                        </div>

                                        <!-- QR Code (2D) - High Error Correction -->
                                        <div class="qr-container bg-white p-3 rounded-2xl border border-gray-100 shadow-sm">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->errorCorrection('H')->margin(2)->generate($barcode->barcode) !!}
                                        </div>
                                        
                                        <div class="mt-4 font-[monospace] text-xs font-black tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">
                                            {{ $barcode->barcode }}
                                        </div>
                                    </div>

                                    <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight mb-1">{{ $stock->name }}</div>
                                    @if($barcode->imei || $barcode->serial)
                                        <div class="flex flex-wrap justify-center gap-2 mt-2">
                                            @if($barcode->imei)
                                                <span class="text-[9px] font-black uppercase tracking-tighter bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 px-2 py-0.5 rounded-lg border border-indigo-100 dark:border-indigo-800">IMEI: {{ $barcode->imei }}</span>
                                            @endif
                                            @if($barcode->serial)
                                                <span class="text-[9px] font-black uppercase tracking-tighter bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-2 py-0.5 rounded-lg border border-blue-100 dark:border-blue-800">SN: {{ $barcode->serial }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-4 uppercase tracking-widest">{{ __('Created') }} {{ \Carbon\Carbon::parse($barcode->created_at)->format('d M Y') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="p-4 rounded-full bg-gray-100 text-gray-400 inline-block mb-4">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-700">{{ __('No barcodes generated yet') }}</h4>
                            <p class="text-gray-500 mb-6">{{ __('Click the button above to create unique tracking codes for this stock item.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: block !important;
            }
            .group {
                border: 1px solid #ddd !important;
                break-inside: avoid;
                margin-bottom: 20px;
                padding: 10px !important;
                float: left;
                width: 200px;
            }
            .grayscale {
                display: none !important; /* Don't print used barcodes */
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.barcode-item').forEach(el => {
                const value = el.getAttribute('data-value');
                JsBarcode(el, value, {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 80,
                    displayValue: false,
                    margin: 25
                });
            });
        });
    </script>
</x-app-layout>
