<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Platypi:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <title>Stamp Selling | Admin Panel</title>

    <style>
        /* Prevent breaking inside tables */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Prevent breaking inside elements (use this for long sections) */
        .avoid-break {
            page-break-inside: avoid;
        }

        /* Force page break after certain elements, like large sections */
        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-dots">
            <div class="dot">1</div>
            <div class="dot">2</div>
            <div class="dot">3</div>
        </div>
    </div>
    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo align-items-center">
                    <img src="{{asset('favicon.png')}}">
                    <h2 >Stamp<span class="text-blue-500"> Selling</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>
            {{-- {{ request()->routeIs('dashboard') ? 'active' : '' }} --}}
            <div class="sidebar">
                <a href="{{route('dashboard')}}"  class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>
                <div id="report-dropdown" class="dropdown {{ request()->is('money_report', 'purchase_report', 'branch_sale_report', 'ho_sale_report', 'reject_free_report', 'expense_report', 'branch_total_report', 'balance_sheet', 'expenditure_sheet') ? 'active' : '' }}">
                    <a href="#" id="report-toggle" class="flex items-center cursor-pointer dropdown-toggle {{ request()->is('money_report', 'purchase_report', 'branch_sale_report', 'ho_sale_report', 'reject_free_report', 'expense_report', 'branch_total_report', 'balance_sheet', 'expenditure_sheet') ? 'active' : '' }}">
                        <span class="text-sm material-icons-sharp">
                            assessment
                        </span>
                        <h3 class="">Reports</h3>
                        <span class="material-icons-sharp icon-rotate text-sm {{ request()->is('money_report', 'purchase_report', 'branch_sale_report', 'ho_sale_report', 'reject_free_report', 'expense_report', 'branch_total_report', 'balance_sheet', 'expenditure_sheet', 'transection_report') ? 'rotate-180' : '' }}">
                            expand_more
                        </span>
                    </a>
                    <div id="report-content" class="dropdown-content {{ request()->is('money_report', 'purchase_report', 'branch_sale_report', 'ho_sale_report', 'reject_free_report', 'expense_report', 'branch_total_report', 'balance_sheet', 'expenditure_sheet') ? 'active' : '' }}">
                        <a href="{{ route('fund_management_report') }}" class="block p-2 {{ request()->routeIs('fund_management_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('fund_management_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Fund Report
                        </a>
                        <a href="{{ route('money_report') }}" class="block p-2 {{ request()->routeIs('money_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('money_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Money Report
                        </a>
                        <a href="{{ route('branch_sale_report') }}" class="block p-2 {{ request()->routeIs('branch_sale_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('branch_sale_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Branch Report
                        </a>
                        <a href="{{ route('branch_total_report') }}" class="block p-2 {{ request()->routeIs('branch_total_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('branch_total_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Branches Report
                        </a>
                        <a href="{{ route('ho_sale_report') }}" class="block p-2 {{ request()->routeIs('ho_sale_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('ho_sale_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            HO Sale Report
                        </a>
                        <a href="{{ route('reject_free_report') }}" class="block p-2 {{ request()->routeIs('reject_free_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('reject_free_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Reject/Free Report
                        </a>
                        <a href="{{ route('expense_report') }}" class="block p-2 {{ request()->routeIs('expense_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('expense_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Expense Report
                        </a>
                        <a href="{{ route('bank_balance_report') }}" class="block p-2 {{ request()->routeIs('bank_balance_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('bank_balance_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Bank Balance Report
                        </a>
                        <a href="{{ route('stock_register_report') }}" class="block p-2 {{ request()->routeIs('stock_register_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('stock_register_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Stock Register Report
                        </a>
                        <a href="{{ route('purchase_report') }}" class="block p-2 {{ request()->routeIs('purchase_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('purchase_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Purchase Report
                        </a>
                        <a href="{{ route('receipt_payment') }}" class="block p-2 {{ request()->routeIs('receipt_payment') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('receipt_payment') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Receipt & Payment
                        </a>
                        <a href="{{ route('expenditure_sheet') }}" class="block p-2 {{ request()->routeIs('expenditure_sheet') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('expenditure_sheet') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Income & Expenditure
                        </a>
                        {{-- <a href="{{ route('transection_report') }}" class="block p-2 {{ request()->routeIs('transection_report') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('transection_report') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Transections Sheet
                        </a> --}}
                        <a href="{{ route('balance_sheet') }}" class="block p-2 {{ request()->routeIs('balance_sheet') ? 'active' : '' }}">
                            <span class="text-sm material-icons-sharp">
                                {{ request()->routeIs('balance_sheet') ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                            Balance Sheet
                        </a>
                    </div>
                </div>
                <a href="{{ route('fund_management') }}" class="{{ request()->routeIs('fund_management') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        money
                    </span>
                    <h3>Fund Management</h3>
                </a>

                <a href="{{ route('money_manage') }}" class="{{ request()->routeIs('money_manage') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        attach_money
                    </span>
                    <h3>Balance Management</h3>
                </a>

                <a href="{{ route('stocks') }}" class="{{ request()->routeIs('stocks') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        insert_chart_outlined
                    </span>
                    <h3>Purchase (Stock)</h3>
                </a>

                <a href="{{ route('branches') }}" class="{{ request()->routeIs('branches') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        location_city
                    </span>
                    <h3>Branches</h3>
                </a>

                <a href="{{ route('set_price') }}" class="{{ request()->routeIs('set_price') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        attach_money
                    </span>
                    <h3>Unit Price (Branch)</h3>
                </a>

                <a href="{{ route('branch_sale') }}" class="{{ request()->routeIs('branch_sale') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        local_grocery_store
                    </span>
                    <h3>Branch Management</h3>
                </a>

                <a href="{{ route('office_sale') }}" class="{{ request()->routeIs('office_sale') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        store
                    </span>
                    <h3>Head Office Sale</h3>
                </a>

                <a href="{{ route('reject_free') }}" class="{{ request()->routeIs('reject_free') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        delete
                    </span>
                    <h3>Reject or Free</h3>
                </a>

                <a href="{{ route('expences') }}" class="{{ request()->routeIs('expences') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        monetization_on
                    </span>
                    <h3>Expences</h3>
                </a>

                <a href="{{ route('sofar-net-profit.index') }}" class="{{ request()->routeIs('sofar-net-profit.index') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        account_balance
                    </span>
                    <h3>Sofar Net Profit</h3>
                </a>

                <a href="{{ route('profile') }}" class="{{ request()->routeIs('sofar-net-profit.index') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        account_box
                    </span>
                    <h3>Profile</h3>
                </a>


                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                </form>
            </div>
        </aside>


        <!-- Main Content -->
        <main>
            <div class="right-section">
                <div class="nav">

                    <button id="menu-btn">
                        <span class="material-icons-sharp">
                            menu
                        </span>
                    </button>


                    <div class="dark-mode">
                        <span class="material-icons-sharp active">
                            light_mode
                        </span>
                        <span class="material-icons-sharp">
                            dark_mode
                        </span>
                    </div>
                </div>
            </div>
            {{ $slot }}
        </main>
        <!-- End of Main Content -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="{{asset('index.js')}}"></script>
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
    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const dropdownContent = item.nextElementSibling;
                dropdownContent.classList.toggle('active');
                const icon = item.querySelector('.icon-rotate');
                icon.classList.toggle('rotate-180');
            });
        });

        // Add active-link class to the current link
        document.querySelectorAll('.sidebar a').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active-link');
                // Expand dropdown if the link is inside it
                if (link.closest('.dropdown-content')) {
                    link.closest('.dropdown-content').classList.add('active');
                    link.closest('.dropdown').querySelector('.icon-rotate').classList.add('rotate-180');
                }
            }
        });

    </script>



</body>

</html>
