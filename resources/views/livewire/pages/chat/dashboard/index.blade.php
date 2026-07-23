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

            // Play Forest Drop Sound Tone for incoming chat message
            this.playForestDropSound();

            // Auto-dismiss after 10 seconds
            setTimeout(() => {
                this.removeToast(id);
            }, 10000);
        },
        playForestDropSound() {
            try {
                const audio = new Audio('/sounds/forest-drop.wav');
                audio.volume = 0.75;
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.catch(() => {
                        this.synthForestDrop();
                    });
                }
            } catch (e) {
                this.synthForestDrop();
            }
        },
        synthForestDrop() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                if (ctx.state === 'suspended') ctx.resume();

                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.type = 'sine';
                osc.frequency.setValueAtTime(1450, now);
                osc.frequency.exponentialRampToValueAtTime(380, now + 0.15);

                gain.gain.setValueAtTime(0.001, now);
                gain.gain.linearRampToValueAtTime(0.6, now + 0.005);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(now);
                osc.stop(now + 0.36);
            } catch (err) {
                console.error(err);
            }
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        openToastChat(toast) {
            Livewire.dispatch('conversationSelected', { conversationId: toast.conversation_id });
            this.sidebarOpen = false;
            this.removeToast(toast.id);
        },
        getDepth(toast) {
            const idx = this.toasts.findIndex(t => t.id === toast.id);
            if (idx === -1) return 0;
            return (this.toasts.length - 1) - idx;
        },
        getToastStyle(toast) {
            const depth = this.getDepth(toast);
            if (depth > 3) {
                return 'transform: translateY(-26px) scale(0.82); transform-origin: top center; opacity: 0; pointer-events: none; z-index: 10; position: absolute; top: 0; left: 0; width: 100%; box-sizing: border-box;';
            }
            const translateY = -depth * 8;
            const scale = 1 - (depth * 0.045);
            const opacity = 1 - (depth * 0.12);
            const zIndex = 50 - depth;
            return `transform: translateY(${translateY}px) scale(${scale}); transform-origin: top center; opacity: ${opacity}; z-index: ${zIndex}; position: absolute; top: 0; left: 0; width: 100%; box-sizing: border-box;`;
        }
    }"
    x-on:new-message-toast.window="addToast($event.detail)"
    class="h-full h-[100dvh] w-full flex bg-dark-garnet-100 text-slate-100 overflow-hidden relative"
>
    <!-- Top Animated Banner Notification Stack (Mobile: Slide Down from Top | Desktop: Top Right Stack) -->
    <!-- Mobile Stackable Deck Container (Fixed Top Center Anchor) -->
    <div class="fixed top-6 inset-x-3 z-50 pointer-events-none md:hidden flex justify-center">
        <div class="relative w-full max-w-md h-20">
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    :style="getToastStyle(toast)"
                    x-transition:enter="transition-all transform ease-out duration-300"
                    x-transition:enter-start="-translate-y-6 opacity-0 scale-90"
                    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                    x-transition:leave="transition-all transform ease-in duration-200"
                    x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                    x-transition:leave-end="-translate-y-6 opacity-0 scale-90"
                    @click="openToastChat(toast)"
                    class="pointer-events-auto w-full bg-dark-garnet-200/98 backdrop-blur-md border border-ochre-500/60 rounded-2xl p-3 shadow-2xl flex items-center justify-between cursor-pointer hover:bg-dark-garnet-300 hover:border-saffron-500 transition-all duration-300 ease-out group active:scale-98"
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
    </div>

    <!-- Desktop Stackable Deck Container (Fixed Top Right Anchor) -->
    <div class="hidden md:flex fixed top-6 right-4 z-50 pointer-events-none justify-end">
        <div class="relative w-80 sm:w-96 h-20">
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    :style="getToastStyle(toast)"
                    x-transition:enter="transition-all transform ease-out duration-300"
                    x-transition:enter-start="translate-x-full opacity-0 scale-90"
                    x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                    x-transition:leave="transition-all transform ease-in duration-200"
                    x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                    x-transition:leave-end="translate-x-full opacity-0 scale-90"
                    @click="openToastChat(toast)"
                    class="pointer-events-auto w-full bg-dark-garnet-200/98 backdrop-blur-md border border-ochre-500/60 rounded-2xl p-3.5 shadow-2xl flex items-center justify-between cursor-pointer hover:bg-dark-garnet-300 hover:border-saffron-500 transition-all duration-300 ease-out group"
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
    <div class="flex-1 flex flex-col h-full min-h-0 min-w-0 bg-[#1c0202]">
        <!-- Message Feed -->
        <div class="flex-1 min-h-0 flex flex-col">
            @livewire('chat.message-feed', ['conversationId' => $activeConversationId], key('feed-'.$activeConversationId))
        </div>

        <!-- Text-Only Message Input Bar -->
        <div class="flex-shrink-0 z-20">
            @livewire('chat.message-input', ['conversationId' => $activeConversationId], key('input-'.$activeConversationId))
        </div>
    </div>
</div>
