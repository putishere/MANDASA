@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
    .dashboard-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
    }
    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #28a745, #20c997, #17a2b8);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(21, 87, 36, 0.4);
        border-color: rgba(40, 167, 69, 0.5) !important;
    }
    .dashboard-card:hover::before {
        opacity: 1;
    }
    .dashboard-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: 1.5rem;
    }
    .card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    .dashboard-card:hover .card-icon {
        transform: scale(1.1);
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
    .bg-gradient-green {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 50%, #20c997 100%);
        box-shadow: 0 6px 20px rgba(30, 126, 52, 0.5);
    }
    .bg-light-green {
        background: linear-gradient(135deg, #ffffff 0%, #e8f5e9 50%, #c8e6c9 100%);
        border: 2px solid rgba(40, 167, 69, 0.3);
    }
    .dashboard-card .btn {
        white-space: nowrap;
        font-size: clamp(0.875rem, 2vw, 0.95rem);
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(21, 87, 36, 0.4);
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: 2px solid #155724;
        color: white;
    }
    .dashboard-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(21, 87, 36, 0.6);
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
        border-color: #0d4217;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #155724 0%, #1e7e34 50%, #28a745 100%);
        border-radius: 15px;
        padding: 1.5rem 2rem;
        box-shadow: 0 8px 25px rgba(21, 87, 36, 0.6);
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.1); opacity: 0.9; }
    }
    .welcome-banner h3 {
        position: relative;
        z-index: 1;
        text-shadow: 0 3px 15px rgba(0,0,0,0.4), 0 1px 3px rgba(0,0,0,0.3);
        font-weight: 700;
    }
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-left: 6px solid #155724;
        border-top: 2px solid rgba(40, 167, 69, 0.2);
        border-right: 2px solid rgba(40, 167, 69, 0.2);
        border-bottom: 2px solid rgba(40, 167, 69, 0.2);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(21, 87, 36, 0.3);
    }
    .stat-card.warning {
        border-left-color: #ff8c00;
        border-top-color: rgba(255, 193, 7, 0.3);
        border-right-color: rgba(255, 193, 7, 0.3);
        border-bottom-color: rgba(255, 193, 7, 0.3);
    }
    .stat-card.warning:hover {
        box-shadow: 0 10px 25px rgba(255, 140, 0, 0.3);
    }
    .stat-number {
        font-weight: 800;
        letter-spacing: -1px;
        text-shadow: 0 3px 8px rgba(0,0,0,0.15);
        color: #155724 !important;
    }
    .stat-card.warning .stat-number {
        color: #b8860b !important;
    }
    .stat-label {
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #212529 !important;
    }
    .stat-card .card-icon.text-success {
        color: #155724 !important;
        filter: drop-shadow(0 2px 4px rgba(21, 87, 36, 0.3));
    }
    .stat-card.warning .card-icon.text-warning {
        color: #ff8c00 !important;
        filter: drop-shadow(0 2px 4px rgba(255, 140, 0, 0.3));
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
                        <h3 class="mb-0" style="font-size: clamp(1.1rem, 4vw, 1.5rem);">
                            <i class="bi bi-person-badge me-2"></i> Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
                        </h3>
                        <p class="mb-0 mt-2 opacity-90" style="font-size: clamp(0.875rem, 2vw, 1rem);">
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
                    <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h2 class="stat-number text-success mb-2" style="font-size: clamp(2rem, 5vw, 3rem);">{{ \App\Models\User::where('role', 'santri')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark" style="font-size: clamp(0.9rem, 2vw, 1rem);">Total Santri</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card dashboard-card stat-card shadow-sm">
                <div class="card-body text-center">
                    <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="stat-number text-success mb-2" style="font-size: clamp(2rem, 5vw, 3rem);">{{ \App\Models\SantriDetail::where('status_santri', 'aktif')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark" style="font-size: clamp(0.9rem, 2vw, 1rem);">Santri Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card dashboard-card stat-card warning shadow-sm">
                <div class="card-body text-center">
                    <div class="card-icon text-warning mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <h2 class="stat-number text-warning mb-2" style="font-size: clamp(2rem, 5vw, 3rem);">{{ \App\Models\SantriDetail::where('status_santri', 'boyong')->count() }}</h2>
                    <p class="stat-label mb-0 text-dark" style="font-size: clamp(0.9rem, 2vw, 1rem);">Santri Boyong</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Utama -->
    <div class="row g-3 g-md-4">
        <!-- Data Santri -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card bg-light-green shadow-sm">
                <div class="card-body text-center">
                    <div class="card-content-wrapper">
                        <div class="card-icon mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem); color: #155724; filter: drop-shadow(0 2px 4px rgba(21, 87, 36, 0.3));">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="card-title mb-2 fw-bold" style="font-size: clamp(1.1rem, 3vw, 1.3rem); color: #155724;">Data Santri</h5>
                        <p class="card-text mb-3" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.5; color: #495057; font-weight: 500;">Kelola data semua santri (CRUD)</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('santri.index') }}" class="btn btn-success w-100">
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
                        <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="card-title text-success mb-2 fw-bold" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Profil Pondok</h5>
                        <p class="card-text text-muted mb-3" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.5;">Kelola profil dan informasi pondok</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.profil-pondok') }}" class="btn btn-success w-100">
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
                        <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                            <i class="bi bi-images"></i>
                        </div>
                        <h5 class="card-title text-success mb-2 fw-bold" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Album Pondok</h5>
                        <p class="card-text text-muted mb-3" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.5;">Kelola galeri foto kegiatan pondok</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.album.manage') }}" class="btn btn-success w-100">
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
                        <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <h5 class="card-title text-success mb-2 fw-bold" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Info Aplikasi</h5>
                        <p class="card-text text-muted mb-3" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.5;">Informasi tentang aplikasi</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.info-aplikasi') }}" class="btn btn-success w-100">
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
                        <div class="card-icon text-success mb-3" style="font-size: clamp(2.5rem, 5vw, 3.5rem);">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h5 class="card-title text-success mb-2 fw-bold" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Pengaturan</h5>
                        <p class="card-text text-muted mb-3" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.5;">Edit semua fitur dan pengaturan aplikasi</p>
                    </div>
                    <div class="card-button-wrapper">
                        <a href="{{ route('admin.unified-edit.index') }}" class="btn btn-success w-100">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
