<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
    <header class="px-8 py-8 bg-gradient-to-r from-emerald-500 to-teal-600">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-xl backdrop-blur-sm">
                <i class="text-2xl text-white fas fa-shield-alt"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">
                    {{ __('Update Password') }}
                </h2>
                <p class="mt-1 text-emerald-100">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </div>
        </div>
    </header>

    <div class="p-8">
        <form wire:submit="updatePassword" class="space-y-8">
            <!-- Current Password -->
            <div class="form-group">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="block mb-3 text-sm font-semibold text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-gray-400 fas fa-lock"></i>
                    </div>
                    <x-text-input
                        wire:model="current_password"
                        id="update_password_current_password"
                        name="current_password"
                        type="password"
                        class="w-full py-4 pl-12 pr-4 text-sm transition-all duration-200 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        autocomplete="current-password"
                        placeholder="Enter your current password" />
                    <button type="button" onclick="togglePassword('update_password_current_password')" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <i class="text-gray-400 transition-colors fas fa-eye hover:text-gray-600" id="update_password_current_password_toggle"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- New Password -->
            <div class="form-group">
                <x-input-label for="update_password_password" :value="__('New Password')" class="block mb-3 text-sm font-semibold text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-gray-400 fas fa-key"></i>
                    </div>
                    <x-text-input
                        wire:model="password"
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="w-full py-4 pl-12 pr-4 text-sm transition-all duration-200 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        autocomplete="new-password"
                        placeholder="Create a strong new password"
                        oninput="checkPasswordStrength(this.value)" />
                    <button type="button" onclick="togglePassword('update_password_password')" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <i class="text-gray-400 transition-colors fas fa-eye hover:text-gray-600" id="update_password_password_toggle"></i>
                    </button>
                </div>

                <!-- Password Strength Indicator -->
                <div class="mt-2">
                    <div class="flex items-center mb-2 space-x-2">
                        <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                        <div class="flex space-x-1">
                            <div class="w-6 h-2 bg-gray-200 rounded-full" id="strength-1"></div>
                            <div class="w-6 h-2 bg-gray-200 rounded-full" id="strength-2"></div>
                            <div class="w-6 h-2 bg-gray-200 rounded-full" id="strength-3"></div>
                            <div class="w-6 h-2 bg-gray-200 rounded-full" id="strength-4"></div>
                        </div>
                        <span class="text-xs font-medium" id="strength-text">Weak</span>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="block mb-3 text-sm font-semibold text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-gray-400 fas fa-check-circle"></i>
                    </div>
                    <x-text-input
                        wire:model="password_confirmation"
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="w-full py-4 pl-12 pr-4 text-sm transition-all duration-200 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        autocomplete="new-password"
                        placeholder="Confirm your new password" />
                    <button type="button" onclick="togglePassword('update_password_password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <i class="text-gray-400 transition-colors fas fa-eye hover:text-gray-600" id="update_password_password_confirmation_toggle"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Action Buttons -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <x-primary-button class="inline-flex items-center px-8 py-4 font-semibold text-white transition-all duration-200 shadow-lg bg-emerald-600 hover:bg-emerald-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 hover:shadow-xl">
                        <i class="mr-2 fas fa-key"></i>
                        {{ __('Update Password') }}
                    </x-primary-button>

                    <x-action-message class="text-sm font-medium" on="password-updated">
                        <div class="inline-flex items-center px-4 py-2 text-green-800 border border-green-200 rounded-lg bg-green-50">
                            <i class="mr-2 fas fa-check-circle"></i>
                            {{ __('Password updated successfully!') }}
                        </div>
                    </x-action-message>
                </div>
            </div>
        </form>
    </div>

    <!-- Security Recommendations -->
    <div class="px-8 py-6 border-t bg-emerald-50 border-emerald-200">
        <h4 class="mb-4 text-lg font-semibold text-emerald-900">Security Recommendations</h4>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-lg fas fa-check-circle text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">Use a strong password</p>
                    <p class="mt-1 text-xs text-emerald-600">At least 8 characters with mixed case, numbers, and symbols</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-lg fas fa-check-circle text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">Avoid common passwords</p>
                    <p class="mt-1 text-xs text-emerald-600">Don't use easily guessable information like birthdays</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-lg fas fa-check-circle text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">Regular updates</p>
                    <p class="mt-1 text-xs text-emerald-600">Change your password every 3-6 months</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="mt-1 text-lg fas fa-check-circle text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">Unique passwords</p>
                    <p class="mt-1 text-xs text-emerald-600">Use different passwords for different accounts</p>
                </div>
            </div>
        </div>
    </div>
    <script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(inputId + '_toggle');

    if (input.type === 'password') {
        input.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    const strengthBars = [
        document.getElementById('strength-1'),
        document.getElementById('strength-2'),
        document.getElementById('strength-3'),
        document.getElementById('strength-4')
    ];
    const strengthText = document.getElementById('strength-text');

    // Reset all bars
    strengthBars.forEach(bar => {
        bar.className = 'h-2 w-6 rounded-full bg-gray-200';
    });

    let strength = 0;
    let strengthLabel = 'Very Weak';
    let strengthColor = 'bg-red-500';

    // Check password criteria
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    // Update strength display
    if (strength >= 1) {
        strengthBars[0].className = 'h-2 w-6 rounded-full bg-red-500';
        strengthLabel = 'Weak';
    }
    if (strength >= 2) {
        strengthBars[1].className = 'h-2 w-6 rounded-full bg-orange-500';
        strengthLabel = 'Fair';
        strengthColor = 'text-orange-600';
    }
    if (strength >= 3) {
        strengthBars[2].className = 'h-2 w-6 rounded-full bg-yellow-500';
        strengthLabel = 'Good';
        strengthColor = 'text-yellow-600';
    }
    if (strength >= 4) {
        strengthBars[3].className = 'h-2 w-6 rounded-full bg-green-500';
        strengthLabel = 'Strong';
        strengthColor = 'text-green-600';
    }

    strengthText.textContent = strengthLabel;
    strengthText.className = `text-xs font-medium ${strengthColor}`;
}
</script>

<style>
    /* Enhanced form styling */
    .form-group input:focus {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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

    /* Password toggle button */
    .form-group button[type="button"] {
        transform: none;
    }

    .form-group button[type="button"]:hover {
        transform: none;
    }

    /* Strength indicator animations */
    .h-2 {
        transition: background-color 0.3s ease;
    }

    /* Focus states for password fields */
    input[type="password"]:focus,
    input[type="text"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
</style>
</section>


