<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name')  }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/glucoscreen.css') }}">
    @yield('style')
</head>

<body class="gs-app">
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        <aside class="left-sidebar gs-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-center mt-4 mb-3">
                    <a href="{{ route(auth()->user()->role .'.dashboard') }}" class="text-nowrap logo-img text-decoration-none"
                        style="display:flex;align-items:center;gap:12px;">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" width="44" style="border-radius:12px;" />
                        <span style="font-weight:800;color:var(--gs-text);font-size:1.15rem;">{{ config('app.name') }}</span>
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-6" style="color:var(--gs-text);"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <x-dashboard.sidebar />
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->

        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header gs-header">
                <nav class="navbar navbar-expand-lg navbar-light px-3">
                    <ul class="navbar-nav align-items-center gap-2">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2">

                            <li class="nav-item nav-item-hide d-none d-lg-flex">
                                <a class="nav-link" href="{{ route('home') }}" title="Beranda">
                                    <i class="ti ti-home"></i>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/profile/user-1.jpg') }}" alt="" width="38"
                                        height="38" style="border-radius:50%;">
                                    <span class="d-none d-lg-inline fw-semibold" style="color:var(--gs-text);">
                                        {{ auth()->user()->name }}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body p-2">
                                        <div class="px-3 py-2 mb-2" style="background:rgba(255,255,255,0.08);border:1px solid var(--gs-border);border-radius:12px;">
                                            <div class="fw-bold" style="color:var(--gs-text);">{{ auth()->user()->name }}</div>
                                            <small style="color:var(--gs-muted);">{{ ucfirst(auth()->user()->role) }}</small>
                                        </div>
                                        <a href="{{ route('logout') }}" type="submit"
                                            class="gs-btn gs-btn-primary gs-btn-block"
                                            style="background:linear-gradient(135deg,#FF5E7D,#FF2D55);box-shadow:0 6px 18px var(--gs-glow-danger);">
                                            <i class="ti ti-logout"></i> Keluar
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->

            <div class="body-wrapper-inner">
                <div class="container-fluid gs-glass">
                    <div class="py-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    @include('sweetalert::alert')
</body>
</html>