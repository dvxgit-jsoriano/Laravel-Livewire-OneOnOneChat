<div class="min-h-screen flex flex-col justify-center py-8 sm:px-6 lg:px-8 bg-dark-garnet-100 text-slate-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="flex justify-center items-center space-x-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-ochre-500 to-rust-brown-600 flex items-center justify-center text-white shadow-lg shadow-ochre-600/30">
                <svg class="w-7 h-7 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white sm:text-3xl">
            Welcome back
        </h2>
        <p class="mt-1 text-center text-sm text-saffron-700 font-medium">
            Sign in to start chatting with your contacts
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-dark-garnet-200 border border-rust-brown-300/50 py-8 px-6 shadow-2xl rounded-3xl sm:px-10">
            <form wire:submit.prevent="login" class="space-y-5">
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-saffron-700">
                        Email Address
                    </label>
                    <div class="mt-1.5">
                        <input
                            wire:model.defer="email"
                            id="email"
                            type="email"
                            required
                            class="w-full px-4 py-3 bg-dark-garnet-100 border border-rust-brown-300/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-saffron-500 focus:border-transparent transition"
                            placeholder="user@example.com"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-bright-lemon-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-saffron-700">
                        Password
                    </label>
                    <div class="mt-1.5">
                        <input
                            wire:model.defer="password"
                            id="password"
                            type="password"
                            required
                            class="w-full px-4 py-3 bg-dark-garnet-100 border border-rust-brown-300/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-saffron-500 focus:border-transparent transition"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-bright-lemon-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input wire:model="remember" type="checkbox" class="rounded border-rust-brown-300 bg-dark-garnet-100 text-ochre-500 focus:ring-saffron-500">
                        <span class="text-xs text-slate-400">Remember me</span>
                    </label>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-rust-brown-600 via-ochre-500 to-saffron-500 hover:from-rust-brown-500 hover:to-ochre-400 shadow-lg shadow-ochre-600/30 focus:outline-none focus:ring-2 focus:ring-saffron-500 transition cursor-pointer"
                    >
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-saffron-600 hover:text-saffron-500 underline">
                    Register here
                </a>
            </div>

            <div class="mt-6 pt-4 border-t border-rust-brown-300/40 text-center text-xs text-slate-400">
                Demo User: <span class="text-saffron-600 font-mono">user@example.com</span> / <span class="text-saffron-600 font-mono">password</span>
            </div>
        </div>
    </div>
</div>
