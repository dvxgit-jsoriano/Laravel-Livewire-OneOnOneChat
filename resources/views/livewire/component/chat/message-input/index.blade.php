<div
    x-data="{
        drawerOpen: false,
        activeTab: 'Emoji',
        notice: '',
        showNotice(msg) {
            this.notice = msg;
            setTimeout(() => this.notice = '', 3000);
        }
    }"
    class="bg-dark-garnet-100 border-t border-rust-brown-300/40 relative"
>
    <!-- Text-Only Notice Alert -->
    <div
        x-show="notice"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-ochre-500 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg border border-saffron-500 z-50 flex items-center space-x-1.5"
    >
        <svg class="w-4 h-4 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="notice"></span>
    </div>

    <!-- Main Toolbar and Input Row -->
    <div class="p-3 max-w-4xl mx-auto flex items-center space-x-2">
        <!-- Plus Button (Text-Only Notice) -->
        <button
            type="button"
            @click="showNotice('Text-only chat enabled. File uploads disabled.')"
            :disabled="!{{ $conversationId ? 'true' : 'false' }}"
            class="p-2 text-ochre-700 hover:text-saffron-600 bg-dark-garnet-200 hover:bg-rust-brown-300/30 disabled:opacity-50 disabled:cursor-not-allowed rounded-full border border-rust-brown-300/40 transition flex-shrink-0 cursor-pointer"
            title="Add media (Disabled)"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>

        <!-- Grid Icon Button -->
        <button
            type="button"
            @click="if ({{ $conversationId ? 'true' : 'false' }}) drawerOpen = !drawerOpen"
            :disabled="!{{ $conversationId ? 'true' : 'false' }}"
            class="p-2 text-ochre-700 hover:text-saffron-600 bg-dark-garnet-200 hover:bg-rust-brown-300/30 disabled:opacity-50 disabled:cursor-not-allowed rounded-full border border-rust-brown-300/40 transition flex-shrink-0 cursor-pointer"
            title="Stickers and Apps"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </button>

        <!-- Input Box Form -->
        <form wire:submit.prevent="sendMessage" class="flex-1 flex items-center relative">
            <input
                wire:model.defer="body"
                type="text"
                :disabled="!{{ $conversationId ? 'true' : 'false' }}"
                placeholder="{{ $conversationId ? ($otherUser ? 'Message @' . $otherUser->name : 'Message contact...') : 'Select or start a chat to message...' }}"
                class="w-full pl-4 pr-10 py-2.5 bg-dark-garnet-200 border border-rust-brown-300/50 rounded-2xl text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-ochre-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition"
                @keydown.enter="if (!$event.shiftKey && {{ $conversationId ? 'true' : 'false' }}) { $wire.sendMessage(); $event.preventDefault(); }"
            >

            <button
                type="button"
                @click="if ({{ $conversationId ? 'true' : 'false' }}) drawerOpen = !drawerOpen"
                :disabled="!{{ $conversationId ? 'true' : 'false' }}"
                class="absolute right-3 text-ochre-700 hover:text-saffron-600 disabled:opacity-50 transition cursor-pointer"
                title="Keyboard & Emoji"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </form>

        <!-- Send Button -->
        <button
            type="button"
            wire:click="sendMessage"
            :disabled="!{{ $conversationId ? 'true' : 'false' }}"
            class="p-2.5 text-white bg-gradient-to-r from-rust-brown-600 to-ochre-500 hover:from-rust-brown-500 hover:to-ochre-400 active:from-rust-brown-700 active:to-ochre-600 disabled:opacity-50 disabled:cursor-not-allowed rounded-2xl shadow-lg shadow-ochre-600/30 transition flex-shrink-0 cursor-pointer"
            title="Send Message"
        >
            <svg class="w-5 h-5 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
        </button>
    </div>

    <!-- Collapsible Emoji / GIFs / Stickers Drawer -->
    <div
        x-show="drawerOpen && {{ $conversationId ? 'true' : 'false' }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4"
        class="bg-dark-garnet-200 border-t border-rust-brown-300/50 p-4 space-y-4 shadow-2xl max-w-4xl mx-auto rounded-t-3xl"
    >
        <!-- Drag pill -->
        <div class="w-10 h-1 bg-rust-brown-300/50 rounded-full mx-auto cursor-pointer" @click="drawerOpen = false"></div>

        <!-- Tab Selector Bar -->
        <div class="flex items-center justify-between bg-dark-garnet-100 p-1 rounded-2xl border border-rust-brown-300/40 text-xs font-semibold">
            <button
                type="button"
                @click="activeTab = 'Emoji'"
                :class="activeTab === 'Emoji' ? 'bg-rust-brown-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                class="flex-1 py-2 rounded-xl transition cursor-pointer text-center"
            >
                Emoji
            </button>
            <button
                type="button"
                @click="activeTab = 'GIFs'"
                :class="activeTab === 'GIFs' ? 'bg-rust-brown-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                class="flex-1 py-2 rounded-xl transition cursor-pointer text-center"
            >
                GIFs
            </button>
            <button
                type="button"
                @click="activeTab = 'Stickers'"
                :class="activeTab === 'Stickers' ? 'bg-rust-brown-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                class="flex-1 py-2 rounded-xl transition cursor-pointer text-center"
            >
                Stickers
            </button>
        </div>

        <!-- Search Input Box -->
        <div class="relative">
            <input
                type="text"
                placeholder="Find the perfect emoji"
                class="w-full pl-9 pr-4 py-2.5 bg-dark-garnet-100 border border-rust-brown-300/40 rounded-2xl text-xs text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-saffron-500 transition"
            >
            <svg class="w-4 h-4 absolute left-3 top-3 text-ochre-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- Frequently Used Section -->
        <div>
            <div class="text-[11px] font-semibold text-ochre-700 mb-2 uppercase tracking-wider">
                Frequently Used
            </div>
            <div class="grid grid-cols-7 gap-2">
                @foreach (['😂', '🤖', '🐷', '🐍', '🚀', '🔥', '💙', '🎉', '👍', '😎', '💬', '✨', '⚡', '💯'] as $emoji)
                    <button
                        type="button"
                        wire:click="appendEmoji('{{ $emoji }}')"
                        class="p-2.5 bg-dark-garnet-100 hover:bg-rust-brown-600/40 border border-rust-brown-300/40 rounded-2xl text-xl flex items-center justify-center transition cursor-pointer transform active:scale-95"
                    >
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
