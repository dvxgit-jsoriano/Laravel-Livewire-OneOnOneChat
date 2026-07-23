<div
    x-data="{
        scrollToBottom(smooth = false) {
            this.$nextTick(() => {
                const el = this.$refs.scrollContainer;
                if (!el) return;
                try {
                    el.scrollTo({
                        top: el.scrollHeight,
                        behavior: smooth ? 'smooth' : 'instant'
                    });
                } catch (e) {
                    el.scrollTop = el.scrollHeight;
                }

                // Double check after layout paint / avatar image load
                setTimeout(() => {
                    if (el) el.scrollTop = el.scrollHeight;
                }, 50);
                setTimeout(() => {
                    if (el) el.scrollTop = el.scrollHeight;
                }, 150);
            });
        },
        initObserver() {
            const el = this.$refs.scrollContainer;
            if (!el) return;

            // Scroll immediately on init
            this.scrollToBottom(false);

            // Observe child list additions (new messages coming from Livewire or Reverb)
            const observer = new MutationObserver(() => {
                this.scrollToBottom(true);
            });

            observer.observe(el, { childList: true, subtree: true });
        }
    }"
    x-init="initObserver()"
    x-on:messageSent.window="scrollToBottom(true)"
    x-on:conversationSelected.window="scrollToBottom(false)"
    class="flex-1 flex flex-col h-full min-h-0 bg-[#1c0202] overflow-hidden"
>
    @php
        $currentUser = Auth::user();
        $otherUser = $conversation?->users->firstWhere('id', '!=', $currentUser?->id)
            ?? $conversation?->users->first();
    @endphp

    <!-- Header styled for 1-on-1 Chat -->
    <div class="h-14 px-4 bg-dark-garnet-100 border-b border-rust-brown-300/40 flex items-center justify-between shadow-sm z-10 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-dark-garnet-200 transition cursor-pointer"
                title="Toggle conversations"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            @if ($conversation && $otherUser)
                <div class="flex items-center space-x-2.5 text-slate-200">
                    <div class="relative flex-shrink-0">
                        <img
                            src="{{ $otherUser->avatar_url }}"
                            alt="{{ $otherUser->name }}"
                            class="w-8.5 h-8.5 rounded-full bg-dark-garnet-300 object-cover border border-rust-brown-300/60 shadow-sm"
                        >
                        @if ($otherUser->is_online)
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full"></span>
                        @endif
                    </div>
                    <div>
                        <div class="font-bold text-sm tracking-wide text-white flex items-center space-x-1">
                            <span>{{ $otherUser->name }}</span>
                        </div>
                        <div class="text-[10px] text-saffron-700 font-medium">
                            {{ $otherUser->is_online ? 'Online' : 'Offline' }}
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center space-x-2 text-slate-400 font-semibold text-sm">
                    <svg class="w-5 h-5 text-saffron-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Select or Start a Chat</span>
                </div>
            @endif
        </div>

        @if ($conversation)
            <button
                class="p-2 text-slate-400 hover:text-white bg-dark-garnet-200 hover:bg-rust-brown-300/30 rounded-full transition cursor-pointer border border-rust-brown-300/30"
                title="Search conversation"
            >
                <svg class="w-5 h-5 text-saffron-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Messages Container -->
    <div
        x-ref="scrollContainer"
        class="flex-1 overflow-y-auto p-4 space-y-4 bg-dark-garnet-100/90 min-h-0"
    >
        @if ($conversation && $messages)
            @forelse ($messages as $msg)
                @php
                    $isMe = (int) $msg->user_id === (int) $currentUser?->id;
                @endphp

                <div
                    wire:key="msg-{{ $msg->id }}"
                    @class([
                        'flex items-end space-x-2 max-w-[85%] sm:max-w-[75%]',
                        'ml-auto flex-row-reverse space-x-reverse justify-start' => $isMe,
                        'mr-auto flex-row justify-start' => !$isMe,
                    ])
                >
                    <!-- User Avatar -->
                    <div class="relative flex-shrink-0 mb-0.5">
                        <img
                            src="{{ $msg->user->avatar_url }}"
                            alt="{{ $msg->user->name }}"
                            class="w-8 h-8 rounded-full bg-dark-garnet-300 object-cover border border-rust-brown-300/60 shadow-sm"
                        >
                        @if ($msg->user->is_online)
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full"></span>
                        @endif
                    </div>

                    <!-- Message Body & Header -->
                    <div @class([
                        'flex flex-col min-w-0',
                        'items-end text-right' => $isMe,
                        'items-start text-left' => !$isMe,
                    ])>
                        <!-- Sender Name & Timestamp -->
                        <div @class([
                            'flex items-baseline space-x-2 mb-1 px-1',
                            'flex-row-reverse space-x-reverse' => $isMe,
                        ])>
                            <span class="font-semibold text-xs text-saffron-600">
                                {{ $isMe ? 'You' : $msg->user->name }}
                            </span>
                            <span class="text-[10px] text-ochre-700 font-medium">
                                {{ $msg->created_at->isToday() ? $msg->created_at->format('g:i A') : $msg->created_at->format('M d, g:i A') }}
                            </span>
                        </div>

                        <!-- Chat Bubble -->
                        <div @class([
                            'px-4 py-2.5 text-sm leading-relaxed break-words shadow-md',
                            'bg-gradient-to-r from-rust-brown-600 to-ochre-500 text-white rounded-2xl rounded-tr-none shadow-rust-brown-600/30' => $isMe,
                            'bg-dark-garnet-300 border border-rust-brown-300/50 text-slate-100 rounded-2xl rounded-tl-none' => !$isMe,
                        ])>
                            {!! nl2br(e($msg->body)) !!}
                        </div>

                        <!-- Sticker Badges -->
                        @if (str_contains(strtolower($msg->body), 'emoji') || str_contains($msg->body, '😂') || str_contains(strtolower($msg->body), 'bwhaahahah'))
                            <div @class([
                                'mt-1.5 flex items-center space-x-1.5 overflow-x-auto',
                                'justify-end' => $isMe,
                                'justify-start' => !$isMe,
                            ])>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-dark-garnet-200 border border-rust-brown-300/50 text-lg shadow-sm">
                                    🤖
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-dark-garnet-200 border border-rust-brown-300/50 text-lg shadow-sm">
                                    🐷
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-dark-garnet-200 border border-rust-brown-300/50 text-lg shadow-sm">
                                    🐍
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-dark-garnet-200 flex items-center justify-center mb-3 border border-rust-brown-300/40">
                        <img src="{{ $otherUser?->avatar_url ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=Chat' }}" class="w-10 h-10 rounded-full">
                    </div>
                    <h3 class="text-lg font-bold text-saffron-700">1-on-1 Chat with {{ $otherUser?->name ?? 'Contact' }}</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-xs">Send a text message to start your conversation.</p>
                </div>
            @endforelse
        @else
            <!-- No Conversation Selected Empty State -->
            <div class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400 space-y-3">
                <div class="w-16 h-16 rounded-full bg-dark-garnet-200 flex items-center justify-center text-saffron-600 border border-rust-brown-300/40 shadow-inner">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-saffron-700">No Conversation Selected</h3>
                <p class="text-xs text-slate-400 max-w-xs leading-relaxed">
                    Click <span class="text-saffron-600 font-semibold">+ New Chat</span> in the sidebar menu to find a user by username or email and start messaging!
                </p>
            </div>
        @endif
    </div>
</div>
