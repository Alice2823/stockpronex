<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Add Stock via Barcode') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-medium flex items-center transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen transition-all duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left Section: Scanner & Manual Entry -->
                <div class="lg:col-span-8">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                        <!-- Option 1: Scan Barcode -->
                        <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-8 shadow-xl flex flex-col h-full">
                            <div class="flex items-center mb-8">
                                <div class="p-4 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-5 shadow-sm">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Scan Barcode</h3>
                                    <p class="text-xs font-bold text-blue-500/70 dark:text-blue-400/70 uppercase tracking-widest">Live Camera Feed</p>
                                </div>
                            </div>

                            <div class="relative rounded-3xl overflow-hidden bg-gray-50 dark:bg-black/20 border-2 border-gray-100 dark:border-gray-800 shadow-inner">
                                <div id="reader" style="width: 100%; min-height: 300px;"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center">
                                    <span class="bg-black/60 text-white text-xs px-4 py-1.5 rounded-full font-bold backdrop-blur-md">
                                        Center the barcode
                                    </span>
                                </div>
                            </div>

                            <div id="scan-result-container" class="hidden"></div>
                        </div>

                        <!-- Option 2: Enter Manually -->
                        <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-8 shadow-xl flex flex-col h-full">
                            <div class="flex items-center mb-8">
                                <div class="p-4 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-5 shadow-sm">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Manual Entry</h3>
                                    <p class="text-xs font-bold text-purple-500/70 dark:text-purple-400/70 uppercase tracking-widest">Type Barcode</p>
                                </div>
                            </div>

                            <div class="space-y-4 grow">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 ml-1">Barcode / IMEI Number</label>
                                    <div class="flex gap-2">
                                        <input type="text" id="manual_barcode_value" class="grow bg-gray-50 dark:bg-black/20 border-2 border-gray-100 dark:border-gray-800 rounded-2xl py-3 px-4 text-lg font-black focus:border-purple-500 transition-all dark:text-white" placeholder="STK-000">
                                        <button onclick="fetchBarcodeDetails(document.getElementById('manual_barcode_value').value, 'manual')" class="bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-2xl shadow-lg">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-black/10 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800">
                                    <p class="text-[10px] text-gray-400 font-black uppercase mb-2 tracking-widest">New product?</p>
                                    <a href="{{ route('stocks.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center transition-colors">
                                        Go to Manual Stock Creation
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Replenishment Cart -->
                <div class="lg:col-span-4">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-8 shadow-2xl sticky top-8 transition-all duration-300">
                        <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-800 pb-6">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center">
                                <svg class="h-6 w-6 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Batch Replenish
                            </h3>
                            <span id="cart-count" class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">0 Items</span>
                        </div>

                        <!-- Cart Items -->
                        <div id="cart-container" class="space-y-4 mb-8 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl">
                                <p class="text-sm font-bold text-gray-400">Scan items to add to batch.</p>
                            </div>
                        </div>

                        <button onclick="submitBatchAdd()" id="submit-btn" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest text-xs rounded-xl transition-all shadow-xl hover:shadow-blue-500/40 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Add to Inventory
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Overlay -->
            <div id="status-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 max-w-lg w-full text-center shadow-2xl">
                    <div id="status-icon" class="w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center"></div>
                    <h2 id="status-title" class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tight"></h2>
                    <p id="status-message" class="text-gray-600 dark:text-gray-400 font-bold mb-8"></p>
                    <div id="status-footer" class="hidden">
                        <button onclick="location.reload()" class="inline-block bg-gray-900 dark:bg-white dark:text-black text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl">Scan More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let cart = [];
        let html5QrcodeScanner;
        let isProcessing = false;
        let lastScannedCode = null;
        let lastScanTime = 0;
        const SCAN_DEBOUNCE_MS = 1000;

        // Initialize Scanner
        function initScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                fps: 20, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                rememberLastUsedCamera: true
            });
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }

        function onScanSuccess(decodedText) {
            const now = Date.now();
            if (decodedText === lastScannedCode && (now - lastScanTime < SCAN_DEBOUNCE_MS)) return;
            
            lastScannedCode = decodedText;
            lastScanTime = now;
            
            // Visual feedback
            const reader = document.getElementById('reader');
            reader.classList.add('ring-4', 'ring-green-500', 'ring-inset');
            setTimeout(() => reader.classList.remove('ring-4', 'ring-green-500', 'ring-inset'), 500);

            fetchBarcodeDetails(decodedText, 'scan');
        }

        function onScanFailure(error) { /* silence */ }

        function fetchBarcodeDetails(barcode, type) {
            if (!barcode || isProcessing) return;
            
            fetch(`/stocks/barcode/${barcode}/details`, {
                headers: { "Accept": "application/json" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    addToCart(data.data, barcode);
                    if (type === 'manual') document.getElementById('manual_barcode_value').value = '';
                } else {
                    showStatus('Not Found', data.message, 'error');
                }
            })
            .catch(err => {
                showStatus('Error', 'Verification failed.', 'error');
            });
        }

        function addToCart(product, barcode) {
            const existing = cart.find(i => i.stock_id === product.stock_id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    stock_id: product.stock_id,
                    name: product.name,
                    quantity: 1,
                    current_qty: product.current_quantity,
                    barcode: barcode
                });
            }
            updateCartUI();
        }

        function updateCartUI() {
            const container = document.getElementById('cart-container');
            const countEl = document.getElementById('cart-count');
            const submitBtn = document.getElementById('submit-btn');
            
            countEl.innerText = `${cart.length} Items`;
            submitBtn.disabled = cart.length === 0;

            if (cart.length === 0) {
                container.innerHTML = `<div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl"><p class="text-sm font-bold text-gray-400">Scan items to add to batch.</p></div>`;
                return;
            }

            let html = '';
            cart.forEach((item, index) => {
                html += `
                    <div class="p-4 bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-2xl">
                        <div class="flex justify-between items-start mb-2">
                            <div class="pr-2 min-w-0">
                                <h4 class="font-black text-[12px] text-gray-900 dark:text-white truncate uppercase">${item.name}</h4>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">In Stock: ${item.current_qty}</p>
                            </div>
                            <button onclick="removeFromCart(${index})" class="text-gray-300 hover:text-red-500 transition-colors p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                        <div class="flex items-center bg-white dark:bg-gray-800 rounded-lg p-1 border border-gray-100 dark:border-gray-700">
                            <button onclick="updateQty(${index}, -1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">-</button>
                            <input type="number" value="${item.quantity}" onchange="cart[${index}].quantity = parseInt(this.value)" class="grow text-center text-sm font-black border-none bg-transparent dark:text-white focus:ring-0">
                            <button onclick="updateQty(${index}, 1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">+</button>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function updateQty(index, delta) {
            cart[index].quantity += delta;
            if (cart[index].quantity < 1) cart[index].quantity = 1;
            updateCartUI();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        function submitBatchAdd() {
            if (cart.length === 0 || isProcessing) return;
            isProcessing = true;
            showStatus('Processing...', 'Adding items to your inventory...', 'loading');

            fetch("{{ route('stocks.barcode.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ items: cart })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showStatus('Stock Updated!', 'Batch replenishment complete.', 'success');
                    setTimeout(() => window.location.href = "{{ route('dashboard') }}", 2000);
                } else {
                    showStatus('Failed', data.message, 'error');
                    isProcessing = false;
                }
            })
            .catch(err => {
                showStatus('Error', 'Connection failed.', 'error');
                isProcessing = false;
            });
        }

        function showStatus(title, message, type) {
            const overlay = document.getElementById('status-overlay');
            const iconEl = document.getElementById('status-icon');
            const footer = document.getElementById('status-footer');

            document.getElementById('status-title').innerText = title;
            document.getElementById('status-message').innerText = message;
            overlay.classList.remove('hidden');
            footer.classList.add('hidden');

            if (type === 'loading') {
                iconEl.className = "w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center bg-blue-100 text-blue-600 animate-spin";
                iconEl.innerHTML = '<svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
            } else if (type === 'success') {
                iconEl.className = "w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center bg-green-100 text-green-600";
                iconEl.innerHTML = '<svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                footer.classList.remove('hidden');
            } else {
                iconEl.className = "w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center bg-red-100 text-red-600";
                iconEl.innerHTML = '<svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                setTimeout(() => { if(!isProcessing) overlay.classList.add('hidden') }, 3000);
            }
        }

        document.getElementById('manual_barcode_value').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') fetchBarcodeDetails(e.target.value, 'manual');
        });

        document.addEventListener('DOMContentLoaded', initScanner);
    </script>

    <style>
        #reader { border: none !important; }
        #reader__dashboard_section_csr button {
            background: #2563eb !important; color: white !important; border: none !important;
            padding: 10px 20px !important; border-radius: 12px !important; font-weight: 800 !important;
            text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 10px !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>
</x-app-layout>
