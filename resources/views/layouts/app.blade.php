<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Santri App')</title>
    @php
        $appFavicon = \App\Models\AppSetting::where('key', 'app_favicon')->value('value');
    @endphp
    @if($appFavicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($appFavicon) }}?v={{ time() }}">
        <link rel="shortcut icon" type="image/png" href="{{ Storage::url($appFavicon) }}?v={{ time() }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Header Styling */
        .app-header {
            background: linear-gradient(135deg, #1e7e34 0%, #28a745 25%, #20c997 75%, #17a2b8 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        }
        .app-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 3px,
                    rgba(255, 255, 255, 0.04) 3px,
                    rgba(255, 255, 255, 0.04) 6px
                );
            pointer-events: none;
            opacity: 0.6;
        }
        .app-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 40%, rgba(0,0,0,0.08) 100%);
            pointer-events: none;
        }
        .app-header .container-fluid {
            position: relative;
            z-index: 1;
        }
        /* Remove white background from logo - preserve PNG transparency */
        .app-header img,
        .app-header img[src*="logo"],
        .app-logo {
            /* Remove all backgrounds - preserve PNG transparency */
            background: none !important;
            background-color: transparent !important;
            background-image: none !important;
            /* NO blend mode - preserve original PNG transparency */
            mix-blend-mode: normal;
            /* Subtle shadow for visibility on colored background */
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
            -webkit-filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
            /* Ensure logo doesn't have any background */
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            /* Remove any padding that might create background effect */
            padding: 0 !important;
            /* Ensure proper display */
            display: block;
            margin-left: auto;
            margin-right: auto;
            /* Preserve PNG transparency */
            -webkit-mask-image: none;
            mask-image: none;
            /* Preserve aspect ratio and transparency */
            object-fit: contain;
            /* Ensure transparency is preserved */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        /* Wrapper untuk logo - completely transparent */
        .app-header {
            background-blend-mode: normal;
        }
        /* Logo wrapper - completely transparent, no background */
        .logo-wrapper {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
            padding: 0 !important;
            margin: 0 0 0.5rem 0 !important;
            border: none !important;
            display: inline-block;
            /* No border radius to avoid white corners */
            border-radius: 0;
            overflow: visible;
            /* Ensure wrapper doesn't add any background */
            box-shadow: none !important;
        }
        /* Force logo to preserve transparency */
        .app-header .app-logo {
            /* Normal blend mode - preserve PNG transparency */
            mix-blend-mode: normal;
            /* Subtle shadow for visibility */
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
            -webkit-filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
            /* Remove any background */
            background: transparent !important;
            background-color: transparent !important;
            position: relative;
            z-index: 1;
        }
        /* Force wrapper to be transparent */
        .app-header .logo-wrapper,
        .app-header div[style*="inline-block"] {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
            box-shadow: none !important;
        }
        /* Center header content excluding sidebar */
        @media (min-width: 768px) {
            @auth
                .app-header .row > div:last-child {
                    margin-left: auto;
                    margin-right: auto;
                }
            @endauth
        }
        
        /* Sidebar Styling - Match dengan header */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 3px 0 20px rgba(21, 87, 36, 0.3);
            background: linear-gradient(180deg, {{ $secondaryColor ?? '#e8f5e9' }} 0%, #c8e6c9 50%, #a5d6a7 100%);
            min-height: 100vh;
            border-right: 3px solid rgba(40, 167, 69, 0.5);
        }
        .sidebar-content {
            padding: 1.5rem 0.75rem;
            margin-top: 0;
        }
        @media (min-width: 768px) {
            .sidebar-content {
                padding-top: 2rem;
            }
        }
        @media (max-width: 767.98px) {
            .sidebar-content {
                padding: 1rem 0.5rem;
            }
        }
        .offcanvas {
            background: linear-gradient(180deg, {{ $secondaryColor ?? '#d4edda' }} 0%, #c3e6cb 100%);
        }
        .offcanvas-header {
            background: linear-gradient(135deg, {{ $primaryColor ?? '#28a745' }} 0%, #20c997 100%);
            color: white;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        .offcanvas-header .btn-close {
            filter: invert(1);
        }
        .offcanvas .list-group-item {
            border-radius: 10px;
            margin: 0.25rem 0.5rem;
        }
        .sidebar .list-group-item {
            border: none;
            border-radius: 10px;
            padding: 0.875rem 1.25rem;
            margin: 0.25rem 0.5rem;
            background: transparent;
            color: #212529;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .sidebar .list-group-item:hover {
            background: rgba(40, 167, 69, 0.25);
            border-left-color: {{ $primaryColor ?? '#28a745' }};
            transform: translateX(5px);
            color: #155724;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }
        .sidebar .list-group-item.active {
            background: linear-gradient(135deg, {{ $primaryColor ?? '#28a745' }} 0%, #1e7e34 100%);
            color: white;
            font-weight: 700;
            border-left-color: #155724;
            box-shadow: 0 4px 12px rgba(21, 87, 36, 0.4);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .sidebar .list-group-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.15);
            border-left-color: #dc3545;
            color: #dc3545 !important;
        }
        .sidebar .border-top {
            border-color: rgba(40, 167, 69, 0.2) !important;
        }
        .sidebar .list-group-item i {
            margin-right: 0.75rem;
            width: 22px;
            text-align: center;
        }
        main {
            margin-top: 0.5rem;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        /* User Navbar Styling */
        .user-navbar {
            position: sticky;
            top: 0;
            z-index: 99;
        }
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar-content {
                margin-top: 100px;
            }
        }
        @media (max-width: 767.98px) {
            main {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            .sidebar-content {
                margin-top: 90px;
            }
        }
        body {
            background: #f8f9fa;
        }
        @media (max-width: 575.98px) {
            .card-body {
                padding: 1rem;
            }
            .btn {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }
            .navbar {
                padding: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <header class="app-header" style="padding: @auth clamp(1rem, 2.5vw, 1.5rem) @else clamp(0.75rem, 2vw, 1rem) @endauth 0;">
        <div class="container-fluid px-2 px-md-3">
            <div class="row align-items-center">
                @auth
                    <!-- Offset untuk sidebar di desktop -->
                    <div class="col-md-3 col-lg-2 d-none d-md-block"></div>
                    <div class="col-12 col-md-9 col-lg-10 text-center">
                @else
                    <div class="col-12 text-center">
                @endauth
                    @if(isset($appLogo) && $appLogo)
                        <div class="logo-wrapper">
                            <img src="{{ Storage::url($appLogo) }}?v={{ time() }}" alt="Logo" class="app-logo" style="max-height: @auth clamp(50px, 8vw, 70px) @else clamp(45px, 7vw, 60px) @endauth; width: auto; height: auto; display: block; margin-left: auto; margin-right: auto;">
                        </div>
                    @endif
                    <h1 class="mb-1 px-2" style="font-size: clamp(@auth 1rem, 3vw, 1.4rem @else 0.9rem, 3vw, 1.2rem @endauth); font-weight: 700; text-shadow: 0 2px 8px rgba(0,0,0,0.2); letter-spacing: 0.3px; line-height: 1.2;">{{ $appName ?? 'MANAGEMEN DATA SANTRI' }}</h1>
                    <h2 class="mb-0 px-2" style="font-size: clamp(@auth 0.75rem, 2.5vw, 1rem @else 0.7rem, 2.5vw, 0.9rem @endauth); font-weight: 600; text-shadow: 0 2px 6px rgba(0,0,0,0.15); opacity: 0.95; line-height: 1.2;">{{ $appSubtitle ?? 'PP HS AL-FAKKAR' }}</h2>
                </div>
            </div>
        </div>
    </header>

    @auth
    <nav class="navbar navbar-expand-lg navbar-light mb-3 user-navbar" style="background: linear-gradient(135deg, {{ $secondaryColor ?? '#d4edda' }} 0%, #c3e6cb 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-bottom: 2px solid rgba(40, 167, 69, 0.3); padding: 0.75rem 0;">
        <div class="container-fluid px-2 px-md-3">
            <button class="btn btn-link text-success d-lg-none p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" style="text-decoration: none; font-weight: 600;">
                <i class="bi bi-list fs-5"></i> <span class="ms-1 d-none d-sm-inline">Menu</span>
            </button>
        </div>
    </nav>
    @endauth

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <!-- Offcanvas Sidebar for Mobile -->
            <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="sidebarMenuLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="list-group">
                        <a href="@if(Auth::user()->role === 'admin') {{ route('admin.dashboard') }} @else {{ route('santri.dashboard') }} @endif" class="list-group-item list-group-item-action {{ request()->is('admin/dashboard') || request()->is('santri/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('santri.index') }}" class="list-group-item list-group-item-action {{ request()->is('santri') || request()->is('santri/*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> Data Santri
                            </a>
                            <a href="{{ route('admin.profil-pondok') }}" class="list-group-item list-group-item-action {{ request()->is('admin/profil-pondok') ? 'active' : '' }}">
                                <i class="bi bi-building"></i> Profil Pondok
                            </a>
                            <a href="{{ route('admin.album.manage') }}" class="list-group-item list-group-item-action {{ request()->is('admin/album*') ? 'active' : '' }}">
                                <i class="bi bi-images"></i> Album Pondok
                            </a>
                            <a href="{{ route('admin.info-aplikasi') }}" class="list-group-item list-group-item-action {{ request()->is('admin/info-aplikasi') ? 'active' : '' }}">
                                <i class="bi bi-info-circle"></i> Info Aplikasi
                            </a>
                            <a href="{{ route('admin.unified-edit.index') }}" class="list-group-item list-group-item-action {{ request()->is('admin/unified-edit*') ? 'active' : '' }}">
                                <i class="bi bi-pencil-square"></i> Edit Semua Fitur
                            </a>
                        @elseif(Auth::user()->role === 'santri')
                            <a href="{{ route('santri.profil') }}" class="list-group-item list-group-item-action {{ request()->is('santri/profil') ? 'active' : '' }}">
                                <i class="bi bi-person"></i> Profil Saya
                            </a>
                        @endif
                        
                        <!-- Logout Button -->
                        <div class="border-top mt-2 pt-2">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action text-danger w-100 border-0" style="background: transparent; cursor: pointer;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Sidebar -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <div class="sidebar-content">
                    <div class="list-group">
                        <a href="@if(Auth::user()->role === 'admin') {{ route('admin.dashboard') }} @else {{ route('santri.dashboard') }} @endif" class="list-group-item list-group-item-action {{ request()->is('admin/dashboard') || request()->is('santri/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('santri.index') }}" class="list-group-item list-group-item-action {{ request()->is('santri') || request()->is('santri/*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> Data Santri
                            </a>
                            <a href="{{ route('admin.profil-pondok') }}" class="list-group-item list-group-item-action {{ request()->is('admin/profil-pondok') ? 'active' : '' }}">
                                <i class="bi bi-building"></i> Profil Pondok
                            </a>
                            <a href="{{ route('admin.album.manage') }}" class="list-group-item list-group-item-action {{ request()->is('admin/album*') ? 'active' : '' }}">
                                <i class="bi bi-images"></i> Album Pondok
                            </a>
                            <a href="{{ route('admin.info-aplikasi') }}" class="list-group-item list-group-item-action {{ request()->is('admin/info-aplikasi') ? 'active' : '' }}">
                                <i class="bi bi-info-circle"></i> Info Aplikasi
                            </a>
                            <a href="{{ route('admin.unified-edit.index') }}" class="list-group-item list-group-item-action {{ request()->is('admin/unified-edit*') ? 'active' : '' }}">
                                <i class="bi bi-pencil-square"></i> Edit Semua Fitur
                            </a>
                        @elseif(Auth::user()->role === 'santri')
                            <a href="{{ route('santri.profil') }}" class="list-group-item list-group-item-action {{ request()->is('santri/profil') ? 'active' : '' }}">
                                <i class="bi bi-person"></i> Profil Saya
                            </a>
                        @endif
                        
                        <!-- Logout Button -->
                        <div class="border-top mt-2 pt-2">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action text-danger w-100 border-0" style="background: transparent; cursor: pointer;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Main Content -->
            <main class="@auth col-md-9 col-lg-10 ms-sm-auto px-md-4 @else col-12 @endauth">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, {{ $secondaryColor ?? '#d4edda' }} 0%, #c3e6cb 100%); padding: 1.5rem 0; margin-top: 3rem; border-top: 3px solid {{ $primaryColor ?? '#28a745' }};">
        <div class="container-fluid px-2 px-md-3">
            <div class="row">
                @auth
                    <!-- Offset untuk sidebar di desktop -->
                    <div class="col-md-3 col-lg-2 d-none d-md-block"></div>
                    <div class="col-12 col-md-9 col-lg-10 text-center">
                @else
                    <div class="col-12 text-center">
                @endauth
                    <p class="mb-0 text-success">
                        {!! $footerText ?? '<strong>&copy; ' . date('Y') . ' PP HS Al-Fakkar</strong><br><small>Pondok Pesantren HS Al-Fakkar</small>' !!}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Setup CSRF token untuk AJAX requests
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                // Setup untuk fetch API
                const originalFetch = window.fetch;
                window.fetch = function(...args) {
                    if (args[1] && args[1].method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(args[1].method.toUpperCase())) {
                        args[1].headers = args[1].headers || {};
                        args[1].headers['X-CSRF-TOKEN'] = token.getAttribute('content');
                    }
                    return originalFetch.apply(this, args);
                }
                
                // Setup untuk XMLHttpRequest
                const originalOpen = XMLHttpRequest.prototype.open;
                XMLHttpRequest.prototype.open = function(method, url, ...rest) {
                    this.addEventListener('loadstart', function() {
                        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
                            this.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                        }
                    });
                    return originalOpen.apply(this, [method, url, ...rest]);
                }
            }
        })();
        
        // Auto-dismiss success alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlerts = document.querySelectorAll('.alert-success.alert-dismissible');
            successAlerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // 5 seconds
            });
        });
    </script>
</body>
</html>
