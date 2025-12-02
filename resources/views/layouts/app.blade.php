<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stamp Selling | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        /* Custom Properties */
        :root {
            --sidebar-width: 250px;
            --primary: #3b82f6;
            --primary-dark: #1e40af;
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s ease;
        }

        /* Loading Overlay */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e5e7eb;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Sidebar Styles */
        .sidebar {
            transform: translateX(-100%);
            width: var(--sidebar-width);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
            }
        }

        /* Dropdown Animation */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .dropdown-content.open {
            max-height: 1000px;
        }

        /* Custom Scrollbar */
        .scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>

<body class="text-sm text-gray-900 bg-gray-50">

    <!-- Loading Overlay -->
    <div class="loading" id="loading">
        <div class="text-center">
            <div class="mb-3 spinner"></div>
            <p class="text-gray-600">Loading...</p>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay lg:hidden" id="overlay"></div>

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-50 h-full bg-white border-r border-gray-200 sidebar">
        <div class="flex flex-col h-full">

            <!-- Logo Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div
                        class="flex items-center justify-center rounded-lg w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600">
                        <i class="text-xs text-white fas fa-stamp"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-gray-800">Stamp Selling</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>
                <button id="closeSidebar" class="p-1 rounded lg:hidden hover:bg-gray-100">
                    <i class="text-sm text-gray-400 fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-3 overflow-y-auto scrollbar">
                <ul class="space-y-1">

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700' }}">
                            <i class="w-4 text-xs fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Financial Management -->
                    <li>
                        <div class="dropdown">
                            <button
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-700 rounded-md dropdown-btn hover:bg-blue-50 hover:text-blue-700">
                                <div class="flex items-center gap-2">
                                    <i class="w-4 text-xs fas fa-coins"></i>
                                    <span>Financial Management</span>
                                </div>
                                <i class="text-xs transition-transform duration-200 fas fa-chevron-right"></i>
                            </button>
                            <div class="mt-1 ml-4 dropdown-content">
                                <a href="{{ route('fund_management') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('fund_management') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-wallet opacity-60"></i>Fund Management
                                </a>
                                <a href="{{ route('money_manage') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('money_manage') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-balance-scale opacity-60"></i>Balance Management
                                </a>
                                <a href="{{ route('expences') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('expences') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-receipt opacity-60"></i>Expenses
                                </a>
                                <a href="{{ route('sofar-net-profit.index') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('sofar-net-profit.index') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-chart-line opacity-60"></i>Net Profit
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Inventory Management -->
                    <li>
                        <div class="dropdown">
                            <button
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-700 rounded-md dropdown-btn hover:bg-blue-50 hover:text-blue-700">
                                <div class="flex items-center gap-2">
                                    <i class="w-4 text-xs fas fa-boxes"></i>
                                    <span>Inventory Management</span>
                                </div>
                                <i class="text-xs transition-transform duration-200 fas fa-chevron-right"></i>
                            </button>
                            <div class="mt-1 ml-4 dropdown-content">
                                <a href="{{ route('stocks') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('stocks') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-cube opacity-60"></i>Purchase (Stock)
                                </a>
                                <a href="{{ route('set_price') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('set_price') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-tag opacity-60"></i>Unit Price (Branch)
                                </a>
                                <a href="{{ route('reject_free') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('reject_free') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-trash-alt opacity-60"></i>Reject or Free
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Sales Management -->
                    <li>
                        <div class="dropdown">
                            <button
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-700 rounded-md dropdown-btn hover:bg-blue-50 hover:text-blue-700">
                                <div class="flex items-center gap-2">
                                    <i class="w-4 text-xs fas fa-shopping-cart"></i>
                                    <span>Sales Management</span>
                                </div>
                                <i class="text-xs transition-transform duration-200 fas fa-chevron-right"></i>
                            </button>
                            <div class="mt-1 ml-4 dropdown-content">
                                <a href="{{ route('branches') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('branches') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-building opacity-60"></i>Branches
                                </a>
                                <a href="{{ route('branch_sale') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('branch_sale') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-store opacity-60"></i>Branch Management
                                </a>
                                <a href="{{ route('office_sale') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('office_sale') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-3 mr-2 fas fa-home opacity-60"></i>Head Office Sale
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Reports & Analytics -->
                    <li>
                        <div class="dropdown">
                            <button
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-700 rounded-md dropdown-btn hover:bg-blue-50 hover:text-blue-700">
                                <div class="flex items-center gap-2">
                                    <i class="w-4 text-xs fas fa-chart-bar"></i>
                                    <span>Reports & Analytics</span>
                                </div>
                                <i class="text-xs transition-transform duration-200 fas fa-chevron-right"></i>
                            </button>
                            <div class="dropdown-content ml-4 mt-1 space-y-0.5">

                                <!-- Financial Reports -->
                                <div class="px-2 py-1 text-xs font-medium tracking-wide text-gray-400 uppercase">
                                    Financial</div>
                                <a href="{{ route('fund_management_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('fund_management_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Fund Report
                                </a>
                                <a href="{{ route('money_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('money_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Money Report
                                </a>
                                <a href="{{ route('expense_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('expense_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Expense Report
                                </a>
                                <a href="{{ route('bank_balance_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('bank_balance_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Bank Balance
                                </a>

                                <!-- Sales Reports -->
                                <div class="px-2 py-1 mt-2 text-xs font-medium tracking-wide text-gray-400 uppercase">
                                    Sales</div>
                                <a href="{{ route('branch_sale_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('branch_sale_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Branch Report
                                </a>
                                <a href="{{ route('branch_total_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('branch_total_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Branches Report
                                </a>
                                <a href="{{ route('ho_sale_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('ho_sale_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>HO Sale Report
                                </a>
                                <a href="{{ route('reject_free_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('reject_free_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Reject/Free Report
                                </a>

                                <!-- Inventory Reports -->
                                <div class="px-2 py-1 mt-2 text-xs font-medium tracking-wide text-gray-400 uppercase">
                                    Inventory</div>
                                <a href="{{ route('stock_register_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('stock_register_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Stock Register
                                </a>
                                <a href="{{ route('purchase_report') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('purchase_report') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Purchase Report
                                </a>

                                <!-- Financial Statements -->
                                <div class="px-2 py-1 mt-2 text-xs font-medium tracking-wide text-gray-400 uppercase">
                                    Statements</div>
                                <a href="{{ route('receipt_payment') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('receipt_payment') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Receipt & Payment
                                </a>
                                <a href="{{ route('expenditure_sheet') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('expenditure_sheet') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Income & Expenditure
                                </a>
                                <a href="{{ route('balance_sheet') }}"
                                    class="block px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded {{ request()->routeIs('balance_sheet') ? 'text-blue-700 bg-blue-50' : '' }}">
                                    <i class="w-2 mr-2 fas fa-dot-circle opacity-40"></i>Balance Sheet
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Profile -->
                    <li>
                        <a href="{{ route('profile') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('profile') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700' }}">
                            <i class="w-4 text-xs fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-3 border-t border-gray-100">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 rounded-md hover:bg-red-50">
                    <i class="w-4 text-xs fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>

        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="lg:ml-[220px] min-h-screen">

        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between px-4 py-3">

                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="p-2 rounded-md lg:hidden hover:bg-gray-100">
                    <i class="text-gray-600 fas fa-bars"></i>
                </button>

                <!-- Page Title -->
                <h2 class="hidden font-medium text-gray-800 sm:block">Admin Dashboard</h2>

                <!-- Header Actions -->
                <div class="flex items-center gap-3">

                    <!-- Theme Toggle -->
                    <button id="themeToggle" class="p-2 text-gray-600 rounded-md hover:bg-gray-100">
                        <i class="text-sm fas fa-moon"></i>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                        <div
                            class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600">
                            <i class="text-xs text-white fas fa-user"></i>
                        </div>
                        <span class="hidden text-sm text-gray-700 sm:block">Admin</span>
                    </div>

                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-4">
            {{ $slot }}
        </div>

    </main>

    <!-- JavaScript -->
    <script>
        // DOM Elements
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('overlay');
        const loading = document.getElementById('loading');
        const themeToggle = document.getElementById('themeToggle');

        // Mobile Sidebar Toggle
        openSidebar?.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        });

        closeSidebar?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        // Dropdown Toggle
        document.querySelectorAll('.dropdown-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const icon = btn.querySelector('.fa-chevron-right');

                // Toggle current dropdown
                content.classList.toggle('open');
                icon.style.transform = content.classList.contains('open') ? 'rotate(90deg)' :
                'rotate(0deg)';
            });
        });

        // Loading Functions
        function showLoading() {
            loading.style.display = 'flex';
        }

        function hideLoading() {
            loading.style.display = 'none';
        }

        // Page Load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(hideLoading, 800);
        });

        // Theme Toggle
        themeToggle?.addEventListener('click', () => {
            const icon = themeToggle.querySelector('i');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
        });

        // Close mobile menu on link click
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                }
            });
        });

        // Auto-expand active dropdown
        document.querySelectorAll('.dropdown-content a').forEach(link => {
            if (link.classList.contains('text-blue-700')) {
                const dropdown = link.closest('.dropdown-content');
                const btn = dropdown.previousElementSibling;
                const icon = btn.querySelector('.fa-chevron-right');

                dropdown.classList.add('open');
                icon.style.transform = 'rotate(90deg)';
            }
        });
    </script>

</body>

</html>
