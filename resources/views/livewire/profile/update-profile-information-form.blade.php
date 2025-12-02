<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
    <header class="px-8 py-8 bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-xl backdrop-blur-sm">
                <i class="text-2xl text-white fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">
                    {{ __('Profile Information') }}
                </h2>
                <p class="mt-1 text-indigo-100">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
            </div>
        </div>
    </header>

    <div class="p-8">
        <form wire:submit="updateProfileInformation" class="space-y-8">
            <!-- Name Field -->
            <div class="form-group">
                <x-input-label for="name" :value="__('Full Name')"
                    class="block mb-3 text-sm font-semibold text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-gray-400 fas fa-user"></i>
                    </div>
                    <x-text-input wire:model="name" id="name" name="name" type="text"
                        class="w-full py-4 pl-12 pr-4 text-sm transition-all duration-200 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required autofocus autocomplete="name" placeholder="Enter your full name" />
                </div>
                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('name')" />
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Email Address')"
                    class="block mb-3 text-sm font-semibold text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-gray-400 fas fa-envelope"></i>
                    </div>
                    <x-text-input wire:model="email" id="email" name="email" type="email"
                        class="w-full py-4 pl-12 pr-4 text-sm transition-all duration-200 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required autocomplete="username" placeholder="Enter your email address" />
                </div>
                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('email')" />

                <!-- Email Verification Notice -->
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="p-4 mt-4 border bg-amber-50 border-amber-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="text-lg fas fa-exclamation-triangle text-amber-500"></i>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-amber-800">
                                    {{ __('Your email address is unverified.') }}
                                </p>
                                <button wire:click.prevent="sendVerification"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 rounded-lg bg-amber-600 hover:bg-amber-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                    <i class="mr-2 fas fa-paper-plane"></i>
                                    {{ __('Send Verification Email') }}
                                </button>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="p-3 mt-3 border border-green-200 rounded-lg bg-green-50">
                                        <div class="flex items-center space-x-2">
                                            <i class="text-green-500 fas fa-check-circle"></i>
                                            <p class="text-sm font-medium text-green-800">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <x-primary-button
                        class="inline-flex items-center px-8 py-4 font-semibold text-white transition-all duration-200 bg-indigo-600 shadow-lg hover:bg-indigo-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-xl">
                        <i class="mr-2 fas fa-save"></i>
                        {{ __('Update Profile') }}
                    </x-primary-button>

                    <x-action-message class="text-sm font-medium" on="profile-updated">
                        <div
                            class="inline-flex items-center px-4 py-2 text-green-800 border border-green-200 rounded-lg bg-green-50">
                            <i class="mr-2 fas fa-check-circle"></i>
                            {{ __('Profile updated successfully!') }}
                        </div>
                    </x-action-message>
                </div>
            </div>
        </form>
    </div>

    <!-- Additional Profile Options -->
    <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
        <h4 class="mb-4 text-sm font-semibold text-gray-900">Profile Tips</h4>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-sm text-green-500 fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Keep your information current</p>
                    <p class="mt-1 text-xs text-gray-500">Regular updates help maintain account security</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-sm text-blue-500 fas fa-shield-alt"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Verify your email</p>
                    <p class="mt-1 text-xs text-gray-500">Email verification adds an extra layer of security</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced form styling */
        .form-group input:focus,
        .form-group textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Icon styling */
        .form-group .fas {
            transition: color 0.2s ease-in-out;
        }

        .form-group input:focus+.fas,
        .form-group textarea:focus+.fas {
            color: #6366f1;
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        /* Backdrop blur effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Animation for success messages */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        /* Loading state for form submission */
        .form-submitting {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Custom focus ring */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</section>
