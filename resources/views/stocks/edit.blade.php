<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Edit Stock') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-800">
                <div class="p-8">
                    <form method="POST" action="{{ route('stocks.update', $stock) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $stock->name)" required autofocus />
                        </div>

                        <!-- Quantity -->
                        <div class="mt-4">
                            <x-label for="quantity" :value="__('Quantity')" />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                :value="old('quantity', $stock->quantity)" required />
                        </div>

                        <!-- MRP -->
                        <div class="mt-4">
                            <x-label for="mrp" :value="__('MRP (₹)')" />
                            <x-input id="mrp" class="block mt-1 w-full" type="number" step="0.01" name="mrp"
                                :value="old('mrp', $stock->mrp)" />
                        </div>

                        <!-- Selling Price -->
                        <div class="mt-4">
                            <x-label for="price" :value="__('Selling Price (₹)')" />
                            <x-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price"
                                :value="old('price', $stock->price)" required />
                        </div>

                        <!-- Business-Specific Fields -->
                        @php
                            $businessType = Auth::user()->business_type;
                            $attributes = $stock->business_attributes ?? [];
                            $requiredFields = \App\Constants\BusinessIndustry::getRequiredFields($businessType);
                        @endphp

                        <div id="business_fields" class="mt-8 p-6 bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('Business Specific Details') }} ({{ $businessType ? __($businessType) : __('Default') }})
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @if(in_array('brand', $requiredFields))
                                    <div>
                                        <x-label for="brand" :value="__('Brand / Manufacturer')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand', $attributes['brand'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('model_number', $requiredFields))
                                    <div>
                                        <x-label for="model_number" :value="__('Model Number / Name')" />
                                        <x-input id="model_number" class="block mt-1 w-full" type="text" name="business_attributes[model_number]" :value="old('business_attributes.model_number', $attributes['model_number'] ?? $attributes['model'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('serial_number', $requiredFields))
                                    <div>
                                        <x-label for="serial_number" :value="__('Serial Number')" />
                                        <x-input id="serial_number" class="block mt-1 w-full" type="text" name="business_attributes[serial_number]" :value="old('business_attributes.serial_number', $attributes['serial_number'] ?? $attributes['serial'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('imei_number', $requiredFields))
                                    <div>
                                        <x-label for="imei_number" :value="__('IMEI / MAC Address')" />
                                        <x-input id="imei_number" class="block mt-1 w-full" type="text" name="business_attributes[imei_number]" :value="old('business_attributes.imei_number', $attributes['imei_number'] ?? $attributes['imei'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('warranty', $requiredFields))
                                    <div>
                                        <x-label for="warranty" :value="__('Warranty (Months)')" />
                                        <x-input id="warranty" class="block mt-1 w-full" type="number" name="business_attributes[warranty]" :value="old('business_attributes.warranty', $attributes['warranty'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('part_number', $requiredFields))
                                    <div>
                                        <x-label for="part_number" :value="__('Part Number / SKU')" />
                                        <x-input id="part_number" class="block mt-1 w-full" type="text" name="business_attributes[part_number]" :value="old('business_attributes.part_number', $attributes['part_number'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('specification', $requiredFields))
                                    <div>
                                        <x-label for="specification" :value="__('Specifications (AH/Volt/Power)')" />
                                        <x-input id="specification" class="block mt-1 w-full" type="text" name="business_attributes[specification]" :value="old('business_attributes.specification', $attributes['specification'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('certification', $requiredFields))
                                    <div>
                                        <x-label for="certification" :value="__('Certification (ISO/CE/etc)')" />
                                        <x-input id="certification" class="block mt-1 w-full" type="text" name="business_attributes[certification]" :value="old('business_attributes.certification', $attributes['certification'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('batch_number', $requiredFields))
                                    <div>
                                        <x-label for="batch_number" :value="__('Batch Number')" />
                                        <x-input id="batch_number" class="block mt-1 w-full" type="text" name="business_attributes[batch_number]" :value="old('business_attributes.batch_number', $attributes['batch_number'] ?? $attributes['batch'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('expiry_date', $requiredFields))
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date', $attributes['expiry_date'] ?? $attributes['expiry'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('composition', $requiredFields))
                                    <div>
                                        <x-label for="composition" :value="__('Composition / Ingredients')" />
                                        <x-input id="composition" class="block mt-1 w-full" type="text" name="business_attributes[composition]" :value="old('business_attributes.composition', $attributes['composition'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('volume', $requiredFields))
                                    <div>
                                        <x-label for="volume" :value="__('Volume / Quantity (ml/L/kg)')" />
                                        <x-input id="volume" class="block mt-1 w-full" type="text" name="business_attributes[volume]" :value="old('business_attributes.volume', $attributes['volume'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('variety', $requiredFields))
                                    <div>
                                        <x-label for="variety" :value="__('Variety / Type')" />
                                        <x-input id="variety" class="block mt-1 w-full" type="text" name="business_attributes[variety]" :value="old('business_attributes.variety', $attributes['variety'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('size', $requiredFields))
                                    <div>
                                        <x-label for="size" :value="__('Size')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size', $attributes['size'] ?? '')" :placeholder="__('e.g. XL, 42, 32')" />
                                    </div>
                                @endif

                                @if(in_array('color', $requiredFields))
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color', $attributes['color'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('material', $requiredFields))
                                    <div>
                                        <x-label for="material" :value="__('Material Type')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material', $attributes['material'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('dimension', $requiredFields))
                                    <div>
                                        <x-label for="dimension" :value="__('Dimensions')" />
                                        <x-input id="dimension" class="block mt-1 w-full" type="text" name="business_attributes[dimension]" :value="old('business_attributes.dimension', $attributes['dimension'] ?? '')" :placeholder="__('e.g. 10x20x30')" />
                                    </div>
                                @endif

                                @if(in_array('thickness', $requiredFields))
                                    <div>
                                        <x-label for="thickness" :value="__('Thickness (mm/in)')" />
                                        <x-input id="thickness" class="block mt-1 w-full" type="text" name="business_attributes[thickness]" :value="old('business_attributes.thickness', $attributes['thickness'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('grade', $requiredFields))
                                    <div>
                                        <x-label for="grade" :value="__('Grade / Quality')" />
                                        <x-input id="grade" class="block mt-1 w-full" type="text" name="business_attributes[grade]" :value="old('business_attributes.grade', $attributes['grade'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('weight', $requiredFields))
                                    <div>
                                        <x-label for="weight" :value="__('Weight (g/kg)')" />
                                        <x-input id="weight" class="block mt-1 w-full" type="number" step="0.001" name="business_attributes[weight]" :value="old('business_attributes.weight', $attributes['weight'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('purity', $requiredFields))
                                    <div>
                                        <x-label for="purity" :value="__('Purity (Karat/%)')" />
                                        <x-input id="purity" class="block mt-1 w-full" type="text" name="business_attributes[purity]" :value="old('business_attributes.purity', $attributes['purity'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('hallmark', $requiredFields))
                                    <div>
                                        <x-label for="hallmark" :value="__('Hallmark ID')" />
                                        <x-input id="hallmark" class="block mt-1 w-full" type="text" name="business_attributes[hallmark]" :value="old('business_attributes.hallmark', $attributes['hallmark'] ?? '')" />
                                    </div>
                                @endif

                                @if(in_array('making_charges', $requiredFields))
                                    <div>
                                        <x-label for="making_charges" :value="__('Making Charges')" />
                                        <x-input id="making_charges" class="block mt-1 w-full" type="number" step="0.01" name="business_attributes[making_charges]" :value="old('business_attributes.making_charges', $attributes['making_charges'] ?? '')" />
                                    </div>
                                @endif
                            </div>

                            @if(empty($requiredFields))
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-bold italic">{{ __('No additional specialized fields for your business type.') }}</p>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-label for="description" :value="__('Description (Optional)')" />
                            <x-textarea id="description" name="description" rows="3" class="mt-1 block w-full">{{ old('description', $stock->description) }}</x-textarea>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white mr-6 transition-colors uppercase tracking-widest">{{ __('Cancel') }}</a>
                            <x-button id="submitBtn" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-sm font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95">
                                {{ __('Update Stock') }}
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
</x-app-layout>