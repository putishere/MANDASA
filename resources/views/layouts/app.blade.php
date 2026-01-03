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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Academic Typography dengan Warna Hijau */
        :root {
            --academic-primary: #059669;
            --academic-secondary: #d1fae5;
            --academic-accent: #10b981;
            --academic-text: #1f2937;
            --academic-border: #a7f3d0;
            --academic-success: #10b981;
            --academic-warning: #d97706;
            --academic-green-dark: #047857;
            --academic-green-light: #d1fae5;
            --academic-green-medium: #34d399;
            --academic-green-very-light: #ecfdf5;
            
            /* Font Sizes */
            --font-xs: 0.75rem;
            --font-sm: 0.875rem;
            --font-md: 1rem;
            --font-lg: 1.125rem;
            --font-xl: 1.25rem;
            --font-2xl: 1.5rem;
            --font-3xl: 1.875rem;
            --font-4xl: 2.25rem;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--academic-text);
            background: #ffffff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600;
            color: var(--academic-text);
        }
        
        /* Header Styling - Academic Green */
        .app-header {
            background: var(--academic-primary);
            color: white;
            box-shadow: 0 2px 8px rgba(21, 87, 36, 0.2);
            position: relative;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
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
        
        /* Sidebar Styling - Academic Theme */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 2px 0 12px rgba(21, 87, 36, 0.08);
            background: #ffffff;
            min-height: 100vh;
            border-right: 1px solid var(--academic-border);
            width: 240px;
        }
        .sidebar-content {
            padding: 0;
            margin-top: 0;
            padding-top: 0.5rem;
            border-top: none !important;
        }
        @media (min-width: 768px) {
            .sidebar-content {
                padding-top: 0;
                border-top: none !important;
            }
        }
        @media (max-width: 767.98px) {
            .sidebar-content {
                padding: 0;
                border-top: none !important;
            }
        }
        /* Sidebar Header Section */
        .sidebar-header {
            padding: 0.6rem 0.4rem;
            border-bottom: none;
            margin-bottom: 0;
            text-align: center;
            background: var(--academic-primary);
        }
        .sidebar-header .logo-wrapper {
            margin-bottom: 0.35rem;
        }
        .sidebar-header .logo-wrapper img {
            max-width: 45px;
            max-height: 45px;
            width: auto;
            height: auto;
            background: transparent;
            border-radius: 50%;
            padding: 2px;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
        .sidebar-header h1 {
            color: #ffffff;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 0.75rem;
            margin: 0.35rem 0 0.15rem 0;
            line-height: 1.2;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            word-break: break-word;
        }
        .sidebar-header h2 {
            color: rgba(255, 255, 255, 0.9);
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            font-size: 0.6rem;
            margin: 0;
            line-height: 1.2;
            opacity: 0.9;
        }
        /* Offcanvas Styling */
        .offcanvas {
            background: #ffffff;
            border-right: 1px solid var(--academic-border);
        }
        .offcanvas-header {
            background: var(--academic-primary);
            color: white;
            border-bottom: none;
            padding: 0.6rem 0.4rem;
            text-align: center;
        }
        .offcanvas-header .logo-wrapper {
            margin-bottom: 0.35rem;
        }
        .offcanvas-header .logo-wrapper img {
            max-width: 45px;
            max-height: 45px;
            width: auto;
            height: auto;
            background: transparent;
            border-radius: 50%;
            padding: 2px;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
        .offcanvas-header h1 {
            color: #ffffff;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 0.75rem;
            margin: 0.35rem 0 0.15rem 0;
            line-height: 1.2;
            word-break: break-word;
        }
        .offcanvas-header h2 {
            color: rgba(255, 255, 255, 0.9);
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            font-size: 0.6rem;
            margin: 0;
            line-height: 1.2;
        }
        .offcanvas-header .offcanvas-title {
            display: none;
        }
        .offcanvas-header .btn-close {
            filter: invert(1);
            opacity: 0.9;
        }
        .offcanvas-header .btn-close:hover {
            opacity: 1;
        }
        .offcanvas .list-group-item {
            border-radius: var(--radius-sm);
            margin: 0.25rem var(--spacing-sm);
        }
        /* Sidebar Menu Items */
        .sidebar .list-group {
            padding: 0.5rem 0;
        }
        .sidebar .list-group-item {
            border: none;
            border-radius: 0;
            padding: 0.875rem 1.25rem;
            margin: 0;
            background: transparent;
            color: var(--academic-text);
            font-weight: 500;
            font-size: var(--font-md);
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            line-height: 1.5;
        }
        .sidebar .list-group-item:hover {
            background: var(--academic-green-light);
            border-left-color: var(--academic-primary);
            color: var(--academic-primary);
            font-weight: 600;
        }
        .sidebar .list-group-item.active {
            background: var(--academic-primary);
            color: white;
            font-weight: 600;
            border-left-color: var(--academic-green-dark);
            box-shadow: none;
        }
        .sidebar .list-group-item.active i {
            color: white;
        }
        .sidebar .list-group-item.text-danger {
            color: #dc3545;
        }
        .sidebar .list-group-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
            color: #dc3545 !important;
        }
        .sidebar .list-group-item.text-danger.active {
            background: #dc3545;
            color: white;
            border-left-color: #991b1b;
        }
        /* Sidebar Divider */
        .sidebar .border-top {
            border: none !important;
            border-top: none !important;
            margin: 0.5rem 0;
            padding-top: 0.5rem;
        }
        /* Sidebar Icons */
        .sidebar .list-group-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: var(--font-lg);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .sidebar .list-group-item:hover i {
            transform: none;
        }
        .sidebar .list-group-item.active i {
            transform: none;
        }
        /* Sidebar Menu Group Label (if needed) */
        .sidebar-menu-label {
            padding: 0.5rem 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--academic-text);
            opacity: 0.6;
        }
        /* Responsive Sidebar Width */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 220px;
            }
        }
        @media (max-width: 767.98px) {
            .sidebar {
                width: 100%;
                max-width: 280px;
            }
        }
        main {
            margin-top: var(--spacing-md);
            padding-left: var(--spacing-md);
            padding-right: var(--spacing-md);
        }
        /* User Navbar Styling */
        .user-navbar {
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .user-navbar .btn-link {
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }
        .user-navbar .btn-link:hover {
            background: var(--academic-green-light) !important;
            color: var(--academic-primary) !important;
        }
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar-content {
                margin-top: 0;
            }
            .sidebar-header {
                padding: 0.75rem 0.5rem;
            }
            .sidebar-header .logo-wrapper img {
                max-width: 55px;
                max-height: 55px;
            }
            .sidebar-header h1 {
                font-size: 0.8rem;
            }
            .sidebar-header h2 {
                font-size: 0.65rem;
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
                margin-top: 0;
            }
        }
        body {
            background: #ffffff;
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
    @auth
    <header class="app-header" style="padding: 0.75rem 0; min-height: 60px;">
        <div class="container-fluid px-2 px-md-3">
            <div class="row align-items-center">
                <!-- Offset untuk sidebar di desktop -->
                <div class="col-md-3 col-lg-2 d-none d-md-block"></div>
                <div class="col-12 col-md-9 col-lg-10">
                    <!-- Header kosong karena logo sudah di sidebar -->
                </div>
            </div>
        </div>
    </header>
    @else
    <header class="app-header" style="padding: clamp(1rem, 2.5vw, 1.5rem) 0;">
        <div class="container-fluid px-2 px-md-3">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    @php
                        $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->value('value');
                        $appName = \App\Models\AppSetting::where('key', 'app_name')->value('value') ?? 'MANAGEMEN DATA SANTRI';
                        $appSubtitle = \App\Models\AppSetting::where('key', 'app_subtitle')->value('value') ?? 'PP HS AL-FAKKAR';
                    @endphp
                    @if($appLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($appLogo))
                        <div class="logo-wrapper">
                            <img src="{{ Storage::url($appLogo) }}?v={{ time() }}" alt="Logo" class="app-logo" style="max-height: clamp(45px, 7vw, 60px); width: auto; height: auto; display: block; margin-left: auto; margin-right: auto;">
                        </div>
                    @endif
                    <h1 class="mb-1 px-2" style="font-size: clamp(0.9rem, 3vw, 1.2rem); font-weight: 700; text-shadow: 0 2px 8px rgba(0,0,0,0.2); letter-spacing: 0.3px; line-height: 1.2;">{{ $appName }}</h1>
                    <h2 class="mb-0 px-2" style="font-size: clamp(0.7rem, 2.5vw, 0.9rem); font-weight: 600; text-shadow: 0 2px 6px rgba(0,0,0,0.15); opacity: 0.95; line-height: 1.2;">{{ $appSubtitle }}</h2>
                </div>
            </div>
        </div>
    </header>
    @endauth

    @auth
    <nav class="navbar navbar-expand-lg navbar-light mb-3 user-navbar" style="background: #ffffff; box-shadow: 0 1px 3px rgba(21, 87, 36, 0.05); border-bottom: 1px solid var(--academic-border); padding: 0.75rem 0;">
        <div class="container-fluid px-2 px-md-3">
            <button class="btn btn-link d-lg-none p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" style="text-decoration: none; font-weight: 600; color: var(--academic-primary);">
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
                    <div style="flex: 1;">
                        @php
                            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->value('value');
                            $appName = \App\Models\AppSetting::where('key', 'app_name')->value('value') ?? 'MANAGEMEN DATA SANTRI';
                            $appSubtitle = \App\Models\AppSetting::where('key', 'app_subtitle')->value('value') ?? 'PP HS AL-FAKKAR';
                        @endphp
                        @if($appLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($appLogo))
                            <div class="logo-wrapper">
                                <img src="{{ Storage::url($appLogo) }}?v={{ time() }}" alt="Logo" class="app-logo">
                            </div>
                        @endif
                        <h1>{{ $appName }}</h1>
                        <h2>{{ $appSubtitle }}</h2>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                        <div class="border-top">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action text-danger w-100 border-0" style="background: transparent; cursor: pointer; padding: 0.875rem 1.25rem;">
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
                    <!-- Sidebar Header dengan Logo dan Teks -->
                    <div class="sidebar-header">
                        @php
                            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->value('value');
                            $appName = \App\Models\AppSetting::where('key', 'app_name')->value('value') ?? 'MANAGEMEN DATA SANTRI';
                            $appSubtitle = \App\Models\AppSetting::where('key', 'app_subtitle')->value('value') ?? 'PP HS AL-FAKKAR';
                        @endphp
                        @if($appLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($appLogo))
                            <div class="logo-wrapper">
                                <img src="{{ Storage::url($appLogo) }}?v={{ time() }}" alt="Logo" class="app-logo">
                            </div>
                        @endif
                        <h1>{{ $appName }}</h1>
                        <h2>{{ $appSubtitle }}</h2>
                    </div>
                    
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
                        <div class="border-top">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action text-danger w-100 border-0" style="background: transparent; cursor: pointer; padding: 0.875rem 1.25rem;">
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

    <footer style="background: var(--academic-green-light); padding: 1.5rem 0; margin-top: 3rem; border-top: 1px solid var(--academic-border);">
        <div class="container-fluid px-2 px-md-3">
            <div class="row">
                @auth
                    <!-- Offset untuk sidebar di desktop -->
                    <div class="col-md-3 col-lg-2 d-none d-md-block"></div>
                    <div class="col-12 col-md-9 col-lg-10 text-center">
                @else
                    <div class="col-12 text-center">
                @endauth
                    <p class="mb-0" style="color: var(--academic-text);">
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
