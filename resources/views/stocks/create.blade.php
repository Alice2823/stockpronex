<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Add New Stock') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 dark:border-gray-800">
                <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <form method="POST" action="{{ route('stocks.store') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                                required autofocus />
                        </div>

                        <!-- Quantity -->
                        <div class="mt-4">
                            <x-label for="quantity" :value="__('Quantity')" />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                :value="old('quantity', 0)" required />
                        </div>

                        <!-- MRP -->
                        <div class="mt-4">
                            <x-label for="mrp" :value="__('MRP (₹)')" />
                            <x-input id="mrp" class="block mt-1 w-full" type="number" step="0.01" name="mrp"
                                :value="old('mrp', '')" />
                        </div>

                        <!-- Selling Price -->
                        <div class="mt-4">
                            <x-label for="price" :value="__('Selling Price (₹)')" />
                            <x-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price"
                                :value="old('price', 0.00)" required />
                        </div>

                        <!-- Business-Specific Fields -->
                        @php
                            $businessType = Auth::user()->business_type;
                        @endphp

                        <div id="business_fields" class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 flex items-center uppercase tracking-wider">
                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('Business Specific Details') }} ({{ $businessType ?? __('Default') }})
                            </h3>

                            @if($businessType == 'Gold / Jewellery')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="weight" :value="__('Weight (Grams)')" />
                                        <x-input id="weight" class="block mt-1 w-full gold-calc" type="number" step="0.001" name="business_attributes[weight]" :value="old('business_attributes.weight')" />
                                    </div>
                                    <div>
                                        <x-label for="purity" :value="__('Purity')" />
                                        <x-select id="purity" name="business_attributes[purity]" class="mt-1 block w-full">
                                            <option value="22k">22k</option>
                                            <option value="24k">24k</option>
                                            <option value="18k">18k</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="rate_per_gram" :value="__('Rate per Gram (₹)')" />
                                        <x-input id="rate_per_gram" class="block mt-1 w-full gold-calc" type="number" step="0.01" name="business_attributes[rate_per_gram]" :value="old('business_attributes.rate_per_gram')" />
                                    </div>
                                    <div>
                                        <x-label for="making_charges" :value="__('Making Charges (₹)')" />
                                        <x-input id="making_charges" class="block mt-1 w-full gold-calc" type="number" step="0.01" name="business_attributes[making_charges]" :value="old('business_attributes.making_charges')" />
                                    </div>
                                </div>
                                <p class="text-xs text-indigo-600 mt-2 italic">* {{ __('Total Price will be auto-calculated: (Weight × Rate) + Making Charges') }}</p>
                            @elseif($businessType == 'Grocery')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="unit" :value="__('Unit')" />
                                        <x-select id="unit" name="business_attributes[unit]" class="mt-1 block w-full">
                                            <option value="kg">kg</option>
                                            <option value="litre">litre</option>
                                            <option value="packet">packet</option>
                                            <option value="unit">unit</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Electronics' || $businessType == 'Mobile Shop')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand')" />
                                    </div>
                                    <div>
                                        <x-label for="model_number" :value="__('Model Number')" />
                                        <x-input id="model_number" class="block mt-1 w-full" type="text" name="business_attributes[model_number]" :value="old('business_attributes.model_number')" />
                                    </div>
                                    <div>
                                        <x-label for="warranty" :value="__('Warranty (Months)')" />
                                        <x-input id="warranty" class="block mt-1 w-full" type="number" name="business_attributes[warranty]" :value="old('business_attributes.warranty')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Clothing')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="size" :value="__('Size')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size')" :placeholder="__('e.g. XL, 42, 32')" />
                                    </div>
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color')" />
                                    </div>
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Medical Store')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-label for="batch_number" :value="__('Batch Number')" />
                                        <x-input id="batch_number" class="block mt-1 w-full" type="text" name="business_attributes[batch_number]" :value="old('business_attributes.batch_number')" />
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date')" />
                                    </div>
                                    <div>
                                        <x-label for="mrp" :value="__('MRP (₹)')" />
                                        <x-input id="mrp" class="block mt-1 w-full" type="number" step="0.01" name="business_attributes[mrp]" :value="old('business_attributes.mrp')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Hardware')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand')" />
                                    </div>
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material')" />
                                    </div>
                                    <div>
                                        <x-label for="size" :value="__('Size/Dimensions')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Automobile parts')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="part_number" :value="__('Part Number')" />
                                        <x-input id="part_number" class="block mt-1 w-full" type="text" name="business_attributes[part_number]" :value="old('business_attributes.part_number')" />
                                    </div>
                                    <div>
                                        <x-label for="compatibility" :value="__('Vehicle Compatibility')" />
                                        <x-input id="compatibility" class="block mt-1 w-full" type="text" name="business_attributes[compatibility]" :value="old('business_attributes.compatibility')" :placeholder="__('e.g. Toyota Corolla 2020')" />
                                    </div>
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Furniture')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material')" :placeholder="__('e.g. Teak Wood, Steel')" />
                                    </div>
                                    <div>
                                        <x-label for="dimensions" :value="__('Dimensions')" />
                                        <x-input id="dimensions" class="block mt-1 w-full" type="text" name="business_attributes[dimensions]" :value="old('business_attributes.dimensions')" :placeholder="__('e.g. 6x4 ft')" />
                                    </div>
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Cosmetic')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand')" />
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date')" />
                                    </div>
                                    <div>
                                        <x-label for="volume" :value="__('Volume/Weight')" />
                                        <x-input id="volume" class="block mt-1 w-full" type="text" name="business_attributes[volume]" :value="old('business_attributes.volume')" :placeholder="__('e.g. 100ml, 50g')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Book Store')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="isbn" :value="__('ISBN Number')" />
                                        <x-input id="isbn" class="block mt-1 w-full" type="text" name="business_attributes[isbn]" :value="old('business_attributes.isbn')" />
                                    </div>
                                    <div>
                                        <x-label for="author" :value="__('Author')" />
                                        <x-input id="author" class="block mt-1 w-full" type="text" name="business_attributes[author]" :value="old('business_attributes.author')" />
                                    </div>
                                    <div>
                                        <x-label for="publisher" :value="__('Publisher')" />
                                        <x-input id="publisher" class="block mt-1 w-full" type="text" name="business_attributes[publisher]" :value="old('business_attributes.publisher')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Restaurant')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="category" :value="__('Category')" />
                                        <x-select id="category" name="business_attributes[category]" class="mt-1 block w-full">
                                            <option value="Veg">Veg</option>
                                            <option value="Non-Veg">Non-Veg</option>
                                            <option value="Beverage">Beverage</option>
                                            <option value="Other">Other</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date/Best Before')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date')" />
                                    </div>
                                    <div>
                                        <x-label for="batch" :value="__('Batch Number')" />
                                        <x-input id="batch" class="block mt-1 w-full" type="text" name="business_attributes[batch]" :value="old('business_attributes.batch')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Agricultural')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="variety" :value="__('Variety/Grade')" />
                                        <x-input id="variety" class="block mt-1 w-full" type="text" name="business_attributes[variety]" :value="old('business_attributes.variety')" :placeholder="__('e.g. Grade A, Basmati')" />
                                    </div>
                                    <div>
                                        <x-label for="harvest_date" :value="__('Harvest Date')" />
                                        <x-input id="harvest_date" class="block mt-1 w-full" type="date" name="business_attributes[harvest_date]" :value="old('business_attributes.harvest_date')" />
                                    </div>
                                    <div>
                                        <x-label for="origin" :value="__('Origin')" />
                                        <x-input id="origin" class="block mt-1 w-full" type="text" name="business_attributes[origin]" :value="old('business_attributes.origin')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Wholesale')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="pack_size" :value="__('Pack Size')" />
                                        <x-input id="pack_size" class="block mt-1 w-full" type="text" name="business_attributes[pack_size]" :value="old('business_attributes.pack_size')" :placeholder="__('e.g. Case of 24, Bulk 50kg')" />
                                    </div>
                                    <div>
                                        <x-label for="min_order" :value="__('Min Order Qty')" />
                                        <x-input id="min_order" class="block mt-1 w-full" type="number" name="business_attributes[min_order]" :value="old('business_attributes.min_order')" />
                                    </div>
                                    <div>
                                        <x-label for="manufacturer" :value="__('Manufacturer')" />
                                        <x-input id="manufacturer" class="block mt-1 w-full" type="text" name="business_attributes[manufacturer]" :value="old('business_attributes.manufacturer')" />
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">{{ __('No additional specialized fields for your business type.') }}</p>
                            @endif
                        </div>

                        <!-- Unit Tracking Section -->
                        <div id="unit_tracking_section" class="mt-6 p-4 bg-white dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-100 dark:border-gray-800 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-md font-bold text-gray-800 dark:text-white flex items-center uppercase tracking-tight">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    {{ __('Individual Unit Tracking') }}
                                </h3>
                                <div class="flex items-center bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full">
                                    <input type="checkbox" id="enable_tracking" name="enable_tracking" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800" checked>
                                    <label for="enable_tracking" class="ml-2 text-xs font-bold text-indigo-700 dark:text-indigo-400">{{ __('Track Unit Details') }}</label>
                                </div>
                            </div>

                            <div id="tracking_container" class="space-y-2">
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic text-center py-6 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-inner">
                                    {{ __('Enter a quantity above to start tracking individual units.') }}
                                </p>
                            </div>

                            <!-- Integrated Scanner for Unit Entry -->
                            <div id="unit_scanner_container" class="mt-4 hidden">
                                <div class="p-4 bg-gray-900 rounded-lg shadow-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ __('Live Scanner') }}</span>
                                        <button type="button" id="stop_unit_scanner" class="text-gray-400 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    <div id="unit-reader" style="width: 100%;" class="rounded overflow-hidden"></div>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-center">
                                <button type="button" id="start_unit_scanner" class="inline-flex items-center px-4 py-2 border border-indigo-600 dark:border-indigo-500 text-sm font-bold rounded-xl text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-900 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all shadow-sm active:scale-95">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M16 12v4m1 4V4a2 2 0 00-2-2H6a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2zM9 16H9m0 0H9m0 0H9"></path></svg>
                                    {{ __('Bulk Scan Boxes') }}
                                </button>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-label for="description" :value="__('Description (Optional)')" />
                            <x-textarea id="description" name="description" rows="3" class="mt-1 block w-full">{{ old('description') }}</x-textarea>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 font-bold hover:text-gray-900 dark:hover:text-white mr-6 transition-colors transition-all">{{ __('Cancel') }}</a>
                            <x-button id="submitBtn">
                                {{ __('Add Stock') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($businessType == 'Gold / Jewellery')
    <script>
        document.querySelectorAll('.gold-calc').forEach(input => {
            input.addEventListener('input', function() {
                const weight = parseFloat(document.getElementById('weight').value) || 0;
                const rate = parseFloat(document.getElementById('rate_per_gram').value) || 0;
                const making = parseFloat(document.getElementById('making_charges').value) || 0;
                
                const total = (weight * rate) + making;
                document.getElementById('price').value = total.toFixed(2);
            });
        });
    </script>
    @endif

    <!-- Unit Tracking Logic -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const trackingContainer = document.getElementById('tracking_container');
        const quantityInput = document.getElementById('quantity');
        const enableTrackingItem = document.getElementById('enable_tracking');
        const startScannerBtn = document.getElementById('start_unit_scanner');
        const stopScannerBtn = document.getElementById('stop_unit_scanner');
        const scannerContainer = document.getElementById('unit_scanner_container');
        const businessType = "{{ Auth::user()->business_type }}";

        let html5QrCode = null;

        function generateUnitRows() {
            const qty = parseInt(quantityInput.value) || 0;
            const isEnabled = enableTrackingItem.checked;

            if (!isEnabled || qty <= 0) {
                trackingContainer.innerHTML = `<p class="text-sm text-gray-400 dark:text-gray-500 italic text-center py-4 bg-gray-50 dark:bg-gray-800/50 rounded border border-gray-100 dark:border-gray-700">
                    ${isEnabled ? '{{ __("Enter a quantity above to start tracking individual units.") }}' : '{{ __("Per-unit tracking is disabled.") }}'}
                </p>`;
                return;
            }

            let html = '';
            for (let i = 1; i <= qty; i++) {
                html += `
                    <div class="unit-row p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-4 gap-4 items-end transition-all hover:border-blue-300 dark:hover:border-blue-500/50" data-unit="${i}">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1 tracking-wider">Unit #${i} Barcode</label>
                            <div class="flex shadow-sm">
                                <input type="text" name="units[${i}][barcode]" id="unit_barcode_${i}" class="unit-barcode block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-l-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="{{ __('Scan or Enter Barcode') }}">
                                <button type="button" onclick="generateUnitBarcode(${i})" class="inline-flex items-center px-4 py-2 border border-l-0 border-blue-600 dark:border-blue-500 text-[10px] font-bold rounded-r-xl text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 transition-all active:scale-95">
                                    {{ __('Generate') }}
                                </button>
                            </div>
                        </div>
                        ${generateBusinessFields(i)}
                    </div>
                `;
            }
            trackingContainer.innerHTML = html;
        }

        function generateUnitBarcode(index) {
            const now = new Date();
            const dateStr = now.getFullYear() + 
                          String(now.getMonth() + 1).padStart(2, '0') + 
                          String(now.getDate()).padStart(2, '0');
            const randomStr = Math.random().toString(36).substring(2, 6).toUpperCase();
            const barcode = `STK-${dateStr}-${randomStr}`;
            
            const input = document.getElementById(`unit_barcode_${index}`);
            if (input) {
                input.value = barcode;
                // Add highlight effect
                const row = input.closest('.unit-row');
                row.classList.add('ring-2', 'ring-indigo-400');
                setTimeout(() => row.classList.remove('ring-2', 'ring-indigo-400'), 1000);
            }
        }

        function generateBusinessFields(index) {
            if (businessType === 'Mobile Shop') {
                return `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">IMEI Number</label>
                        <input type="text" name="units[${index}][imei_number]" class="unit-imei block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="IMEI">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Serial Number</label>
                        <input type="text" name="units[${index}][serial_number]" class="unit-serial block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Serial No.">
                    </div>
                `;
            } else if (businessType === 'Electronics') {
                return `
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Serial Number</label>
                        <input type="text" name="units[${index}][serial_number]" class="unit-serial block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Serial No.">
                    </div>
                `;
            } else if (businessType === 'Medical Store') {
                return `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Batch Number</label>
                        <input type="text" name="units[${index}][batch_number]" class="unit-batch block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Batch No.">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Expiry Date</label>
                        <input type="date" name="units[${index}][expiry_date]" class="unit-expiry block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300">
                    </div>
                `;
            } else if (businessType === 'Gold / Jewellery') {
                return `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Exact Weight</label>
                        <input type="number" step="0.001" name="units[${index}][weight]" class="unit-weight block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="0.000">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Hallmark/ID</label>
                        <input type="text" name="units[${index}][hallmark]" class="unit-hallmark block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Hallmark ID">
                    </div>
                `;
            }
            return '<div class="col-span-2 text-xs text-gray-500 dark:text-gray-500 italic font-medium">' + "{{ __('No additional unit fields required.') }}" + '</div>';
        }

        // Scanner Logic
        async function startScanner() {
            const qty = parseInt(quantityInput.value) || 0;
            if (qty <= 0) {
                alert('Please enter quantity first.');
                return;
            }

            scannerContainer.classList.remove('hidden');
            html5QrCode = new Html5Qrcode("unit-reader");
            
            const config = { fps: 10, qrbox: { width: 250, height: 150 } };

            try {
                await html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                    fillNextEmptyRow(decodedText);
                    // Visual feedback
                    document.getElementById('unit-reader').style.border = "5px solid #10b981";
                    setTimeout(() => {
                        document.getElementById('unit-reader').style.border = "none";
                    }, 500);
                });
            } catch (err) {
                console.error(err);
                alert("Camera access failed.");
                scannerContainer.classList.add('hidden');
            }
        }

        function fillNextEmptyRow(barcode) {
            const barcodeInputs = document.querySelectorAll('.unit-barcode');
            for (let input of barcodeInputs) {
                if (!input.value) {
                    input.value = barcode;
                    input.focus();
                    
                    // Highlight row
                    const row = input.closest('.unit-row');
                    row.classList.remove('bg-gray-50', 'dark:bg-gray-800/40');
                    row.classList.add('bg-green-50', 'dark:bg-green-900/30', 'border-green-200', 'dark:border-green-700');
                    setTimeout(() => {
                        row.classList.remove('bg-green-50', 'dark:bg-green-900/30', 'border-green-200', 'dark:border-green-700');
                        row.classList.add('bg-gray-50', 'dark:bg-gray-800/40');
                    }, 1000);
                    
                    break;
                }
            }
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    scannerContainer.classList.add('hidden');
                }).catch(err => console.error(err));
            } else {
                scannerContainer.classList.add('hidden');
            }
        }

        // Events
        quantityInput.addEventListener('input', generateUnitRows);
        enableTrackingItem.addEventListener('change', generateUnitRows);
        startScannerBtn.addEventListener('click', startScanner);
        stopScannerBtn.addEventListener('click', stopScanner);

        // Initialize
        generateUnitRows();
    </script>
</x-app-layout>