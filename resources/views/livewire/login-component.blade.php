<div class="min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-sm bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">Login</h2>

        <form wire:submit.prevent="login">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" wire:model="email" class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" wire:model="password" class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline relative">
                    <span wire:target="login">Login</span>
                    <svg wire:loading wire:target="login" class="animate-spin h-5 w-5  text-white absolute right-3 top-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3.6a4.4 4.4 0 00-4.4 4.4H4z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
