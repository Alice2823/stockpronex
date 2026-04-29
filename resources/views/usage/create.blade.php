<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Record Stock Usage') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 dark:border-gray-800">
                <div class="p-8 bg-white dark:bg-gray-900">
                    <form id="usage-form" onsubmit="submitForm(event)">
                        @csrf

                        <!-- Status Feedback -->
                        <div id="status-container" class="mb-6 hidden transition-all duration-300">
                            <div id="status-card" class="p-4 rounded-xl border flex items-center transition-all">
                                <div id="status-icon-container" class="p-2 rounded-lg mr-4"></div>
                                <div class="grow">
                                    <h4 id="status-title" class="text-sm font-black text-gray-900 dark:text-white"></h4>
                                    <p id="status-message" class="text-xs text-gray-600 dark:text-gray-400 font-medium"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Item Selection -->
                        <div>
                            <x-label for="stock_id" :value="__('Select Stock Item')" />
                            <x-select id="stock_id" name="stock_id" class="mt-1 block w-full" required>
                                <option value="">-- {{ __('Choose an item') }} --</option>
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->id }}">
                                        {{ $stock->name }} (Available: {{ $stock->quantity }})
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-label for="quantity" :value="__('Quantity Used')" />
                                <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" min="1"
                                    required />
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-label for="discount_percentage" :value="__('Discount (%)')" />
                                <x-input id="discount_percentage" class="block mt-1 w-full" type="number" name="discount_percentage" min="0" max="100" step="0.01" value="0" />
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">{{ __('Applied to subtotal before tax') }}</p>
                            </div>
                        </div>

                        <!-- Business-Specific Mandatory Fields Container -->
                        @php
                            $businessType = Auth::user()->business_type;
                        @endphp

                        <div id="business_fields_container" class="mt-8">
                            <!-- Dynamic sections will be injected here -->
                        </div>

                        <!-- Template for Business Fields (Hidden) -->
                        <template id="business_fields_template">
                            <div class="business-field-section mb-6 p-6 bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl border border-gray-100 dark:border-gray-800 transition-all duration-300">
                                <h3 class="text-xs font-black text-blue-600 dark:text-blue-400 mb-4 flex items-center uppercase tracking-widest">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Item') }} #<span class="item-index">1</span> {{ __('Details') }} ({{ $businessType ? __($businessType) : __('General Inventory') }})
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @php
                                        $requiredFields = \App\Constants\BusinessIndustry::getRequiredFields($businessType);
                                    @endphp

                                    @if(in_array('brand', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Brand / Manufacturer') }}" />
                                            <x-input class="block mt-1 w-full brand-input" type="text" name="business_attributes[__INDEX__][brand]" />
                                        </div>
                                    @endif

                                    @if(in_array('model_number', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Model Number / Name') }}" />
                                            <x-input class="block mt-1 w-full model-input" type="text" name="business_attributes[__INDEX__][model_number]" />
                                        </div>
                                    @endif

                                    @if(in_array('imei_number', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('IMEI / MAC Address *') }}" />
                                            <x-input class="block mt-1 w-full imei-input" type="text" name="business_attributes[__INDEX__][imei_number]" required />
                                        </div>
                                    @endif

                                    @if(in_array('serial_number', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Serial Number') }}" />
                                            <x-input class="block mt-1 w-full serial-input" type="text" name="business_attributes[__INDEX__][serial_number]" />
                                        </div>
                                    @endif

                                    @if(in_array('warranty', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Warranty (Months)') }}" />
                                            <x-input class="block mt-1 w-full warranty-input" type="number" name="business_attributes[__INDEX__][warranty]" />
                                        </div>
                                    @endif

                                    @if(in_array('part_number', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Part Number / SKU') }}" />
                                            <x-input class="block mt-1 w-full part-input" type="text" name="business_attributes[__INDEX__][part_number]" />
                                        </div>
                                    @endif

                                    @if(in_array('specification', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Specifications') }}" />
                                            <x-input class="block mt-1 w-full spec-input" type="text" name="business_attributes[__INDEX__][specification]" />
                                        </div>
                                    @endif

                                    @if(in_array('expiry_date', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Expiry Date *') }}" />
                                            <x-input class="block mt-1 w-full expiry-input" type="date" name="business_attributes[__INDEX__][expiry_date]" required />
                                        </div>
                                    @endif

                                    @if(in_array('batch_number', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Batch Number') }}" />
                                            <x-input class="block mt-1 w-full batch-input" type="text" name="business_attributes[__INDEX__][batch_number]" />
                                        </div>
                                    @endif

                                    @if(in_array('weight', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Weight (g/kg) *') }}" />
                                            <x-input class="block mt-1 w-full weight-input" type="number" step="0.001" name="business_attributes[__INDEX__][weight]" required />
                                        </div>
                                    @endif

                                    @if(in_array('size', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Size *') }}" />
                                            <x-input class="block mt-1 w-full size-input" type="text" name="business_attributes[__INDEX__][size]" required />
                                        </div>
                                    @endif

                                    @if(in_array('color', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Color *') }}" />
                                            <x-input class="block mt-1 w-full color-input" type="text" name="business_attributes[__INDEX__][color]" required />
                                        </div>
                                    @endif

                                    @if(in_array('material', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Material *') }}" />
                                            <x-input class="block mt-1 w-full material-input" type="text" name="business_attributes[__INDEX__][material]" required />
                                        </div>
                                    @endif

                                    @if(in_array('purity', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Purity *') }}" />
                                            <x-input class="block mt-1 w-full purity-input" type="text" name="business_attributes[__INDEX__][purity]" required />
                                        </div>
                                    @endif

                                    @if(in_array('hallmark', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Hallmark ID') }}" />
                                            <x-input class="block mt-1 w-full hallmark-input" type="text" name="business_attributes[__INDEX__][hallmark]" />
                                        </div>
                                    @endif

                                    @if(in_array('making_charges', $requiredFields))
                                        <div>
                                            <x-label value="{{ __('Making Charges (₹) *') }}" />
                                            <x-input class="block mt-1 w-full making-input" type="number" step="0.01" name="business_attributes[__INDEX__][making_charges]" required />
                                        </div>
                                    @endif

                                    @if(empty($requiredFields))
                                        <div class="col-span-full">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('No additional specialized fields required.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </template>

                        <!-- Customer Information Section -->
                        <div class="mt-8 p-6 bg-blue-50/30 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800/40">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Customer Information (Optional)
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="customer_name" :value="__('Customer Name')" />
                                    <x-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" placeholder="Enter name" />
                                </div>
                                <div>
                                    <x-label for="customer_phone" :value="__('Phone Number')" />
                                    <x-input id="customer_phone" name="customer_phone" type="text" class="mt-1 block w-full" placeholder="Enter phone" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-label for="customer_address" :value="__('Billing Address')" />
                                    <x-textarea id="customer_address" name="customer_address" class="mt-1 block w-full" rows="1" placeholder="Enter address"></x-textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-label for="notes" :value="__('Additional Notes (Optional)')" />
                            <x-textarea id="notes" name="notes" rows="3" class="mt-1 block w-full"></x-textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 gap-4">
                            <a href="{{ route('usage.index') }}"
                                class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors sm:mr-auto">{{ __('Cancel Order') }}</a>
                            
                            <button type="button" onclick="submitForm(event, 'cash')" id="btn-cash"
                                class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-500/20 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                                <span>💵</span> {{ __('Sell via Cash') }}
                            </button>

                            <button type="button" onclick="submitForm(event, 'online')" id="btn-online"
                                class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                                <span>💳</span> {{ __('Sell via Online') }}
                            </button>
                        </div>
                    </form>

    <script>
        let currentStockDetails = null;
        let lastInvoiceId = null;

        // Phone validation
        const PHONE_REGEX = /^\+?\d{10,15}$/;
        function validatePhone(phone) {
            if (!phone) return { valid: true }; // optional in manual mode
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            if (!PHONE_REGEX.test(cleaned)) {
                return { valid: false, message: 'Enter valid phone with country code (e.g. +91XXXXXXXXXX).' };
            }
            return { valid: true };
        }

        document.getElementById('stock_id').addEventListener('change', fetchStockDetails);
        document.getElementById('quantity').addEventListener('input', refreshBusinessFields);

        function fetchStockDetails() {
            const stockId = document.getElementById('stock_id').value;
            if (!stockId) {
                currentStockDetails = null;
                refreshBusinessFields();
                return;
            }

            updateStatus('Fetching Details', 'Retrieving stock attributes...', 'loading');
            
            fetch(`/stocks/${stockId}`)
                .then(res => res.json())
                .then(data => {
                    currentStockDetails = data;
                    document.getElementById('status-container').classList.add('hidden');
                    refreshBusinessFields();
                })
                .catch(err => {
                    updateStatus('Error', 'Failed to fetch stock details.', 'error');
                });
        }

        function refreshBusinessFields() {
            const container = document.getElementById('business_fields_container');
            const template = document.getElementById('business_fields_template');
            const qty = parseInt(document.getElementById('quantity').value) || 0;
            
            container.innerHTML = '';
            
            if (qty <= 0) return;

            for (let i = 0; i < qty; i++) {
                let content = template.innerHTML
                    .replace(/__INDEX__/g, i)
                    .replace('Item #1', `Item #${i + 1}`);
                
                const wrapper = document.createElement('div');
                wrapper.innerHTML = content;
                const section = wrapper.firstElementChild;
                
                // 1. Priority: Auto-fill from per-unit tracking data (IMEI, Serial, etc.)
                const unit = (currentStockDetails && currentStockDetails.units && currentStockDetails.units[i]) ? currentStockDetails.units[i] : null;
                if (unit) {
                    if (section.querySelector('.imei-input')) section.querySelector('.imei-input').value = unit.imei_number || '';
                    if (section.querySelector('.serial-input')) section.querySelector('.serial-input').value = unit.serial_number || '';
                    if (section.querySelector('.expiry-input')) {
                        const expiryDate = unit.expiry_date ? unit.expiry_date.split('T')[0] : '';
                        section.querySelector('.expiry-input').value = expiryDate;
                    }
                    if (section.querySelector('.batch-input')) section.querySelector('.batch-input').value = unit.batch_number || '';
                    if (section.querySelector('.weight-input')) section.querySelector('.weight-input').value = unit.weight || '';
                    if (section.querySelector('.hallmark-input')) section.querySelector('.hallmark-input').value = unit.hallmark || '';
                }

                // 2. Secondary: Auto-fill common/static stock attributes if available
                if (currentStockDetails && currentStockDetails.business_attributes) {
                    const attrs = currentStockDetails.business_attributes;
                    
                    // Helper to fill if field exists and is empty
                    const fillIfEmpty = (selector, value) => {
                        const el = section.querySelector(selector);
                        if (el && !el.value) el.value = value || '';
                    };

                    fillIfEmpty('.brand-input', attrs.brand);
                    fillIfEmpty('.model-input', attrs.model_number || attrs.model);
                    fillIfEmpty('.warranty-input', attrs.warranty);
                    fillIfEmpty('.part-input', attrs.part_number);
                    fillIfEmpty('.spec-input', attrs.specification);
                    fillIfEmpty('.size-input', attrs.size);
                    fillIfEmpty('.color-input', attrs.color);
                    fillIfEmpty('.material-input', attrs.material);
                    fillIfEmpty('.purity-input', attrs.purity);
                    fillIfEmpty('.making-input', attrs.making_charges);
                    
                    // Fallback for tracking fields if no unit found
                    if (!unit) {
                        fillIfEmpty('.imei-input', attrs.imei_number);
                        fillIfEmpty('.serial-input', attrs.serial_number);
                        fillIfEmpty('.expiry-input', attrs.expiry_date ? attrs.expiry_date.split('T')[0] : '');
                        fillIfEmpty('.batch-input', attrs.batch_number);
                    }
                }

                container.appendChild(section);
            }
        }

        function submitForm(event, paymentMethod) {
            event.preventDefault();
            const form = document.getElementById('usage-form');
            const btnCash = document.getElementById('btn-cash');
            const btnOnline = document.getElementById('btn-online');
            
            const formData = new FormData(form);
            const data = {
                payment_method: paymentMethod,
                business_attributes: []
            };
            
            // Special handling for indexed business_attributes
            const attrMap = {};
            formData.forEach((value, key) => {
                if (key.startsWith('business_attributes[')) {
                    // Extract index and subkey: business_attributes[0][imei]
                    const match = key.match(/business_attributes\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const idx = match[1];
                        const subKey = match[2];
                        if (!attrMap[idx]) attrMap[idx] = {};
                        attrMap[idx][subKey] = value;
                    }
                } else if (key === 'discount_percentage') {
                    data[key] = parseFloat(value) || 0;
                } else if (key !== '_token') {
                    data[key] = value;
                }
            });

            // Convert map to array
            Object.keys(attrMap).sort().forEach(idx => {
                data.business_attributes.push(attrMap[idx]);
            });

            // Validate phone if provided
            if (data.customer_phone) {
                const phoneCheck = validatePhone(data.customer_phone);
                if (!phoneCheck.valid) {
                    updateStatus('Validation Error', phoneCheck.message, 'error');
                    return;
                }
            }

            // Disable buttons
            btnCash.disabled = true;
            btnOnline.disabled = true;
            
            const activeBtn = paymentMethod === 'cash' ? btnCash : btnOnline;
            const originalContent = activeBtn.innerHTML;
            activeBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            updateStatus('Processing Transaction...', `Recording ${paymentMethod.toUpperCase()} payment...`, 'loading');

            fetch("{{ route('usage.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    lastInvoiceId = data.data?.invoice_id;
                    updateStatus('Success!', data.message, 'success');

                    // Get the phone value from form
                    const phone = document.getElementById('customer_phone').value;

                    // Trigger WhatsApp send if phone provided and invoice_id exists
                    if (lastInvoiceId && phone && phone.trim() !== '' && phone !== 'N/A') {
                        sendWhatsAppInvoice(lastInvoiceId);
                    } else {
                        // No phone, just redirect after delay
                        setTimeout(() => {
                            window.location.href = "{{ route('usage.index') }}";
                        }, 1500);
                    }
                } else {
                    updateStatus('Transaction Failed', data.message || 'Validation error occurred.', 'error');
                    btnCash.disabled = false;
                    btnOnline.disabled = false;
                    btnCash.innerHTML = '<span>💵</span> Sell via Cash';
                    btnOnline.innerHTML = '<span>💳</span> Sell via Online';
                }
            })
            .catch(err => {
                updateStatus('System Error', 'Could not reach the server. Please check your connection.', 'error');
                btnCash.disabled = false;
                btnOnline.disabled = false;
                btnCash.innerHTML = '<span>💵</span> Sell via Cash';
                btnOnline.innerHTML = '<span>💳</span> Sell via Online';
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
                } else {
                    showWhatsAppToast('Failed', data.message || 'WhatsApp send failed', 'error');
                    showResendButton();
                }
                // Redirect after a delay regardless
                setTimeout(() => {
                    window.location.href = "{{ route('usage.index') }}";
                }, 3000);
            })
            .catch(err => {
                showWhatsAppToast('Error', 'Could not send WhatsApp message', 'error');
                showResendButton();
                setTimeout(() => {
                    window.location.href = "{{ route('usage.index') }}";
                }, 3000);
            });
        }

        function showResendButton() {
            // Add resend button in status container
            const container = document.getElementById('status-container');
            if (!document.getElementById('resend-whatsapp-btn')) {
                const btn = document.createElement('button');
                btn.id = 'resend-whatsapp-btn';
                btn.className = 'mt-3 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg transition-all';
                btn.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.613.613l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.353 0-4.556-.682-6.426-1.855l-.352-.215-3.65 1.224 1.224-3.65-.215-.352A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg> Resend on WhatsApp';
                btn.onclick = function() {
                    if (lastInvoiceId) sendWhatsAppInvoice(lastInvoiceId);
                };
                container.querySelector('#status-card').appendChild(btn);
            }
        }

        /**
         * Show WhatsApp toast notification
         */
        function showWhatsAppToast(title, message, type) {
            // Create toast if it doesn't exist
            let toast = document.getElementById('whatsapp-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'whatsapp-toast';
                toast.className = 'fixed bottom-6 right-6 z-[60] transform transition-all duration-500 translate-y-4 opacity-0';
                toast.innerHTML = `
                    <div id="whatsapp-toast-card" class="flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border max-w-md">
                        <div id="whatsapp-toast-icon" class="shrink-0"></div>
                        <div class="min-w-0">
                            <p id="whatsapp-toast-title" class="font-black text-sm"></p>
                            <p id="whatsapp-toast-message" class="text-xs font-medium opacity-80 truncate"></p>
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
            }

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

        function updateStatus(title, message, state) {
            const container = document.getElementById('status-container');
            const card = document.getElementById('status-card');
            const iconBox = document.getElementById('status-icon-container');
            const titleEl = document.getElementById('status-title');
            const messageEl = document.getElementById('status-message');

            container.classList.remove('hidden');
            
            if (state === 'loading') {
                card.className = 'p-4 rounded-xl border bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800/50 flex items-center animate-pulse';
                iconBox.className = 'p-2 rounded-lg mr-4 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400';
                iconBox.innerHTML = '<svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
            } else if (state === 'success') {
                card.className = 'p-4 rounded-xl border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 flex items-center';
                iconBox.className = 'p-2 rounded-lg mr-4 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400';
                iconBox.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            } else {
                card.className = 'p-4 rounded-xl border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 flex items-center';
                iconBox.className = 'p-2 rounded-lg mr-4 bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400';
                iconBox.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
            }

            titleEl.innerText = title;
            messageEl.innerText = message;
        }
    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>