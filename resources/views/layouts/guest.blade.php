<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Stamp Inventory') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            /* Custom Blue Theme Variables */
            :root {
                --primary-blue: #2563eb;
                --secondary-blue: #3b82f6;
                --light-blue: #dbeafe;
                --dark-blue: #1e40af;
                --accent-blue: #60a5fa;
            }

            /* Loading Overlay */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(219, 234, 254, 0.95) 100%);
                backdrop-filter: blur(8px);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                display: none;
            }

            /* Loading Animation */
            .loading-container {
                text-align: center;
                padding: 2rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(37, 99, 235, 0.15);
                border: 1px solid rgba(37, 99, 235, 0.1);
            }

            .loading-dots {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 8px;
                margin-bottom: 1rem;
            }

            .dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: linear-gradient(45deg, var(--primary-blue), var(--secondary-blue));
                animation: dot-bounce 1.4s infinite ease-in-out both;
            }

            .dot:nth-child(1) { animation-delay: -0.32s; }
            .dot:nth-child(2) { animation-delay: -0.16s; }
            .dot:nth-child(3) { animation-delay: 0s; }

            @keyframes dot-bounce {
                0%, 80%, 100% {
                    transform: scale(0);
                    opacity: 0.5;
                }
                40% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            /* Background Pattern */
            .bg-pattern {
                background-image:
                    radial-gradient(circle at 25% 25%, rgba(37, 99, 235, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                    linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(248, 250, 252, 1) 100%);
                min-height: 100vh;
                position: relative;
            }

            /* Floating Elements */
            .floating-element {
                position: absolute;
                border-radius: 50%;
                background: linear-gradient(45deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.05));
                animation: float 6s ease-in-out infinite;
            }

            .floating-element:nth-child(1) {
                width: 80px;
                height: 80px;
                top: 10%;
                left: 10%;
                animation-delay: 0s;
            }

            .floating-element:nth-child(2) {
                width: 120px;
                height: 120px;
                top: 20%;
                right: 15%;
                animation-delay: 2s;
            }

            .floating-element:nth-child(3) {
                width: 60px;
                height: 60px;
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
            }

            .floating-element:nth-child(4) {
                width: 100px;
                height: 100px;
                bottom: 15%;
                right: 10%;
                animation-delay: 1s;
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0px) rotate(0deg);
                }
                33% {
                    transform: translateY(-20px) rotate(5deg);
                }
                66% {
                    transform: translateY(10px) rotate(-5deg);
                }
            }

            /* Content Container */
            .content-container {
                position: relative;
                z-index: 1;
                padding: 2rem 1rem;
            }

            /* Glass Effect */
            .glass-container {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow:
                    0 25px 50px -12px rgba(37, 99, 235, 0.1),
                    0 0 0 1px rgba(37, 99, 235, 0.05);
                border-radius: 24px;
            }

            /* Responsive Design */
            @media (max-width: 640px) {
                .content-container {
                    padding: 1rem 0.5rem;
                }

                .floating-element {
                    display: none;
                }
            }
        </style>
    </head>
    <body class="bg-pattern font-['Figtree']">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loading-overlay">
            <div class="loading-container">
                <div class="loading-dots">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-700">Loading...</h3>
                <p class="text-sm text-gray-500">Please wait while we prepare everything for you</p>
            </div>
        </div>

        <!-- Floating Background Elements -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>

        <!-- Main Content -->
        <div class="flex items-center justify-center min-h-screen content-container">
            <div class="w-full max-w-md mx-auto">
                <div class="p-8 glass-container">
                    {{$slot}}
                </div>
            </div>
        </div>

        <script>
            // Show the loading spinner
            function showLoading() {
                const overlay = document.getElementById('loading-overlay');
                overlay.style.display = 'flex';
                overlay.style.opacity = '0';
                overlay.style.transition = 'opacity 0.3s ease-in-out';

                setTimeout(() => {
                    overlay.style.opacity = '1';
                }, 10);
            }

            // Hide the loading spinner
            function hideLoading() {
                const overlay = document.getElementById('loading-overlay');
                overlay.style.transition = 'opacity 0.3s ease-in-out';
                overlay.style.opacity = '0';

                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            }

            // Page Load Handler
            document.addEventListener('DOMContentLoaded', () => {
                // Show loading initially
                showLoading();

                // Hide loading when everything is ready
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        hideLoading();
                    }, 800); // Small delay for better UX
                });
            });

            // Livewire Loading Integration
            document.addEventListener('livewire:navigating', () => {
                showLoading();
            });

            document.addEventListener('livewire:navigated', () => {
                hideLoading();
            });

            // Form Submission Loading
            document.addEventListener('livewire:sent', () => {
                showLoading();
            });

            document.addEventListener('livewire:received', () => {
                hideLoading();
            });

            // Enhanced Loading for Async Operations
            function fetchData() {
                showLoading();
                // Your async operation here
                setTimeout(() => {
                    hideLoading();
                }, 2000);
            }

            // Auto-hide loading if shown too long (failsafe)
            setTimeout(() => {
                if (document.getElementById('loading-overlay').style.display === 'flex') {
                    hideLoading();
                }
            }, 10000); // 10 seconds maximum
        </script>
    </body>
</html>
