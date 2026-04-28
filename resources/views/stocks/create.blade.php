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
                            $requiredFields = \App\Constants\BusinessIndustry::getRequiredFields($businessType);
                        @endphp

                        <div id="business_fields" class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 flex items-center uppercase tracking-wider">
                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('Business Specific Details') }} ({{ $businessType ? __($businessType) : __('Default') }})
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @if(in_array('brand', $requiredFields))
                                    <div>
                                        <x-label for="brand" :value="__('Brand / Manufacturer')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand')" />
                                    </div>
                                @endif

                                @if(in_array('model_number', $requiredFields))
                                    <div>
                                        <x-label for="model_number" :value="__('Model Number / Name')" />
                                        <x-input id="model_number" class="block mt-1 w-full" type="text" name="business_attributes[model_number]" :value="old('business_attributes.model_number')" />
                                    </div>
                                @endif

                                @if(in_array('serial_number', $requiredFields))
                                    <div>
                                        <x-label for="serial_number" :value="__('Serial Number')" />
                                        <x-input id="serial_number" class="block mt-1 w-full" type="text" name="business_attributes[serial_number]" :value="old('business_attributes.serial_number')" />
                                    </div>
                                @endif

                                @if(in_array('imei_number', $requiredFields))
                                    <div>
                                        <x-label for="imei_number" :value="__('IMEI / MAC Address')" />
                                        <x-input id="imei_number" class="block mt-1 w-full" type="text" name="business_attributes[imei_number]" :value="old('business_attributes.imei_number')" />
                                    </div>
                                @endif

                                @if(in_array('warranty', $requiredFields))
                                    <div>
                                        <x-label for="warranty" :value="__('Warranty (Months)')" />
                                        <x-input id="warranty" class="block mt-1 w-full" type="number" name="business_attributes[warranty]" :value="old('business_attributes.warranty')" />
                                    </div>
                                @endif

                                @if(in_array('part_number', $requiredFields))
                                    <div>
                                        <x-label for="part_number" :value="__('Part Number / SKU')" />
                                        <x-input id="part_number" class="block mt-1 w-full" type="text" name="business_attributes[part_number]" :value="old('business_attributes.part_number')" />
                                    </div>
                                @endif

                                @if(in_array('specification', $requiredFields))
                                    <div>
                                        <x-label for="specification" :value="__('Specifications (AH/Volt/Power)')" />
                                        <x-input id="specification" class="block mt-1 w-full" type="text" name="business_attributes[specification]" :value="old('business_attributes.specification')" />
                                    </div>
                                @endif

                                @if(in_array('certification', $requiredFields))
                                    <div>
                                        <x-label for="certification" :value="__('Certification (ISO/CE/etc)')" />
                                        <x-input id="certification" class="block mt-1 w-full" type="text" name="business_attributes[certification]" :value="old('business_attributes.certification')" />
                                    </div>
                                @endif

                                @if(in_array('batch_number', $requiredFields))
                                    <div>
                                        <x-label for="batch_number" :value="__('Batch Number')" />
                                        <x-input id="batch_number" class="block mt-1 w-full" type="text" name="business_attributes[batch_number]" :value="old('business_attributes.batch_number')" />
                                    </div>
                                @endif

                                @if(in_array('expiry_date', $requiredFields))
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date')" />
                                    </div>
                                @endif

                                @if(in_array('composition', $requiredFields))
                                    <div>
                                        <x-label for="composition" :value="__('Composition / Ingredients')" />
                                        <x-input id="composition" class="block mt-1 w-full" type="text" name="business_attributes[composition]" :value="old('business_attributes.composition')" />
                                    </div>
                                @endif

                                @if(in_array('volume', $requiredFields))
                                    <div>
                                        <x-label for="volume" :value="__('Volume / Quantity (ml/L/kg)')" />
                                        <x-input id="volume" class="block mt-1 w-full" type="text" name="business_attributes[volume]" :value="old('business_attributes.volume')" />
                                    </div>
                                @endif

                                @if(in_array('variety', $requiredFields))
                                    <div>
                                        <x-label for="variety" :value="__('Variety / Type')" />
                                        <x-input id="variety" class="block mt-1 w-full" type="text" name="business_attributes[variety]" :value="old('business_attributes.variety')" />
                                    </div>
                                @endif

                                @if(in_array('size', $requiredFields))
                                    <div>
                                        <x-label for="size" :value="__('Size')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size')" :placeholder="__('e.g. XL, 42, 32')" />
                                    </div>
                                @endif

                                @if(in_array('color', $requiredFields))
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color')" />
                                    </div>
                                @endif

                                @if(in_array('material', $requiredFields))
                                    <div>
                                        <x-label for="material" :value="__('Material Type')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material')" />
                                    </div>
                                @endif

                                @if(in_array('dimension', $requiredFields))
                                    <div>
                                        <x-label for="dimension" :value="__('Dimensions')" />
                                        <x-input id="dimension" class="block mt-1 w-full" type="text" name="business_attributes[dimension]" :value="old('business_attributes.dimension')" :placeholder="__('e.g. 10x20x30')" />
                                    </div>
                                @endif

                                @if(in_array('thickness', $requiredFields))
                                    <div>
                                        <x-label for="thickness" :value="__('Thickness (mm/in)')" />
                                        <x-input id="thickness" class="block mt-1 w-full" type="text" name="business_attributes[thickness]" :value="old('business_attributes.thickness')" />
                                    </div>
                                @endif

                                @if(in_array('grade', $requiredFields))
                                    <div>
                                        <x-label for="grade" :value="__('Grade / Quality')" />
                                        <x-input id="grade" class="block mt-1 w-full" type="text" name="business_attributes[grade]" :value="old('business_attributes.grade')" />
                                    </div>
                                @endif

                                @if(in_array('weight', $requiredFields))
                                    <div>
                                        <x-label for="weight" :value="__('Weight (g/kg)')" />
                                        <x-input id="weight" class="block mt-1 w-full" type="number" step="0.001" name="business_attributes[weight]" :value="old('business_attributes.weight')" />
                                    </div>
                                @endif

                                @if(in_array('purity', $requiredFields))
                                    <div>
                                        <x-label for="purity" :value="__('Purity (Karat/%)')" />
                                        <x-input id="purity" class="block mt-1 w-full" type="text" name="business_attributes[purity]" :value="old('business_attributes.purity')" />
                                    </div>
                                @endif

                                @if(in_array('hallmark', $requiredFields))
                                    <div>
                                        <x-label for="hallmark" :value="__('Hallmark ID')" />
                                        <x-input id="hallmark" class="block mt-1 w-full" type="text" name="business_attributes[hallmark]" :value="old('business_attributes.hallmark')" />
                                    </div>
                                @endif

                                @if(in_array('making_charges', $requiredFields))
                                    <div>
                                        <x-label for="making_charges" :value="__('Making Charges')" />
                                        <x-input id="making_charges" class="block mt-1 w-full" type="number" step="0.01" name="business_attributes[making_charges]" :value="old('business_attributes.making_charges')" />
                                    </div>
                                @endif
                            </div>

                            @if(empty($requiredFields))
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
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="autoGenerateAllBarcodes()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg transition-all active:scale-95">
                                        {{ __('Auto-Generate All') }}
                                    </button>
                                    <div class="flex items-center bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full">
                                        <input type="checkbox" id="enable_tracking" name="enable_tracking" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800" checked>
                                        <label for="enable_tracking" class="ml-2 text-xs font-bold text-indigo-700 dark:text-indigo-400">{{ __('Track Unit Details') }}</label>
                                    </div>
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

        const requiredFields = @json($requiredFields);
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
                row.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-50/50', 'dark:bg-indigo-900/20');
                setTimeout(() => row.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-50/50', 'dark:bg-indigo-900/20'), 1000);
            }
        }

        function autoGenerateAllBarcodes() {
            const qty = parseInt(quantityInput.value) || 0;
            const isEnabled = enableTrackingItem.checked;
            
            if (!isEnabled || qty <= 0) {
                alert('{{ __("Please enter quantity and enable tracking first.") }}');
                return;
            }
            
            for (let i = 1; i <= qty; i++) {
                const input = document.getElementById(`unit_barcode_${i}`);
                if (input && !input.value) {
                    generateUnitBarcode(i);
                }
            }
        }

        function generateBusinessFields(index) {
            let fieldsHtml = '';
            
            if (requiredFields.includes('imei_number')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('IMEI / MAC') }}</label>
                        <input type="text" name="units[${index}][imei_number]" class="unit-imei block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="IMEI">
                    </div>
                `;
            }
            
            if (requiredFields.includes('serial_number')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('Serial Number') }}</label>
                        <input type="text" name="units[${index}][serial_number]" class="unit-serial block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Serial No.">
                    </div>
                `;
            }
            
            if (requiredFields.includes('batch_number')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('Batch Number') }}</label>
                        <input type="text" name="units[${index}][batch_number]" class="unit-batch block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Batch No.">
                    </div>
                `;
            }
            
            if (requiredFields.includes('expiry_date')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('Expiry Date') }}</label>
                        <input type="date" name="units[${index}][expiry_date]" class="unit-expiry block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300">
                    </div>
                `;
            }

            if (requiredFields.includes('weight')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('Weight') }}</label>
                        <input type="number" step="0.001" name="units[${index}][weight]" class="unit-weight block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="0.000">
                    </div>
                `;
            }

            if (requiredFields.includes('hallmark')) {
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('Hallmark/ID') }}</label>
                        <input type="text" name="units[${index}][hallmark]" class="unit-hallmark block w-full text-xs bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all duration-300" placeholder="Hallmark ID">
                    </div>
                `;
            }
            
            return fieldsHtml || '<div class="col-span-2 text-xs text-gray-500 dark:text-gray-500 italic font-medium">' + "{{ __('No additional unit fields required.') }}" + '</div>';
        }

        // Scanner Logic
        async function startScanner() {
            const qty = parseInt(quantityInput.value) || 0;
            if (qty <= 0) {
                alert('{{ __("Please enter quantity first.") }}');
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
                alert('{{ __("Camera access failed.") }}');
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