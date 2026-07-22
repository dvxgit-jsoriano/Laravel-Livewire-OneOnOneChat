<div class="min-h-screen flex flex-col justify-center py-8 sm:px-6 lg:px-8 bg-dark-garnet-100 text-slate-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="flex justify-center items-center space-x-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-ochre-500 to-rust-brown-600 flex items-center justify-center text-white shadow-lg shadow-ochre-600/30">
                <svg class="w-7 h-7 text-bright-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white sm:text-3xl">
            Create an Account
        </h2>
        <p class="mt-1 text-center text-sm text-saffron-700 font-medium">
            Join the chat room and connect instantly
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-dark-garnet-200 border border-rust-brown-300/50 py-8 px-6 shadow-2xl rounded-3xl sm:px-10">
            <form wire:submit.prevent="register" class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-saffron-700">
                        Display Name
                    </label>
                    <div class="mt-1.5">
                        <input
                            wire:model.defer="name"
                            id="name"
                            type="text"
                            required
                            class="w-full px-4 py-3 bg-dark-garnet-100 border border-rust-brown-300/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-saffron-500 focus:border-transparent transition"
                            placeholder="Alex Smith"
                        >
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-bright-lemon-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

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
                            placeholder="alex@example.com"
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

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-saffron-700">
                        Confirm Password
                    </label>
                    <div class="mt-1.5">
                        <input
                            wire:model.defer="password_confirmation"
                            id="password_confirmation"
                            type="password"
                            required
                            class="w-full px-4 py-3 bg-dark-garnet-100 border border-rust-brown-300/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-saffron-500 focus:border-transparent transition"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-rust-brown-600 via-ochre-500 to-saffron-500 hover:from-rust-brown-500 hover:to-ochre-400 shadow-lg shadow-ochre-600/30 focus:outline-none focus:ring-2 focus:ring-saffron-500 transition cursor-pointer"
                    >
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400">
                Already registered?
                <a href="{{ route('login') }}" class="font-semibold text-saffron-600 hover:text-saffron-500 underline">
                    Sign in here
                </a>
            </div>
        </div>
    </div>
</div>
