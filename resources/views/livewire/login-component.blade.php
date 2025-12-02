<div>
    <!-- Compact Login Component - Fits in 100vh -->
    <div
        class="relative flex items-center justify-center h-screen p-4 overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">

        <!-- Floating Background Elements -->
        <div class="absolute w-16 h-16 bg-blue-200 rounded-full top-10 left-10 opacity-20 animate-pulse"></div>
        <div class="absolute w-12 h-12 bg-indigo-200 rounded-full top-20 right-20 opacity-30 animate-bounce"></div>
        <div class="absolute w-10 h-10 bg-blue-300 rounded-full opacity-25 bottom-20 left-20"></div>
        <div class="absolute w-20 h-20 bg-indigo-100 rounded-full bottom-16 right-16 opacity-20"></div>

        <!-- Main Login Container -->
        <div
            class="relative z-10 w-full max-w-sm p-6 border shadow-2xl bg-white/90 backdrop-blur-sm rounded-2xl border-white/50">

            <!-- Header Section -->
            <div class="mb-6 text-center">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 mb-3 rounded-full shadow-lg bg-gradient-to-br from-blue-500 to-blue-600">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 6H4c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-8 2h6v2h-6V8zm-2 0v2H4V8h6zm6 6v2h-6v-2h6zm-8 0v2H4v-2h4z" />
                    </svg>
                </div>
                <h1 class="mb-1 text-2xl font-bold text-gray-800">Stamp Sale</h1>
                <p class="text-sm text-gray-600">Inventory Management</p>
            </div>

            <!-- Login Form -->
            <form wire:submit.prevent="login" class="space-y-4">
                <!-- Email Field -->
                <div>
                    <label for="email" class="flex items-center mb-1 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        Email
                    </label>
                    <input type="email" id="email" wire:model="email"
                        class="w-full px-3 py-2.5 bg-white border-2 border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300"
                        placeholder="Enter your email">
                    @error('email')
                        <div class="flex items-center mt-1 text-xs text-red-500">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="flex items-center mb-1 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                        </svg>
                        Password
                    </label>
                    <input type="password" id="password" wire:model="password"
                        class="w-full px-3 py-2.5 bg-white border-2 border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300"
                        placeholder="Enter your password">
                    @error('password')
                        <div class="flex items-center mt-1 text-xs text-red-500">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-800">Forgot?</a>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="relative w-full px-4 py-3 font-semibold text-white transition-all duration-300 transform rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-200">
                    <span wire:loading.remove wire:target="login" class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </span>

                    <span wire:loading wire:target="login" class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v3.6a4.4 4.4 0 00-4.4 4.4H4z"></path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-4 text-center">
                <div class="flex items-center justify-center mb-2 space-x-2 text-xs text-gray-500">
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                    </svg>
                    <span>Secure Login</span>
                </div>
                <p class="text-xs text-gray-400">© 2024 Stamp Sale Inventory</p>
            </div>
        </div>
    </div>

    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: none;
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }
    </style>
</div>
