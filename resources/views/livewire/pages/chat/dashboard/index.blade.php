<div
    x-data="{
        sidebarOpen: false,
        toasts: [],
        addToast(detail) {
            const id = Date.now() + Math.random();
            const toast = {
                id: id,
                conversation_id: detail.conversation_id,
                sender_name: detail.user ? detail.user.name : 'New Message',
                sender_avatar: detail.user ? detail.user.avatar : 'https://api.dicebear.com/7.x/bottts/svg?seed=User',
                body: detail.body || '',
                time: detail.created_at_human || 'Just now'
            };
            this.toasts.push(toast);

            // Auto-dismiss after 10 seconds
            setTimeout(() => {
                this.removeToast(id);
            }, 10000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        openToastChat(toast) {
            Livewire.dispatch('conversationSelected', { conversationId: toast.conversation_id });
            this.sidebarOpen = false;
            this.removeToast(toast.id);
        }
    }"
    x-on:new-message-toast.window="addToast($event.detail)"
    class="h-screen w-screen flex bg-dark-garnet-100 text-slate-100 overflow-hidden relative"
>
    <!-- Top Animated Banner Notification Stack (Mobile: Slide Down from Top | Desktop: Top Right Stack) -->
    <!-- Mobile Stack Container (Top Center Slide-Down) -->
    <div class="fixed top-3 inset-x-3 z-50 flex flex-col space-y-2 items-center pointer-events-none md:hidden">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="-translate-y-full opacity-0 scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="-translate-y-full opacity-0 scale-95"
                @click="openToastChat(toast)"
                class="pointer-events-auto w-full max-w-md bg-dark-garnet-200/95 backdrop-blur-md border border-ochre-500/50 rounded-2xl p-3 shadow-2xl flex items-center justify-between cursor-pointer hover:bg-dark-garnet-300 hover:border-saffron-500 transition group active:scale-98"
            >
                <div class="flex items-center space-x-3 truncate">
                    <div class="relative flex-shrink-0">
                        <img :src="toast.sender_avatar" :alt="toast.sender_name" class="w-9 h-9 rounded-full bg-dark-garnet-300 object-cover border border-ochre-500/60 shadow">
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full"></span>
                    </div>
                    <div class="truncate text-left">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-white truncate" x-text="toast.sender_name"></span>
                            <span class="text-[10px] text-saffron-600 font-medium" x-text="toast.time"></span>
                        </div>
                        <p class="text-[11px] text-slate-300 truncate font-normal" x-text="toast.body"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-1 pl-2">
                    <span class="p-1 text-slate-400 group-hover:text-saffron-600 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <button
                        @click.stop="removeToast(toast.id)"
                        class="p-1 text-slate-400 hover:text-white rounded-lg hover:bg-dark-garnet-300 transition"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Desktop Stack Container (Top Right Stack) -->
    <div class="hidden md:flex fixed top-4 right-4 z-50 flex-col space-y-2 items-end pointer-events-none max-w-sm w-full">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0"
                @click="openToastChat(toast)"
                class="pointer-events-auto w-full bg-dark-garnet-200/95 backdrop-blur-md border border-ochre-500/50 rounded-2xl p-3.5 shadow-2xl flex items-center justify-between cursor-pointer hover:bg-dark-garnet-300 hover:border-saffron-500 transition group"
            >
                <div class="flex items-center space-x-3 truncate">
                    <div class="relative flex-shrink-0">
                        <img :src="toast.sender_avatar" :alt="toast.sender_name" class="w-10 h-10 rounded-full bg-dark-garnet-300 object-cover border border-ochre-500/60 shadow">
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-saffron-500 border-2 border-dark-garnet-100 rounded-full"></span>
                    </div>
                    <div class="truncate text-left">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-white truncate" x-text="toast.sender_name"></span>
                            <span class="text-[10px] text-saffron-600 font-medium" x-text="toast.time"></span>
                        </div>
                        <p class="text-xs text-slate-300 truncate font-normal mt-0.5" x-text="toast.body"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-1 pl-2">
                    <button
                        @click.stop="removeToast(toast.id)"
                        class="p-1 text-slate-400 hover:text-white rounded-lg hover:bg-dark-garnet-300 transition"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Collapsible Sidebar (Mobile Drawer + Desktop Fixed Side Panel) -->
    <div
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-40 w-80 bg-dark-garnet-100 transition-transform duration-300 ease-in-out md:static md:z-auto flex-shrink-0 shadow-2xl md:shadow-none"
    >
        @livewire('chat.sidebar', ['activeConversationId' => $activeConversationId], key('sidebar-'.$activeConversationId))
    </div>

    <!-- Backdrop Overlay for Mobile Sidebar -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-dark-garnet-100/80 backdrop-blur-sm z-30 md:hidden"
    ></div>

    <!-- Main Chat Workspace View -->
    <div class="flex-1 flex flex-col h-full min-w-0 bg-[#1c0202]">
        <!-- Message Feed -->
        <div class="flex-1 min-h-0">
            @livewire('chat.message-feed', ['conversationId' => $activeConversationId], key('feed-'.$activeConversationId))
        </div>

        <!-- Text-Only Message Input Bar -->
        <div class="flex-shrink-0">
            @livewire('chat.message-input', ['conversationId' => $activeConversationId], key('input-'.$activeConversationId))
        </div>
    </div>
</div>
