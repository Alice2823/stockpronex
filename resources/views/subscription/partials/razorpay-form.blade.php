<div id="form-{{ $plan }}-{{ $cycle }}">
    @if(config('app.developer_mode'))
        {{-- In local dev, just submit the form directly for a mock upgrade --}}
        <form action="{{ route('subscription.verify-payment') }}" method="POST">
            @csrf
            <input type="hidden" name="plan" value="{{ $plan }}">
            <input type="hidden" name="cycle" value="{{ $cycle }}">
            <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/25">
                {{ __('Get') }} {{ __(ucfirst($plan)) }} {{ __(ucfirst($cycle)) }} ({{ __('Dev Mode') }})
            </button>
        </form>
    @else
        {{-- In production, use the Razorpay manual checkout via global JS --}}
        <button type="button" 
                id="rzp-button-{{ $plan }}-{{ $cycle }}"
                onclick="window.initializeRazorpay('{{ $plan }}', '{{ $cycle }}')"
                class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/25">
            {{ __('Upgrade to') }} {{ __(ucfirst($plan)) }} {{ __(ucfirst($cycle)) }}
        </button>
    @endif
</div>
