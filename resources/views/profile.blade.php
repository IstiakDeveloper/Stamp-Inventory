<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900">
                    Account Settings
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Manage your account information, security settings, and preferences.
                </p>
            </div>
            <div class="items-center hidden space-x-2 sm:flex">
                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                <span class="text-sm text-gray-600">Account Active</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <!-- Navigation Tabs -->
            <div class="mb-8">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <a href="#profile" onclick="showTab('profile')" class="px-1 py-2 text-sm font-medium text-indigo-600 border-b-2 border-indigo-500 tab-link whitespace-nowrap" id="profile-tab">
                        <i class="mr-2 fas fa-user"></i>
                        Profile Information
                    </a>
                    <a href="#security" onclick="showTab('security')" class="px-1 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-link hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" id="security-tab">
                        <i class="mr-2 fas fa-shield-alt"></i>
                        Security Settings
                    </a>
                    <a href="#danger" onclick="showTab('danger')" class="px-1 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-link hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" id="danger-tab">
                        <i class="mr-2 fas fa-exclamation-triangle"></i>
                        Account Management
                    </a>
                </nav>
            </div>

            <div class="space-y-8">

                <!-- Profile Information Tab -->
                <div id="profile-content" class="tab-content">
                    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl sm:rounded-2xl">
                        <!-- Header -->
                        <div class="px-6 py-8 bg-gradient-to-r from-indigo-500 to-purple-600">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm">
                                    <i class="text-2xl text-white fas fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Profile Information</h3>
                                    <p class="mt-1 text-indigo-100">Update your account's profile information and email address.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <div class="max-w-2xl">
                                <livewire:profile.update-profile-information-form />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings Tab -->
                <div id="security-content" class="hidden tab-content">
                    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl sm:rounded-2xl">
                        <!-- Header -->
                        <div class="px-6 py-8 bg-gradient-to-r from-emerald-500 to-teal-600">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm">
                                    <i class="text-2xl text-white fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Security Settings</h3>
                                    <p class="mt-1 text-emerald-100">Ensure your account is using a long, random password to stay secure.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <div class="max-w-2xl">
                                <livewire:profile.update-password-form />
                            </div>
                        </div>

                        <!-- Security Tips -->
                        <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">Security Recommendations</h4>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-500 text-sm mt-0.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Use a strong password</p>
                                        <p class="text-xs text-gray-500">At least 8 characters with mixed case, numbers, and symbols</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-500 text-sm mt-0.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Regular password updates</p>
                                        <p class="text-xs text-gray-500">Change your password every 3-6 months</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Management Tab -->
                <div id="danger-content" class="hidden tab-content">
                    <div class="overflow-hidden bg-white border border-red-200 shadow-xl sm:rounded-2xl">
                        <!-- Header -->
                        <div class="px-6 py-8 bg-gradient-to-r from-red-500 to-pink-600">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm">
                                    <i class="text-2xl text-white fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Account Management</h3>
                                    <p class="mt-1 text-red-100">Permanently delete your account and all associated data.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <div class="max-w-2xl">
                                <livewire:profile.delete-user-form />
                            </div>
                        </div>

                        <!-- Warning Notice -->
                        <div class="px-8 py-6 border-t border-red-200 bg-red-50">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="text-lg text-red-400 fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h4 class="mb-2 text-sm font-semibold text-red-900">Before you delete your account</h4>
                                    <ul class="space-y-1 text-sm text-red-700">
                                        <li class="flex items-center space-x-2">
                                            <i class="text-xs fas fa-circle"></i>
                                            <span>Download any important data you want to keep</span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <i class="text-xs fas fa-circle"></i>
                                            <span>Cancel any active subscriptions or services</span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <i class="text-xs fas fa-circle"></i>
                                            <span>This action cannot be undone</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab links
            document.querySelectorAll('.tab-link').forEach(link => {
                link.classList.remove('border-indigo-500', 'text-indigo-600');
                link.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Add active class to selected tab link
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            showTab('profile');
        });
    </script>

    <style>
        /* Custom enhancements */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Smooth transitions */
        .tab-link {
            transition: all 0.2s ease-in-out;
        }

        .tab-content {
            transition: opacity 0.3s ease-in-out;
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Hover effects */
        .group:hover .group-hover\:text-indigo-500 {
            color: #6366f1;
        }

        /* Shadow enhancements */
        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .lg\:absolute {
                position: static !important;
            }

            .lg\:right-8 {
                right: auto !important;
            }

            .lg\:top-24 {
                top: auto !important;
            }

            .lg\:w-80 {
                width: auto !important;
            }
        }
    </style>
</x-app-layout>
