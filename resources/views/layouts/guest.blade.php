<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Scripts -->
        <style>
            /* Loading Overlay */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999; /* Ensure it is on top of other content */
                display: none; /* Hidden by default */
            }

            /* Loading Dots */
            .loading-dots {
                display: flex;
                justify-content: space-between;
                width: 80px;
            }

            .dot {
                width: 15px;
                height: 15px;
                border-radius: 50%;
                background: #3498db;
                animation: dot-flashing 1.5s infinite ease-in-out;
            }

            .dot:nth-child(2) {
                animation-delay: 0.3s;
            }

            .dot:nth-child(3) {
                animation-delay: 0.6s;
            }

            /* Animation */
            @keyframes dot-flashing {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.3;
                }
            }
        </style>
    </head>
    <body class="bg-gray-100">
        <div class="loading-overlay" id="loading-overlay">
            <div class="loading-dots">
                <div class="dot">1</div>
                <div class="dot">2</div>
                <div class="dot">3</div>
            </div>
        </div>
        <div class="max-w-sm mx-auto">
            {{$slot}}
        </div>
        <script>
            // Show the loading spinner
            function showLoading() {
                document.getElementById('loading-overlay').style.display = 'flex';
            }
    
            // Hide the loading spinner
            function hideLoading() {
                document.getElementById('loading-overlay').style.display = 'none';
            }
    
            // Example usage
            document.addEventListener('DOMContentLoaded', () => {
                hideLoading(); // Hide spinner when page is fully loaded
            });
    
            // Show the spinner when initiating some async operation
            function fetchData() {
                showLoading();
                // Simulate an async operation
                setTimeout(() => {
                    // Do your data fetching here
                    hideLoading();
                }, 2000); // Example timeout
            }
    
            // Trigger data fetching as an example
            fetchData();
        </script>
    </body>
</html>
