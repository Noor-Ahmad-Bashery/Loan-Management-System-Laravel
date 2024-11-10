<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Loan')</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        /* Dropdown menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            z-index: 10;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            border-radius: 0.25rem;
            min-width: 12rem;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex flex-grow">
        <!-- Sidebar -->
        @auth
        <nav class="w-64 bg-white shadow-md h-screen hidden lg:block">
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Loan Management System</h2>
                <ul>
                    <li class="mb-2"><a href="{{ route('admin.alluser') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">همه کاربر ها</span></a></li>
                    <li class="mb-2"><a href="{{ route('admin.register') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">ثبت کاربر</span></a></li>
                    <li class="mb-2 dropdown">
                        <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">پرداخت ها</span></a>
                        <ul class="dropdown-menu">
                            <li class="mb-2"><a href="{{ route('admin.notebook') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">فیس کتابچه</span></a></li>
                            <li class="mb-2"><a href="{{ route('admin.monthlypayment') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">پرداخت ماهانه</span></a></li>
                            <li class="mb-2"><a href="{{ route('admin.additionalpayment') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اضافه پرداخت</span></a></li>
                            <li class="mb-2"><a href="{{ route('admin.loanPayments') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">پرداخت قرضه</span></a></li>
                        </ul>
                    </li>



                    <li class="mb-2 dropdown">
                        <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">  قرضه ها</span></a>
                        <ul class="dropdown-menu">
                            <li class="mb-2"><a href="{{ route('admin.loan') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اخذ مکمل پول </span></a></li>
                            <li class="mb-2"><a href="{{ route('admin.getMultiLoanRequest') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اخذ قرضه </span></a></li>
                            <li class="mb-2"><a href="{{ route('admin.getLoans') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">مشاهده کاربرهای باقی </span></a></li>
            
                        </ul>
                    </li>

                    <form method="post" action="{{ route('dual.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded">
                            <span class="mr-2">خروج</span>
                        </button>
                    </form>
                </ul>
            </div>
        </nav>
        @endauth
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <header class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">انجمن ظهور</h1>
                <button class="lg:hidden text-gray-500 focus:outline-none" id="sidebarToggle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </header>
            <main class="content m-2 p-2">
                @yield('content')
            </main>
        </div>

        @auth
        <!-- Mobile Sidebar -->
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden" id="mobileOverlay"></div>
        <div class="fixed right-0 bg-white shadow-md h-full w-64 z-50 transform translate-x-full transition-transform" id="mobileSidebar">
            <nav class="h-full">
                <div class="p-4">
                    <h2 class="text-xl font-bold mb-4">انجمن ظهور</h2>
                    <ul>
                        <li class="mb-2"><a href="{{ route('admin.alluser') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">همه کاربرها</span></a></li>
                        <li class="mb-2"><a href="{{ route('admin.register') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">ثبت کاربر</span></a></li>
                        <li class="mb-2 dropdown">
                            <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">پرداخت ها</span></a>
                            <ul class="dropdown-menu">
                                <li class="mb-2"><a href="{{ route('admin.notebook') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">فیس کتابچه</span></a></li>
                                <li class="mb-2"><a href="{{ route('admin.monthlypayment') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">پرداخت ماهانه</span></a></li>
                                <li class="mb-2"><a href="{{ route('admin.additionalpayment') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اضافه پرداخت</span></a></li>
                                <li class="mb-2"><a href="{{ route('admin.loanPayments') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2"> پرداخت قرضه</span></a></li>
                            </ul>
                        </li>

                        <li class="mb-2 dropdown">
                            <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">قرضه ها</span></a>
                            <ul class="dropdown-menu">
                                <li class="mb-2"><a href="{{ route('admin.loan') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اخذ مکمل پول</span></a></li>
                                <li class="mb-2"><a href="{{ route('admin.getMultiLoanRequest') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اخذ  قرضه</span></a></li>
                                <li class="mb-2"><a href="{{ route('admin.getLoans') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">مشاهده کاربر های باقی</span></a></li>
                             
                            </ul>
                        </li>


                        <li class="mb-2"><a href="{{ route('admin.loan') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded"><span class="mr-2">اخذ قرضه</span></a></li>
                        <form method="post" action="{{ route('dual.logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded">
                                <span class="mr-2">خروج</span>
                            </button>
                        </form>
                    </ul>
                </div>
            </nav>
        </div>
        </div>
        @endauth
        <footer class="bg-white shadow-md p-4 mt-6">
            <p class="text-center">&copy; {{ date('Y') }} کاپی غیر مجاز</p>
        </footer>
        <script>
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            sidebarToggle.addEventListener('click', () => {
                mobileSidebar.classList.toggle('translate-x-full');
                mobileOverlay.classList.toggle('hidden');
            });

            mobileOverlay.addEventListener('click', () => {
                mobileSidebar.classList.add('translate-x-full');
                mobileOverlay.classList.add('hidden');
            });
        </script>
</body>

</html>
