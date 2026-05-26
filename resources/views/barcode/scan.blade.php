<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Barcode Scanner') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center px-5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 active:scale-95">
                    <svg class="h-4 w-4 mr-2.5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
            
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 p-8">
                <div class="text-center mb-10">
                    <div class="inline-flex p-4 rounded-2xl bg-blue-50 text-blue-600 mb-4">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l-3 3h6l-3-3V4zM5 8h14M5 8a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2M5 8l7 4 7-4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ __('Ready to Scan') }}</h3>
                    <p class="text-gray-500 mt-2">{{ __('Point your camera at a') }} <span class="brand-stock">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex">Nex</span> {{ __('barcode to process it.') }}</p>
                </div>

                <!-- Scanner Container -->
                <div class="relative rounded-3xl overflow-hidden bg-gray-900 border-4 border-gray-800 shadow-inner group aspect-video md:aspect-square max-w-md mx-auto">
                    <div id="reader" style="width: 100%; min-height: 250px;"></div>
                    
                    <div class="absolute bottom-2 left-0 right-0 text-center z-10">
                        <span class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full font-bold shadow-sm backdrop-blur-sm">
                            {{ __('Tip: Scan the square QR code for faster results') }}
                        </span>
                    </div>
                    
                    <!-- Overlay UI (Visual only) -->
                    <div id="scanner-overlay" class="absolute inset-0 pointer-events-none flex items-center justify-center">
                         <div class="w-64 h-64 border-2 border-dashed border-blue-400 opacity-30 rounded-2xl animate-pulse"></div>
                    </div>
                </div>

                <!-- Manual Input (Fallback) -->
                <div class="mt-12 max-w-md mx-auto">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-400 font-medium">{{ __('OR ENTER MANUALLY') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-2">
                        <x-input type="text" id="manual-barcode" class="flex-1" placeholder="{{ __('Enter Code (e.g. STK-1-001)') }}" />
                        <button onclick="processBarcode(document.getElementById('manual-barcode').value)"
                            class="px-6 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg active:scale-95">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>

                <!-- Status Feedback -->
                <div id="status-container" class="mt-10 max-w-md mx-auto hidden transition-all duration-300 transform scale-95 opacity-0">
                    <div class="p-6 rounded-2xl flex items-center space-x-4">
                        <div id="status-icon" class="p-3 rounded-full flex-shrink-0"></div>
                        <div>
                            <p id="status-title" class="font-bold text-gray-900 text-lg"></p>
                            <p id="status-message" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 opacity-60 hover:opacity-100 transition-opacity">
                <div class="bg-white/50 p-6 rounded-2xl text-center border border-gray-100">
                    <div class="text-blue-500 font-bold mb-2">1. {{ __('Lighting') }}</div>
                    <p class="text-xs text-gray-600">{{ __('Ensure the barcode is well-lit and not reflective.') }}</p>
                </div>
                <div class="bg-white/50 p-6 rounded-2xl text-center border border-gray-100">
                    <div class="text-blue-500 font-bold mb-2">2. {{ __('Margins are Required') }}</div>
                    <p class="text-xs text-gray-600">{{ __('Ensure there is at least a small white border (quiet zone) around the barcode, especially left/right.') }}</p>
                </div>
                <div class="bg-white/50 p-6 rounded-2xl text-center border border-gray-100">
                    <div class="text-blue-500 font-bold mb-2">3. {{ __('Use QR for Screens') }}</div>
                    <p class="text-xs text-gray-600">{{ __('If scanning from a phone/laptop screen, try the square QR code for instant results.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner;
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            
            // Beep sound (Optional)
            try {
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.play();
            } catch(e) {}

            processBarcode(decodedText);
        }

        function processBarcode(barcode) {
            if (!barcode || isProcessing) return;
            
            // Trim whitespace from barcode
            barcode = barcode.trim();
            
            isProcessing = true;
            showStatus('{{ __("Processing...") }}', '{{ __("Updating stock records...") }}', 'blue');

            fetch("{{ route('barcode.mark-used') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ barcode: barcode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus('{{ __("Success!") }}', data.message, 'green');
                    // Reset after 3 seconds to allow next scan
                    setTimeout(() => {
                        resetStatus();
                        isProcessing = false;
                    }, 3000);
                } else {
                    showStatus('{{ __("Error") }}', data.message, 'red');
                    setTimeout(() => {
                        resetStatus();
                        isProcessing = false;
                    }, 3000);
                }
            })
            .catch(error => {
                showStatus('{{ __("Error") }}', '{{ __("System connectivity issue.") }}', 'red');
                setTimeout(() => {
                    resetStatus();
                    isProcessing = false;
                }, 3000);
            });
        }

        function showStatus(title, message, color) {
            const container = document.getElementById('status-container');
            const icon = document.getElementById('status-icon');
            const titleEl = document.getElementById('status-title');
            const messageEl = document.getElementById('status-message');

            container.classList.remove('hidden', 'opacity-0', 'scale-95');
            container.classList.add('opacity-100', 'scale-100');

            if (color === 'green') {
                container.querySelector('.rounded-2xl').className = 'p-6 rounded-2xl flex items-center space-x-4 bg-green-50 border border-green-200 shadow-sm';
                icon.className = 'p-3 rounded-full flex-shrink-0 bg-green-100 text-green-600';
                icon.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            } else if (color === 'red') {
                container.querySelector('.rounded-2xl').className = 'p-6 rounded-2xl flex items-center space-x-4 bg-red-50 border border-red-200 shadow-sm';
                icon.className = 'p-3 rounded-full flex-shrink-0 bg-red-100 text-red-600';
                icon.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
            } else {
                container.querySelector('.rounded-2xl').className = 'p-6 rounded-2xl flex items-center space-x-4 bg-blue-50 border border-blue-200 shadow-sm';
                icon.className = 'p-3 rounded-full flex-shrink-0 bg-blue-100 text-blue-600 animate-spin';
                icon.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
            }

            titleEl.innerText = title;
            messageEl.innerText = message;
        }

        function resetStatus() {
            const container = document.getElementById('status-container');
            container.classList.add('opacity-0', 'scale-95');
            setTimeout(() => container.classList.add('hidden'), 300);
        }

        function onScanFailure(error) {
            // silent failure (happens very often during scanning)
        }

        const formatsToSupport = [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.ITF
        ];

        html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 30, 
            qrbox: (viewfinderWidth, viewfinderHeight) => {
                // Adaptive box size - Wider for 1D barcodes
                const width = Math.floor(viewfinderWidth * 0.90);
                const height = Math.floor(viewfinderHeight * 0.50); // Shorter height for 1D
                return { width: width, height: height };
            },
            // aspectRatio is removed to use the native camera resolution
            showTorchButtonIfSupported: true,
            rememberLastUsedCamera: true,
            formatsToSupport: formatsToSupport,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            },
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                Html5QrcodeScanType.SCAN_TYPE_FILE
            ]
        });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>

    <style>
        #reader {
            border: none !important;
        }
        #reader__dashboard_section_csr button {
            background-color: #1f2937 !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            border: none !important;
            cursor: pointer !important;
            margin-top: 10px !important;
        }
    </style>
</x-app-layout>
