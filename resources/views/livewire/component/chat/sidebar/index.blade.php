<div
    x-data="{ showNewChatModal: false }"
    x-on:close-new-chat-modal.window="showNewChatModal = false"
    class="h-full flex flex-col bg-dark-garnet-100 border-r border-rust-brown-300/40 text-slate-200"
>
    <!-- Sidebar Header -->
    <div class="p-4 border-b border-rust-brown-300/40 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-ochre-500 to-rust-brown-600 flex items-center justify-center text-bright-lemon-900 font-bold shadow-md shadow-ochre-600/30">
                <svg class="w-5 h-5 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-base text-white tracking-wide">Direct Messages</h1>
                <p class="text-xs text-saffron-700 font-medium">1-on-1 Chat</p>
            </div>
        </div>
        <button
            @click="sidebarOpen = false"
            class="md:hidden p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-dark-garnet-200 transition cursor-pointer"
            title="Close menu"
        >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Top Action Bar: "+ New Chat" Button -->
    <div class="p-3 border-b border-rust-brown-300/40 space-y-2">
        <button
            type="button"
            @click="showNewChatModal = true"
            class="w-full py-2.5 px-4 bg-gradient-to-r from-rust-brown-600 to-ochre-500 hover:from-rust-brown-500 hover:to-ochre-400 active:from-rust-brown-700 active:to-ochre-600 text-white text-xs font-semibold rounded-xl shadow-lg shadow-ochre-600/30 flex items-center justify-center space-x-2 transition cursor-pointer"
        >
            <svg class="w-4 h-4 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ New Chat</span>
        </button>

        <!-- Search Input -->
        <div class="relative">
            <input
                wire:model.live.debounce.200ms="search"
                type="text"
                placeholder="Search conversations..."
                class="w-full pl-9 pr-3 py-2 bg-dark-garnet-200 border border-rust-brown-300/40 rounded-xl text-xs text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-saffron-500 transition"
            >
            <svg class="w-4 h-4 absolute left-3 top-2.5 text-ochre-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- New Chat Modal / Popover -->
    <div
        x-show="showNewChatModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark-garnet-100/90 backdrop-blur-md"
        style="display: none;"
    >
        <div class="bg-dark-garnet-200 border border-rust-brown-300/60 rounded-2xl p-5 w-full max-w-sm shadow-2xl space-y-4" @click.away="showNewChatModal = false">
            <div class="flex items-center justify-between border-b border-rust-brown-300/40 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center space-x-2">
                    <svg class="w-4 h-4 text-saffron-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Start a New 1-on-1 Chat</span>
                </h3>
                <button @click="showNewChatModal = false" class="text-slate-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="startNewChat" class="space-y-3">
                <div>
                    <label for="newChatInput" class="block text-xs font-semibold text-saffron-700 mb-1">
                        Enter Username or Email
                    </label>
                    <input
                        wire:model.defer="newChatInput"
                        id="newChatInput"
                        type="text"
                        placeholder="e.g. Wumpus or wumpus@example.com"
                        class="w-full px-3.5 py-2.5 bg-dark-garnet-100 border border-rust-brown-300/50 rounded-xl text-xs text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-saffron-500"
                        autofocus
                    >
                    @error('newChatInput')
                        <p class="mt-1 text-xs text-bright-lemon-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <button
                        type="button"
                        @click="showNewChatModal = false"
                        class="flex-1 py-2 px-3 bg-dark-garnet-300 hover:bg-rust-brown-300/40 text-slate-300 text-xs font-semibold rounded-xl transition cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 py-2 px-3 bg-gradient-to-r from-rust-brown-600 to-ochre-500 hover:from-rust-brown-500 hover:to-ochre-400 text-white text-xs font-semibold rounded-xl transition shadow-md shadow-ochre-600/30 cursor-pointer"
                    >
                        Start Chat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scrollable Conversations List -->
    <div class="flex-1 overflow-y-auto px-2 py-3 space-y-4">
        <div>
            <div class="px-3 mb-2 flex items-center justify-between text-[11px] font-semibold text-ochre-700 uppercase tracking-wider">
                <span>Conversations</span>
                <span class="bg-rust-brown-300/40 px-1.5 py-0.5 rounded text-[10px] text-saffron-700 font-bold">{{ count($userConversations) }}</span>
            </div>

            @if (count($userConversations) > 0)
                <div class="space-y-1">
                    @foreach ($userConversations as $dm)
                        @php
                            $contact = $dm->users->firstWhere('id', '!=', $user?->id) ?? $dm->users->first();
                            $lastMsg = $dm->latestMessage;
                            $hasUnread = ($dm->unread_messages_count ?? 0) > 0 && $activeConversationId !== $dm->id;
                        @endphp
                        <button
                            wire:key="conv-{{ $dm->id }}"
                            wire:click="selectConversation({{ $dm->id }})"
                            @click="sidebarOpen = false"
                            @class([
                                'w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition cursor-pointer text-left relative',
                                'bg-rust-brown-400/30 border border-ochre-500/40 text-saffron-700 font-medium shadow-sm' => $activeConversationId === $dm->id,
                                'bg-saffron-500/15 border-l-4 border-l-saffron-500 border-y border-r border-saffron-500/40 text-white font-bold shadow-md shadow-saffron-500/10' => $hasUnread,
                                'hover:bg-dark-garnet-200 text-slate-300 hover:text-white' => $activeConversationId !== $dm->id && !$hasUnread,
                            ])
                        >
                            <div class="flex items-center space-x-3 truncate">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $contact?->avatar_url ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=User' }}" alt="{{ $contact?->name }}" class="w-8.5 h-8.5 rounded-full bg-dark-garnet-300 object-cover border border-rust-brown-300/60">
                                    @if ($contact?->is_online)
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full shadow"></span>
                                    @else
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-slate-600 border-2 border-dark-garnet-100 rounded-full"></span>
                                    @endif
                                </div>
                                <div class="truncate">
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <span @class([
                                            'text-xs truncate',
                                            'font-bold text-bright-lemon' => $hasUnread,
                                            'font-semibold text-white' => !$hasUnread,
                                        ])>{{ $contact?->name ?? 'Contact' }}</span>
                                        <span class="text-[10px] text-saffron-700 font-mono truncate">@<span>{{ Str::slug($contact?->name, '') }}</span></span>
                                    </div>
                                    <div @class([
                                        'text-[11px] truncate',
                                        'text-slate-100 font-semibold' => $hasUnread,
                                        'text-ochre-800' => !$hasUnread,
                                    ])>
                                        {{ $lastMsg ? Str::limit($lastMsg->body, 28) : 'No messages yet' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-2 flex-shrink-0">
                                @if ($hasUnread)
                                    <span class="w-2.5 h-2.5 rounded-full bg-bright-lemon animate-pulse shadow-sm shadow-bright-lemon" title="Unread Message"></span>
                                @endif
                                <div class="text-[10px] text-ochre-700 whitespace-nowrap">
                                    {{ $lastMsg ? $lastMsg->created_at->format('g:i A') : '' }}
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <!-- No Conversations Empty State -->
                <div class="p-6 text-center text-slate-400 space-y-3 bg-dark-garnet-200/50 border border-dashed border-rust-brown-300/40 rounded-2xl mx-1 my-4">
                    <div class="w-10 h-10 rounded-full bg-rust-brown-300/30 flex items-center justify-center mx-auto text-saffron-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-saffron-700">No conversations yet</div>
                    <p class="text-[11px] text-slate-400 leading-normal">
                        Click <span class="text-saffron-600 font-medium">+ New Chat</span> above to search and message a user by username or email.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Current User Profile Footer -->
    @if ($user)
        <div class="p-3 border-t border-rust-brown-300/40 bg-dark-garnet-200/90 flex items-center justify-between">
            <div class="flex items-center space-x-3 truncate">
                <div class="relative flex-shrink-0">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full bg-dark-garnet-300 object-cover border border-ochre-500/60 shadow">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full"></span>
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-white truncate leading-tight">{{ $user->name }}</div>
                    <div class="text-[11px] font-mono text-bright-lemon-600 truncate">@<span>{{ Str::slug($user->name, '') }}</span></div>
                    <div class="text-[10px] text-slate-400 truncate">{{ $user->email }}</div>
                </div>
            </div>
            <button
                wire:click="logout"
                class="p-2 text-slate-400 hover:text-bright-lemon-600 hover:bg-dark-garnet-300 rounded-xl transition cursor-pointer flex-shrink-0 ml-2"
                title="Sign Out"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>
    @endif
</div>
