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
                        @endphp

                        <div id="business_fields" class="mt-8 p-6 bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('Business Specific Details') }} ({{ $businessType ?? __('Default') }})
                            </h3>

                            @if($businessType == 'Gold / Jewellery')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="weight" :value="__('Weight (Grams)')" />
                                        <x-input id="weight" class="block mt-1 w-full gold-calc" type="number" step="0.001" name="business_attributes[weight]" :value="old('business_attributes.weight', $attributes['weight'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="purity" :value="__('Purity')" />
                                        <x-select id="purity" name="business_attributes[purity]" class="mt-1 block w-full">
                                            <option value="22k" {{ ($attributes['purity'] ?? '') == '22k' ? 'selected' : '' }}>22k</option>
                                            <option value="24k" {{ ($attributes['purity'] ?? '') == '24k' ? 'selected' : '' }}>24k</option>
                                            <option value="18k" {{ ($attributes['purity'] ?? '') == '18k' ? 'selected' : '' }}>18k</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="rate_per_gram" :value="__('Rate per Gram (₹)')" />
                                        <x-input id="rate_per_gram" class="block mt-1 w-full gold-calc" type="number" step="0.01" name="business_attributes[rate_per_gram]" :value="old('business_attributes.rate_per_gram', $attributes['rate_per_gram'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="making_charges" :value="__('Making Charges (₹)')" />
                                        <x-input id="making_charges" class="block mt-1 w-full gold-calc" type="number" step="0.01" name="business_attributes[making_charges]" :value="old('business_attributes.making_charges', $attributes['making_charges'] ?? '')" />
                                    </div>
                                </div>
                                <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 mt-4 uppercase tracking-widest italic">* {{ __('Total Price auto-calculated: (Weight × Rate) + Making Charges') }}</p>
                            @elseif($businessType == 'Grocery')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="unit" :value="__('Unit')" />
                                        <x-select id="unit" name="business_attributes[unit]" class="mt-1 block w-full">
                                            <option value="kg" {{ ($attributes['unit'] ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                                            <option value="litre" {{ ($attributes['unit'] ?? '') == 'litre' ? 'selected' : '' }}>litre</option>
                                            <option value="packet" {{ ($attributes['unit'] ?? '') == 'packet' ? 'selected' : '' }}>packet</option>
                                            <option value="unit" {{ ($attributes['unit'] ?? '') == 'unit' ? 'selected' : '' }}>unit</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date', $attributes['expiry_date'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Electronics' || $businessType == 'Mobile Shop')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand', $attributes['brand'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="model_number" :value="__('Model Number')" />
                                        <x-input id="model_number" class="block mt-1 w-full" type="text" name="business_attributes[model_number]" :value="old('business_attributes.model_number', $attributes['model_number'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="warranty" :value="__('Warranty (Months)')" />
                                        <x-input id="warranty" class="block mt-1 w-full" type="number" name="business_attributes[warranty]" :value="old('business_attributes.warranty', $attributes['warranty'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Clothing')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="size" :value="__('Size')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size', $attributes['size'] ?? '')" :placeholder="__('e.g. XL, 42, 32')" />
                                    </div>
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color', $attributes['color'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material', $attributes['material'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Medical Store')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-label for="batch_number" :value="__('Batch Number')" />
                                        <x-input id="batch_number" class="block mt-1 w-full" type="text" name="business_attributes[batch_number]" :value="old('business_attributes.batch_number', $attributes['batch_number'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date', $attributes['expiry_date'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="mrp" :value="__('MRP (₹)')" />
                                        <x-input id="mrp" class="block mt-1 w-full" type="number" step="0.01" name="business_attributes[mrp]" :value="old('business_attributes.mrp', $attributes['mrp'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Hardware')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand', $attributes['brand'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material', $attributes['material'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="size" :value="__('Size/Dimensions')" />
                                        <x-input id="size" class="block mt-1 w-full" type="text" name="business_attributes[size]" :value="old('business_attributes.size', $attributes['size'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Automobile parts')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="part_number" :value="__('Part Number')" />
                                        <x-input id="part_number" class="block mt-1 w-full" type="text" name="business_attributes[part_number]" :value="old('business_attributes.part_number', $attributes['part_number'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="compatibility" :value="__('Vehicle Compatibility')" />
                                        <x-input id="compatibility" class="block mt-1 w-full" type="text" name="business_attributes[compatibility]" :value="old('business_attributes.compatibility', $attributes['compatibility'] ?? '')" :placeholder="__('e.g. Toyota Corolla 2020')" />
                                    </div>
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand', $attributes['brand'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Furniture')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="material" :value="__('Material')" />
                                        <x-input id="material" class="block mt-1 w-full" type="text" name="business_attributes[material]" :value="old('business_attributes.material', $attributes['material'] ?? '')" :placeholder="__('e.g. Teak Wood, Steel')" />
                                    </div>
                                    <div>
                                        <x-label for="dimensions" :value="__('Dimensions')" />
                                        <x-input id="dimensions" class="block mt-1 w-full" type="text" name="business_attributes[dimensions]" :value="old('business_attributes.dimensions', $attributes['dimensions'] ?? '')" :placeholder="__('e.g. 6x4 ft')" />
                                    </div>
                                    <div>
                                        <x-label for="color" :value="__('Color')" />
                                        <x-input id="color" class="block mt-1 w-full" type="text" name="business_attributes[color]" :value="old('business_attributes.color', $attributes['color'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Cosmetic')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="brand" :value="__('Brand')" />
                                        <x-input id="brand" class="block mt-1 w-full" type="text" name="business_attributes[brand]" :value="old('business_attributes.brand', $attributes['brand'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date', $attributes['expiry_date'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="volume" :value="__('Volume/Weight')" />
                                        <x-input id="volume" class="block mt-1 w-full" type="text" name="business_attributes[volume]" :value="old('business_attributes.volume', $attributes['volume'] ?? '')" :placeholder="__('e.g. 100ml, 50g')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Book Store')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="isbn" :value="__('ISBN Number')" />
                                        <x-input id="isbn" class="block mt-1 w-full" type="text" name="business_attributes[isbn]" :value="old('business_attributes.isbn', $attributes['isbn'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="author" :value="__('Author')" />
                                        <x-input id="author" class="block mt-1 w-full" type="text" name="business_attributes[author]" :value="old('business_attributes.author', $attributes['author'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="publisher" :value="__('Publisher')" />
                                        <x-input id="publisher" class="block mt-1 w-full" type="text" name="business_attributes[publisher]" :value="old('business_attributes.publisher', $attributes['publisher'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Restaurant')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="category" :value="__('Category')" />
                                        <x-select id="category" name="business_attributes[category]" class="mt-1 block w-full">
                                            <option value="Veg" {{ ($attributes['category'] ?? '') == 'Veg' ? 'selected' : '' }}>Veg</option>
                                            <option value="Non-Veg" {{ ($attributes['category'] ?? '') == 'Non-Veg' ? 'selected' : '' }}>Non-Veg</option>
                                            <option value="Beverage" {{ ($attributes['category'] ?? '') == 'Beverage' ? 'selected' : '' }}>Beverage</option>
                                            <option value="Other" {{ ($attributes['category'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-label for="expiry_date" :value="__('Expiry Date/Best Before')" />
                                        <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="business_attributes[expiry_date]" :value="old('business_attributes.expiry_date', $attributes['expiry_date'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="batch" :value="__('Batch Number')" />
                                        <x-input id="batch" class="block mt-1 w-full" type="text" name="business_attributes[batch]" :value="old('business_attributes.batch', $attributes['batch'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Agricultural')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="variety" :value="__('Variety/Grade')" />
                                        <x-input id="variety" class="block mt-1 w-full" type="text" name="business_attributes[variety]" :value="old('business_attributes.variety', $attributes['variety'] ?? '')" :placeholder="__('e.g. Grade A, Basmati')" />
                                    </div>
                                    <div>
                                        <x-label for="harvest_date" :value="__('Harvest Date')" />
                                        <x-input id="harvest_date" class="block mt-1 w-full" type="date" name="business_attributes[harvest_date]" :value="old('business_attributes.harvest_date', $attributes['harvest_date'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="origin" :value="__('Origin')" />
                                        <x-input id="origin" class="block mt-1 w-full" type="text" name="business_attributes[origin]" :value="old('business_attributes.origin', $attributes['origin'] ?? '')" />
                                    </div>
                                </div>
                            @elseif($businessType == 'Wholesale')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="pack_size" :value="__('Pack Size')" />
                                        <x-input id="pack_size" class="block mt-1 w-full" type="text" name="business_attributes[pack_size]" :value="old('business_attributes.pack_size', $attributes['pack_size'] ?? '')" :placeholder="__('e.g. Case of 24, Bulk 50kg')" />
                                    </div>
                                    <div>
                                        <x-label for="min_order" :value="__('Min Order Qty')" />
                                        <x-input id="min_order" class="block mt-1 w-full" type="number" name="business_attributes[min_order]" :value="old('business_attributes.min_order', $attributes['min_order'] ?? '')" />
                                    </div>
                                    <div>
                                        <x-label for="manufacturer" :value="__('Manufacturer')" />
                                        <x-input id="manufacturer" class="block mt-1 w-full" type="text" name="business_attributes[manufacturer]" :value="old('business_attributes.manufacturer', $attributes['manufacturer'] ?? '')" />
                                    </div>
                                </div>
                            @else
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