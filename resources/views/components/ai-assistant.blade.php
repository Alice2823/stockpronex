{{-- AI Assistant Chat Widget --}}
<div id="ai-assistant" x-data="aiAssistant()" x-cloak>
    
    {{-- Floating Chat Button --}}
    <button 
        @click="toggleChat()"
        class="fixed bottom-24 sm:bottom-6 right-4 sm:right-6 z-50 group animate-float"
        :class="isOpen ? 'scale-0 opacity-0' : 'scale-100 opacity-100'"
        style="transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);"
    >
        <div class="relative">
            {{-- Outer Pulse --}}
            <div class="absolute inset-[-12px] rounded-full bg-blue-500/20 animate-pulse blur-xl group-hover:bg-indigo-500/30 transition-colors"></div>
            
            {{-- Pulse rings --}}
            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 animate-ping opacity-20" style="animation-duration: 3s;"></div>
            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-400 to-purple-500 animate-ping opacity-10" style="animation-duration: 4s; animation-delay: 1s;"></div>
            
            {{-- Button --}}
            <div class="relative h-12 w-12 sm:h-16 sm:w-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 shadow-[0_8px_30px_rgb(59,130,246,0.4)] flex items-center justify-center text-white hover:shadow-[0_15px_40px_rgb(59,130,246,0.6)] hover:scale-110 hover:-rotate-3 transition-all duration-500 border border-white/30 backdrop-blur-md overflow-hidden">
                {{-- Shine effect --}}
                <div class="absolute top-0 -left-[100%] w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-[45deg] group-hover:left-[100%] transition-all duration-1000 ease-in-out"></div>
                
                <svg class="h-6 w-6 sm:h-8 sm:w-8 drop-shadow-[0_2px_4px_rgba(0,0,0,0.2)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                    <circle cx="12" cy="8" r="1.5" fill="currentColor" />
                </svg>
            </div>
            
            {{-- Label --}}
            <div class="absolute -top-12 right-0 whitespace-nowrap bg-gray-900 dark:bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 pointer-events-none shadow-2xl border border-white/20">
                {{ __('Ask AI') }} ✨
                <div class="absolute bottom-[-4px] right-6 rotate-45 w-2 h-2 bg-gray-900 dark:bg-blue-600 border-r border-b border-white/20"></div>
            </div>
        </div>
    </button>

    {{-- Chat Panel --}}
    <div 
        class="fixed bottom-24 sm:bottom-6 right-4 sm:right-6 z-50 w-[400px] max-w-[calc(100vw-2rem)]"
        :class="isOpen ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95 pointer-events-none'"
        style="transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);"
    >
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-black/20 dark:shadow-black/50 border border-gray-200/80 dark:border-gray-700/80 overflow-hidden flex flex-col" style="height: 560px;">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 px-5 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm tracking-tight">{{ __('AI Stock Assistant') }}</h3>
                        <div class="flex items-center space-x-1.5">
                            <div class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-blue-100 text-xs font-medium">{{ __('Online') }} • Powered by Gemini</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="clearChat()" class="h-8 w-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition-all duration-200" title="Clear chat">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    <button @click="toggleChat()" class="h-8 w-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition-all duration-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Messages Area --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-4 ai-chat-messages" x-ref="messagesContainer" style="scroll-behavior: smooth;">
                
                {{-- Welcome message --}}
                <template x-if="messages.length === 0">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shrink-0 mt-0.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                                </svg>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/80 rounded-2xl rounded-tl-md px-4 py-3 max-w-[80%] border border-gray-100 dark:border-gray-700/50">
                                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    {{ __('Hey') }} {{ Auth::user()->name }}! 👋 {{ __('I\'m your AI Stock Assistant. I can help you with:') }}
                                </p>
                                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <li>📊 {{ __('Stock overview & insights') }}</li>
                                    <li>⚠️ {{ __('Low stock alerts') }}</li>
                                    <li>📈 {{ __('Sales & usage trends') }}</li>
                                    <li>💡 {{ __('Business recommendations') }}</li>
                                </ul>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Ask me anything about your inventory!') }}</p>
                            </div>
                        </div>

                        {{-- Quick suggestions --}}
                        <div class="pl-11">
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ __('Quick Actions') }}</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="suggestion in suggestions" :key="suggestion">
                                    <button 
                                        @click="sendSuggestion(suggestion)"
                                        class="text-xs font-semibold px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200/50 dark:border-blue-700/50 hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 cursor-pointer"
                                        x-text="suggestion"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Chat messages --}}
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex items-start space-x-3'">
                        {{-- AI avatar --}}
                        <template x-if="msg.role === 'ai'">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shrink-0 mt-0.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                                </svg>
                            </div>
                        </template>
                        
                        {{-- Message bubble --}}
                        <div :class="msg.role === 'user' 
                            ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl rounded-tr-md px-4 py-3 max-w-[80%] shadow-sm' 
                            : 'bg-gray-50 dark:bg-gray-800/80 rounded-2xl rounded-tl-md px-4 py-3 max-w-[80%] border border-gray-100 dark:border-gray-700/50'"
                        >
                            <div 
                                class="text-sm leading-relaxed whitespace-pre-wrap ai-message-content"
                                :class="msg.role === 'user' ? 'text-white' : 'text-gray-700 dark:text-gray-300'"
                                x-html="msg.role === 'ai' ? formatMessage(msg.content) : msg.content"
                            ></div>
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <template x-if="isLoading">
                    <div class="flex items-start space-x-3">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/80 rounded-2xl rounded-tl-md px-5 py-3.5 border border-gray-100 dark:border-gray-700/50">
                            <div class="flex items-center space-x-1.5">
                                <div class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-bounce" style="animation-delay: 0ms;"></div>
                                <div class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-bounce" style="animation-delay: 150ms;"></div>
                                <div class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-bounce" style="animation-delay: 300ms;"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Input Area --}}
            <div class="p-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 shrink-0">
                <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                    <div class="flex-1 relative">
                        <input 
                            x-model="inputMessage"
                            type="text"
                            placeholder="{{ __('Ask about your inventory...') }}"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-200"
                            :disabled="isLoading"
                            x-ref="chatInput"
                        >
                    </div>
                    <button 
                        type="submit"
                        :disabled="isLoading || !inputMessage.trim()"
                        class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white flex items-center justify-center shadow-sm hover:shadow-md transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-sm shrink-0"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
                <p class="text-center text-[10px] text-gray-400 dark:text-gray-600 mt-1.5 font-medium">{{ __('Powered by Google Gemini AI') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scrollbar */
    .ai-chat-messages::-webkit-scrollbar { width: 4px; }
    .ai-chat-messages::-webkit-scrollbar-track { background: transparent; }
    .ai-chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    .dark .ai-chat-messages::-webkit-scrollbar-thumb { background: #334155; }
    
    /* Bounce animation for typing indicator */
    @keyframes bounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
    }
    
    /* AI message formatting */
    .ai-message-content strong { font-weight: 700; }
    .ai-message-content em { font-style: italic; }
    .ai-message-content ul { list-style-type: disc; padding-left: 1.2em; margin: 0.3em 0; }
    .ai-message-content ol { list-style-type: decimal; padding-left: 1.2em; margin: 0.3em 0; }
    .ai-message-content li { margin: 0.15em 0; }
    .ai-message-content p { margin: 0.3em 0; }
    .ai-message-content p:first-child { margin-top: 0; }
    .ai-message-content p:last-child { margin-bottom: 0; }
</style>

<script>
function aiAssistant() {
    return {
        isOpen: false,
        isLoading: false,
        inputMessage: '',
        messages: [],
        suggestions: [
            '📊 Stock summary',
            '⚠️ Low stock items',
            '📈 Top selling items',
            '💰 Revenue overview',
            '🔄 What to reorder?',
            '💡 Business tips',
        ],

        init() {
            // Load suggestions from server
            this.loadSuggestions();
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.$refs.chatInput?.focus();
                    this.scrollToBottom();
                });
            }
        },

        async loadSuggestions() {
            try {
                const res = await fetch('{{ route("ai.suggestions") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data.suggestions) {
                        this.suggestions = data.suggestions;
                    }
                }
            } catch (e) {
                // Keep defaults
            }
        },

        sendSuggestion(text) {
            this.inputMessage = text;
            this.sendMessage();
        },

        async sendMessage() {
            const msg = this.inputMessage.trim();
            if (!msg || this.isLoading) return;

            // Add user message
            this.messages.push({ role: 'user', content: msg });
            this.inputMessage = '';
            this.isLoading = true;
            this.scrollToBottom();

            try {
                const res = await fetch('{{ route("ai.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: msg }),
                });

                const data = await res.json();

                if (data.success) {
                    this.messages.push({ role: 'ai', content: data.message });
                } else {
                    this.messages.push({ role: 'ai', content: data.message || 'Sorry, something went wrong. Please try again.' });
                }
            } catch (error) {
                this.messages.push({ role: 'ai', content: '⚠️ Network error. Please check your connection and try again.' });
            }

            this.isLoading = false;
            this.scrollToBottom();
            this.$nextTick(() => this.$refs.chatInput?.focus());
        },

        clearChat() {
            this.messages = [];
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        formatMessage(text) {
            if (!text) return '';
            
            // Escape HTML
            let html = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            
            // Bold: **text**
            html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            
            // Italic: *text*
            html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
            
            // Bullet points: lines starting with - or •
            html = html.replace(/^[\-•]\s+(.+)/gm, '<li>$1</li>');
            
            // Wrap consecutive <li> in <ul>
            html = html.replace(/((<li>.*<\/li>\n?)+)/g, '<ul>$1</ul>');
            
            // Numbered lists: 1. text
            html = html.replace(/^\d+\.\s+(.+)/gm, '<li>$1</li>');
            
            // Line breaks
            html = html.replace(/\n\n/g, '</p><p>');
            html = html.replace(/\n/g, '<br>');
            
            // Wrap in paragraph if not already
            if (!html.startsWith('<')) {
                html = '<p>' + html + '</p>';
            }
            
            return html;
        },
    }
}
</script>
