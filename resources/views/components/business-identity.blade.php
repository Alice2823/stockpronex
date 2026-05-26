@if(Auth::user()->business_name || Auth::user()->business_type)
    <div class="flex flex-col items-end">
        <div class="flex items-center gap-2">
            @if(Auth::user()->business_name)
                <span class="text-xl font-black text-gray-900 dark:text-white tracking-tighter uppercase decoration-blue-500 decoration-2 underline-offset-4">
                    {{ Auth::user()->business_name }}
                </span>
            @endif
            <div class="h-2 w-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)] animate-pulse"></div>
        </div>
        @if(Auth::user()->business_type)
            <span class="mt-1 inline-flex items-center px-3 py-0.5 rounded-lg text-[10px] font-black bg-blue-600/10 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-600/20 dark:border-blue-500/20 uppercase tracking-widest backdrop-blur-sm">
                {{ Auth::user()->business_type }}
            </span>
        @endif
    </div>
@endif
