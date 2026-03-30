<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Use Stock via Barcode') }}
            </h2>
            <a href="{{ route('usage.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-medium flex items-center transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Usage History
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen transition-all duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left Section: Scanner & Manual Entry (8/12 - 2/3 Width) -->
                <div class="lg:col-span-8">
                    <!-- Twin-Column Card Grid for Input Methods -->
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

                                <div id="manual_product_info" class="hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Cart & Customer Info (4/12 width) -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-8 shadow-2xl sticky top-8 transition-all duration-300">
                        <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-800 pb-6">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center">
                                <svg class="h-6 w-6 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Items Cart
                            </h3>
                            <span id="cart-count" class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">0 Items</span>
                        </div>

                        <!-- Cart Items List -->
                        <div id="cart-container" class="space-y-3 mb-8 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            <div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl">
                                <p class="text-sm font-bold text-gray-400">Cart is empty. Scan something!</p>
                            </div>
                        </div>

                        <!-- Customer Details Form -->
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Company Name</label>
                                    <input type="text" id="company_name" class="w-full bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl py-2 px-3 font-bold focus:border-blue-500 transition-all text-sm text-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Customer Name *</label>
                                    <input type="text" id="customer_name" class="w-full bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl py-2 px-3 font-bold focus:border-blue-500 transition-all text-sm text-gray-900 dark:text-white">
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Phone Number *</label>
                                        <input type="text" id="phone" class="w-full bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl py-2 px-3 font-bold focus:border-blue-500 transition-all text-sm text-gray-900 dark:text-white">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Shipping Address *</label>
                                            <textarea id="address" rows="2" class="w-full bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl py-2 px-3 font-bold focus:border-blue-500 transition-all text-sm text-gray-900 dark:text-white"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Discount (%)</label>
                                            <div class="relative">
                                                <input type="number" id="discount_input" step="0.5" min="0" max="100" class="w-full bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl py-2 px-3 font-bold focus:border-red-500 transition-all text-sm text-gray-900 dark:text-white" placeholder="0.00" oninput="updateCartUI()">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Selection -->
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center justify-center p-2 rounded-xl border border-gray-100 dark:border-gray-800 cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:text-white transition-all shadow-sm">
                                    <input type="radio" name="payment_method" value="cash" class="sr-only" checked>
                                    <span class="font-black text-[10px] uppercase tracking-widest">Cash</span>
                                </label>
                                <label class="relative flex items-center justify-center p-2 rounded-xl border border-gray-100 dark:border-gray-800 cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:text-white transition-all shadow-sm">
                                    <input type="radio" name="payment_method" value="online" class="sr-only">
                                    <span class="font-black text-[10px] uppercase tracking-widest">Online</span>
                                </label>
                            </div>

                            <button onclick="submitBatchUsage()" id="submit-btn" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-black uppercase tracking-widest text-xs rounded-xl transition-all shadow-xl hover:shadow-green-500/40 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Confirm & Generate Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Overlay -->
            <div id="status-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 max-w-lg w-full text-center shadow-2xl">
                    <div id="status-icon" class="w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center"></div>
                    <h2 id="status-title" class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tight"></h2>
                    <p id="status-message" class="text-gray-600 dark:text-gray-400 font-bold mb-8"></p>
                    <div id="status-footer" class="hidden space-y-3">
                        <a href="{{ route('usage.index') }}" class="inline-block bg-gray-900 dark:bg-white dark:text-black text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl">Go to History</a>
                        <div id="whatsapp-resend-container" class="hidden">
                            <button onclick="resendWhatsApp()" id="resend-whatsapp-btn" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.613.613l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.353 0-4.556-.682-6.426-1.855l-.352-.215-3.65 1.224 1.224-3.65-.215-.352A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                                Resend on WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Toast Notification -->
            <div id="whatsapp-toast" class="fixed bottom-6 right-6 z-[60] hidden transform transition-all duration-500 translate-y-4 opacity-0">
                <div id="whatsapp-toast-card" class="flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border max-w-md">
                    <div id="whatsapp-toast-icon" class="shrink-0"></div>
                    <div class="min-w-0">
                        <p id="whatsapp-toast-title" class="font-black text-sm"></p>
                        <p id="whatsapp-toast-message" class="text-xs font-medium opacity-80 truncate"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let cart = [];
        let tempItem = null;
        let html5QrcodeScanner;
        let isProcessing = false;
        let lastScannedCode = null;
        let lastScanTime = 0;
        const SCAN_DEBOUNCE_MS = 500;
        const lastInvoiceId = null;
        const taxPercentage = {{ Auth::user()->getTaxPercentage() }};
        const currencySymbol = "{{ Auth::user()->currency === 'INR' ? '₹' : (Auth::user()->currency === 'USD' ? '$' : Auth::user()->currency) }}";

        // Phone validation regex: supports +CountryCode followed by digits
        const PHONE_REGEX = /^\+?\d{10,15}$/;

        function validatePhone(phone) {
            if (!phone) return { valid: false, message: 'Phone number is required.' };
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            if (!PHONE_REGEX.test(cleaned)) {
                return { valid: false, message: 'Enter valid phone with country code (e.g. +91XXXXXXXXXX).' };
            }
            return { valid: true };
        }

        // Initialize Scanner
        function initScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                fps: 20, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
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

        function onScanFailure(error) {
            // Silently ignore
        }

        function fetchBarcodeDetails(barcode, type) {
            if (!barcode || isProcessing) return;
            
            // Prevent adding the same record twice
            if (cart.some(item => item.barcode === barcode)) {
                showStatus('Already in Cart', 'This item is already added to your cart.', 'error');
                if (type === 'manual') document.getElementById('manual_barcode_value').value = '';
                return;
            }

            const prefix = type === 'scan' ? 'scan_' : 'manual_';
            const containerId = type === 'scan' ? 'scan-result-container' : 'manual_product_info';
            
            fetch(`/barcode/${barcode}/details`, {
                headers: { "Accept": "application/json" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    tempItem = {
                        barcode: barcode,
                        name: data.data.name,
                        unit_price: data.data.price
                    };
                    
                    // Automatically add to cart!
                    addToCart(type);
                    
                    // Brief success feedback
                    const reader = document.getElementById('reader');
                    if (type === 'scan') {
                        reader.classList.add('ring-8', 'ring-green-400', 'ring-inset');
                        setTimeout(() => reader.classList.remove('ring-8', 'ring-green-400', 'ring-inset'), 800);
                    }
                } else {
                    showStatus('Product Not Found', data.message || 'This barcode is not in our inventory.', 'error');
                }
            })
            .catch(err => {
                showStatus('Error', 'Failed to verify barcode.', 'error');
            });
        }

        function addToCart(type) {
            if (!tempItem) return;
            
            cart.push({...tempItem});
            
            // Visual feedback
            const containerId = type === 'scan' ? 'scan-result-container' : 'manual_product_info';
            document.getElementById(containerId).classList.add('hidden');
            
            if (type === 'manual') {
                document.getElementById('manual_barcode_value').value = '';
            }
            
            tempItem = null;
            updateCartUI();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        function updateCartUI() {
            const container = document.getElementById('cart-container');
            const countEl = document.getElementById('cart-count');
            const submitBtn = document.getElementById('submit-btn');
            
            countEl.innerText = `${cart.length} ${cart.length === 1 ? 'Item' : 'Items'}`;
            submitBtn.disabled = cart.length === 0;

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl">
                        <p class="text-sm font-bold text-gray-400">Cart is empty. Scan something!</p>
                    </div>
                `;
                return;
            }

            let html = '';
            let subtotal = 0;

            cart.forEach((item, index) => {
                subtotal += parseFloat(item.unit_price);
                html += `
                    <div class="group flex items-center justify-between p-3 bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl hover:border-blue-200 dark:hover:border-blue-800 transition-all animate-fade-in">
                        <div class="min-w-0 pr-2">
                            <h4 class="font-black text-[12px] text-gray-900 dark:text-white truncate">${item.name}</h4>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">${item.barcode}</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-[11px] font-black text-blue-600 mr-2">${currencySymbol}${parseFloat(item.unit_price).toFixed(2)}</span>
                            <button onclick="removeFromCart(${index})" class="p-1.5 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            // Calculate Discount
            const discountInput = document.getElementById('discount_input');
            const discountPercent = parseFloat(discountInput.value) || 0;
            const discountAmount = (subtotal * discountPercent) / 100;
            const discountedSubtotal = subtotal - discountAmount;
            
            // Calculate Tax
            const taxAmount = (discountedSubtotal * taxPercentage) / 100;
            const finalTotal = discountedSubtotal + taxAmount;

            // Add Total Summary
            html += `
                <div class="mt-4 pt-4 border-t border-dashed border-gray-100 dark:border-gray-800 space-y-2">
                    <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Items Subtotal</span>
                        <span>${currencySymbol}${subtotal.toFixed(2)}</span>
                    </div>
                    ${discountPercent > 0 ? `
                    <div class="flex justify-between items-center text-[10px] font-bold text-red-500 uppercase tracking-widest">
                        <span>Discount (${discountPercent}%)</span>
                        <span>-${currencySymbol}${discountAmount.toFixed(2)}</span>
                    </div>
                    ` : ''}
                    <div class="flex justify-between items-center text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-t border-gray-50 dark:border-gray-800/50 pt-1">
                        <span>Net Total</span>
                        <span>${currencySymbol}${discountedSubtotal.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Tax (${taxPercentage}%)</span>
                        <span>${currencySymbol}${taxAmount.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Total Bill</span>
                        <span class="text-xl font-black text-blue-600">${currencySymbol}${finalTotal.toFixed(2)}</span>
                    </div>
                </div>
            `;

            container.innerHTML = html;
        }

        function submitBatchUsage() {
            if (cart.length === 0 || isProcessing) return;

            const cName = document.getElementById('customer_name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;

            if (!cName || !phone || !address) {
                alert('Please fill in customer details (Name, Phone, Address)');
                return;
            }

            // Validate phone number format
            const phoneCheck = validatePhone(phone);
            if (!phoneCheck.valid) {
                alert(phoneCheck.message);
                document.getElementById('phone').focus();
                return;
            }

            const payload = {
                items: cart,
                customer_name: cName,
                phone: phone,
                address: address,
                discount: document.getElementById('discount_input').value || 0,
                company_name: document.getElementById('company_name').value || '',
                payment_method: document.querySelector('input[name="payment_method"]:checked').value
            };

            isProcessing = true;
            showStatus('Processing...', 'Generating invoice and updating stock...', 'loading');

            fetch('/usage/barcode/multi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                if (!res.ok) {
                    const errorText = await res.text();
                    let errorMessage = `Status ${res.status}`;
                    try {
                        const errorJson = JSON.parse(errorText);
                        errorMessage = errorJson.message || errorMessage;
                    } catch (e) {
                        // Not JSON, use truncated text
                        errorMessage += ': ' + (errorText.substring(0, 50) || 'Unknown Error');
                    }
                    throw new Error(errorMessage);
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Clear cart upon success to prevent duplicate submissions
                    cart = [];
                    updateCartUI();
                    
                    showStatus('Success!', data.message || 'Invoice generated successfully.', 'success');
                    
                    // Add a download button to the success overlay
                    if (data.data && data.data.invoice_id) {
                        const statusOverlay = document.getElementById('status-overlay');
                        const downloadBtn = document.createElement('a');
                        downloadBtn.href = `/invoice/${data.data.invoice_id}/download`;
                        downloadBtn.className = "mt-4 inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg";
                        downloadBtn.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Invoice
                        `;
                        const statusContent = statusOverlay.querySelector('.bg-white');
                        if (statusContent) statusContent.appendChild(downloadBtn);
                    }
                    
                    // Trigger WhatsApp send if phone provided and invoice_id exists
                    if (data.data?.invoice_id && phone && phone !== 'N/A') {
                        sendWhatsAppInvoice(data.data.invoice_id);
                    }
                } else {
                    showStatus('Failed', data.message || 'Could not process items.', 'error');
                    isProcessing = false;
                }
            })
            .catch(err => {
                showStatus('Error', 'Connection failed.', 'error');
                isProcessing = false;
            });
        }

        /**
         * Send invoice to customer via WhatsApp
         */
        function sendWhatsAppInvoice(invoiceId) {
            showWhatsAppToast('Sending...', 'Sending invoice to WhatsApp...', 'loading');

            fetch(`/invoice/${invoiceId}/whatsapp/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showWhatsAppToast('Sent!', 'Invoice sent successfully on WhatsApp', 'success');
                    document.getElementById('whatsapp-resend-container').classList.add('hidden');
                } else {
                    showWhatsAppToast('Failed', data.message || 'WhatsApp send failed', 'error');
                    // Show resend button in overlay
                    document.getElementById('whatsapp-resend-container').classList.remove('hidden');
                }
            })
            .catch(err => {
                showWhatsAppToast('Error', 'Could not send WhatsApp message', 'error');
                document.getElementById('whatsapp-resend-container').classList.remove('hidden');
            });
        }

        /**
         * Resend WhatsApp invoice (retry)
         */
        function resendWhatsApp() {
            if (!lastInvoiceId) return;

            const btn = document.getElementById('resend-whatsapp-btn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Sending...';

            fetch(`/invoice/${lastInvoiceId}/whatsapp/resend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showWhatsAppToast('Sent!', 'Invoice sent successfully on WhatsApp', 'success');
                    document.getElementById('whatsapp-resend-container').classList.add('hidden');
                } else {
                    showWhatsAppToast('Failed', data.message || 'Retry failed', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.613.613l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.353 0-4.556-.682-6.426-1.855l-.352-.215-3.65 1.224 1.224-3.65-.215-.352A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg> Resend on WhatsApp';
                }
            })
            .catch(err => {
                showWhatsAppToast('Error', 'Connection failed', 'error');
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.613.613l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.353 0-4.556-.682-6.426-1.855l-.352-.215-3.65 1.224 1.224-3.65-.215-.352A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg> Resend on WhatsApp';
            });
        }

        /**
         * Show WhatsApp toast notification
         */
        function showWhatsAppToast(title, message, type) {
            const toast = document.getElementById('whatsapp-toast');
            const card = document.getElementById('whatsapp-toast-card');
            const icon = document.getElementById('whatsapp-toast-icon');
            const titleEl = document.getElementById('whatsapp-toast-title');
            const msgEl = document.getElementById('whatsapp-toast-message');

            titleEl.innerText = title;
            msgEl.innerText = message;

            if (type === 'loading') {
                card.className = 'flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border bg-blue-50 dark:bg-blue-900/40 border-blue-200 dark:border-blue-700 max-w-md';
                icon.innerHTML = '<svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
                titleEl.className = 'font-black text-sm text-blue-800 dark:text-blue-200';
                msgEl.className = 'text-xs font-medium opacity-80 truncate text-blue-600 dark:text-blue-300';
            } else if (type === 'success') {
                card.className = 'flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border bg-green-50 dark:bg-green-900/40 border-green-200 dark:border-green-700 max-w-md';
                icon.innerHTML = '<svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.613.613l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.353 0-4.556-.682-6.426-1.855l-.352-.215-3.65 1.224 1.224-3.65-.215-.352A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>';
                titleEl.className = 'font-black text-sm text-green-800 dark:text-green-200';
                msgEl.className = 'text-xs font-medium opacity-80 truncate text-green-600 dark:text-green-300';
            } else {
                card.className = 'flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border bg-red-50 dark:bg-red-900/40 border-red-200 dark:border-red-700 max-w-md';
                icon.innerHTML = '<svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                titleEl.className = 'font-black text-sm text-red-800 dark:text-red-200';
                msgEl.className = 'text-xs font-medium opacity-80 truncate text-red-600 dark:text-red-300';
            }

            // Show toast with animation
            toast.classList.remove('hidden');
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            // Auto-hide after 5 seconds (except loading)
            if (type !== 'loading') {
                setTimeout(() => {
                    toast.classList.add('translate-y-4', 'opacity-0');
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    setTimeout(() => toast.classList.add('hidden'), 500);
                }, 5000);
            }
        }

        function showStatus(title, message, type) {
            const overlay = document.getElementById('status-overlay');
            const titleEl = document.getElementById('status-title');
            const msgEl = document.getElementById('status-message');
            const iconEl = document.getElementById('status-icon');
            const footer = document.getElementById('status-footer');

            titleEl.innerText = title;
            msgEl.innerText = message;
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

        // Handle enter key for manual input
        document.getElementById('manual_barcode_value').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                fetchBarcodeDetails(this.value, 'manual');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', initScanner);
    </script>

    <style>
        #reader { border: none !important; }
        #reader__dashboard_section_csr button {
            background: #2563eb !important;
            color: white !important;
            border: none !important;
            padding: 10px 20px !important;
            border-radius: 12px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 10px !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>
