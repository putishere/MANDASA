@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
    .dashboard-card {
        transition: all 0.2s ease;
        border: 1px solid var(--academic-border);
        border-radius: var(--radius-md);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
        background: #ffffff;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(21, 87, 36, 0.15);
        border-color: var(--academic-primary) !important;
    }
    .dashboard-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: var(--spacing-lg);
    }
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        transition: color 0.2s ease;
    }
    .dashboard-card:hover .card-icon {
        color: var(--academic-primary);
    }
    .card-content-wrapper {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .card-button-wrapper {
        margin-top: auto;
        padding-top: 1rem;
    }
    .bg-light-green {
        background: #ffffff;
        border: 1px solid var(--academic-border);
        background: linear-gradient(to bottom, #ffffff 0%, var(--academic-green-light) 100%);
    }
    .stat-card .card-icon {
        color: var(--academic-primary) !important;
    }
    .stat-card:hover {
        border-left-color: var(--academic-green-medium);
    }
    .dashboard-card .btn {
        white-space: nowrap;
        font-size: var(--font-sm);
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        transition: all 0.2s ease;
        background: var(--academic-primary);
        border: 1px solid var(--academic-primary);
        color: white;
    }
    .dashboard-card .btn:hover {
        background: var(--academic-accent);
        border-color: var(--academic-accent);
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
    }
    .welcome-banner {
        background: var(--academic-primary);
        border-radius: var(--radius-md);
        padding: 1.5rem 1.75rem;
        box-shadow: var(--shadow-md);
        margin-bottom: var(--spacing-xl);
    }
    .welcome-banner h3 {
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        font-size: var(--font-xl);
        margin-bottom: 0.5rem;
        color: white;
    }
    .welcome-banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: var(--font-md);
    }
    .stat-card {
        background: #ffffff;
        border-left: 4px solid var(--academic-primary);
        border: 1px solid var(--academic-border);
        border-left-width: 4px;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        transition: all 0.2s ease;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(21, 87, 36, 0.15);
    }
    .stat-card.warning {
        border-left-color: var(--academic-warning);
    }
    .stat-card.warning:hover {
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
    }
    .stat-number {
        font-weight: 700;
        letter-spacing: -0.5px;
        color: var(--academic-primary) !important;
        font-family: 'Inter', sans-serif;
        line-height: 1.2;
    }
    .stat-card.warning .stat-number {
        color: var(--academic-warning) !important;
    }
    .stat-label {
        font-weight: 600;
        color: var(--academic-text) !important;
        font-size: var(--font-md);
        margin-top: 0.5rem;
    }
    .stat-card .card-icon.text-success {
        color: var(--academic-primary) !important;
    }
    .stat-card.warning .card-icon.text-warning {
        color: var(--academic-warning) !important;
    }
    @media (max-width: 768px) {
        .dashboard-card .card-body {
            padding: 1.25rem;
        }
        .welcome-banner {
            padding: 1.25rem 1.5rem;
        }
    }
</style>

<div class="container-fluid px-2 px-md-3">
    <div class="row mb-4 mb-md-5">
        <div class="col-12">
            <div class="welcome-banner text-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h3 class="mb-0" style="font-size: var(--font-xl);">
                            <i class="bi bi-person-badge me-2"></i> Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
                        </h3>
                        <p class="mb-0 mt-2 opacity-90" style="font-size: var(--font-md);">
                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <i class="bi bi-camera-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-3 g-md-4 mb-4 mb-md-5">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card dashboard-card stat-card shadow-sm">
                <div class="card-body text-center">
                    <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h2 class="stat-number mb-2" style="font-size: var(--font-3xl); line-height: 1.2;">{{ \App\Models\User::where('role', 'santri')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark">Total Santri</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card dashboard-card stat-card shadow-sm">
                <div class="card-body text-center">
                    <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="stat-number mb-2" style="font-size: var(--font-3xl); line-height: 1.2;">{{ \App\Models\SantriDetail::where('status_santri', 'aktif')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark">Santri Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card dashboard-card stat-card warning shadow-sm">
                <div class="card-body text-center">
                    <div class="card-icon text-warning mb-3" style="font-size: 2.5rem;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <h2 class="stat-number text-warning mb-2" style="font-size: var(--font-3xl); line-height: 1.2;">{{ \App\Models\SantriDetail::where('status_santri', 'boyong')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark">Santri Boyong</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Utama -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Data Santri -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: var(--font-lg); color: var(--academic-text); font-family: 'Playfair Display', serif;">Data Santri</h5>
                        <p class="card-text mb-3" style="font-size: var(--font-sm); line-height: 1.5; color: #495057;">Kelola data semua santri (CRUD)</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('santri.index') }}" class="btn w-100" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Kelola Santri
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil Pondok -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: var(--font-lg); color: var(--academic-text); font-family: 'Playfair Display', serif;">Profil Pondok</h5>
                        <p class="card-text text-muted mb-3" style="font-size: var(--font-sm); line-height: 1.5;">Kelola profil dan informasi pondok</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.profil-pondok') }}" class="btn w-100" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Album Pondok -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                            <i class="bi bi-images"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: var(--font-lg); color: var(--academic-text); font-family: 'Playfair Display', serif;">Album Pondok</h5>
                        <p class="card-text text-muted mb-3" style="font-size: var(--font-sm); line-height: 1.5;">Kelola galeri foto kegiatan pondok</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.album.manage') }}" class="btn w-100" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Kelola Album
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Aplikasi -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: var(--font-lg); color: var(--academic-text); font-family: 'Playfair Display', serif;">Info Aplikasi</h5>
                        <p class="card-text text-muted mb-3" style="font-size: var(--font-sm); line-height: 1.5;">Informasi tentang aplikasi</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.info-aplikasi') }}" class="btn w-100" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: 2.5rem; color: var(--academic-primary);">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: var(--font-lg); color: var(--academic-text); font-family: 'Playfair Display', serif;">Pengaturan</h5>
                        <p class="card-text text-muted mb-3" style="font-size: var(--font-sm); line-height: 1.5;">Edit semua fitur dan pengaturan aplikasi</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.unified-edit.index') }}" class="btn w-100" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
