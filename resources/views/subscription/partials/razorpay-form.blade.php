<form action="{{ route('subscription.payment') }}" method="POST" id="form-{{ $plan }}-{{ $cycle }}">
    @csrf
    <input type="hidden" name="plan" value="{{ $plan }}">
    <input type="hidden" name="cycle" value="{{ $cycle }}">
    
    @if(config('app.developer_mode'))
        {{-- In local dev, just submit the form directly for a mock upgrade --}}
        <button type="submit" 
                class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/25">
            {{ __('Get') }} {{ __(ucfirst($plan)) }} {{ __(ucfirst($cycle)) }} ({{ __('Dev Mode') }})
        </button>
    @else
        {{-- In production, use the Razorpay manual checkout --}}
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id_{{ $plan }}_{{ $cycle }}">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id_{{ $plan }}_{{ $cycle }}">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature_{{ $plan }}_{{ $cycle }}">

        <button type="button" 
                id="rzp-button-{{ $plan }}-{{ $cycle }}"
                class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/25">
            {{ __('Upgrade to') }} {{ __(ucfirst($plan)) }} {{ __(ucfirst($cycle)) }}
        </button>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            document.getElementById('rzp-button-{{ $plan }}-{{ $cycle }}').onclick = function(e) {
                var options = {
                    "key": "{{ config('app.razorpay_key') }}",
                    "amount": "{{ $amount }}",
                    "currency": "INR",
                    "name": "StockProNex",
                    "description": "{{ ucfirst($plan) }} Plan ({{ ucfirst($cycle) }})",
                    "order_id": "{{ $orders[$plan][$cycle] ?? '' }}",
                    "handler": function (response){
                        document.getElementById('razorpay_payment_id_{{ $plan }}_{{ $cycle }}').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id_{{ $plan }}_{{ $cycle }}').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature_{{ $plan }}_{{ $cycle }}').value = response.razorpay_signature;
                        document.getElementById('form-{{ $plan }}-{{ $cycle }}').submit();
                    },
                    "prefill": {
                        "name": "{{ Auth::user()->name }}",
                        "email": "{{ Auth::user()->email }}",
                        "contact": "{{ Auth::user()->phone }}"
                    },
                    "theme": {
                        "color": "#3B82F6"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
                e.preventDefault();
            }
        </script>
    @endif
</form>
