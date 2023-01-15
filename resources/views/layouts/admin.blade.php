@php
    $title = 'Piggery Management System';
    if (isset($pageTitle)) {
        $title = $pageTitle . ' | ' . $title;
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Google Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
</head>

<body>
    @auth
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <a href="/" class="logo d-flex align-items-center">
                    <span class="d-none d-lg-block">Piggery Management System</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/profile-img.png') }}" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>Piggery Admin</h6>
                                <span>Administratior</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt me-2"></i>
                                    <span>Sign Out</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>

        <aside id="sidebar" class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.dashboard') ? '' : 'text-dark bg-white' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="ms-2">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.pigs') ? '' : 'text-dark bg-white' }}" href="{{ route('admin.pigs') }}">
                        <i class="fa-solid fa-piggy-bank"></i>
                        <span class="ms-2">Manage Pigs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.breeds') ? '' : 'text-dark bg-white' }}" href="{{ route('admin.breeds') }}">
                        <i class="fa-solid fa-bars-staggered"></i>
                        <span class="ms-2">Manage Breeds</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.quarantine') ? '' : 'text-dark bg-white' }}" href="{{ route('admin.quarantine') }}">
                        <i class="fa-solid fa-house-lock"></i>
                        <span class="ms-2">Manage Quarantine</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.orders') ? '' : 'text-dark bg-white' }}" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#" aria-expanded="true">
                        <i class="fa-solid fa-boxes-packing"></i>
                        <span class="ms-2">Manage Orders</span>
                        <i class="fa-solid fa-angle-down ms-auto"></i>
                    </a>
                    <ul id="orders-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
                        <li>
                            <a href="{{ route('admin.orders', ['type' => 'pending_orders']) }}" class="text-decoration-none">
                                Pending Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders', ['type' => 'sold']) }}" class="text-decoration-none">
                                Sold
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.logs') ? '' : 'text-dark bg-white' }}" href="{{ route('admin.logs') }}">
                        <i class="fa-solid fa-list"></i>
                        <span class="ms-2">View Logs</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main id="main" class="main">
            <div class="container-fluid">
                <div class="pagetitle d-flex">
                    <h1>{{ $pageTitle ?? '' }}</h1>
                    @if (isset($back))
                        <a href="{{ $back }}" class="btn btn-dark ms-3"><i class="fa-solid fa-circle-chevron-left"></i> Go back</a>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    @endauth

    @guest
        @yield('content')
    @endguest

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    @yield('js')
</body>
</html>
