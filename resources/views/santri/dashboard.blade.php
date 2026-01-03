@extends('layouts.app')

@section('title', 'Dashboard Santri')

@section('content')
<style>
    .welcome-banner {
        background: var(--academic-primary);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        border: none;
        padding: 1.25rem 1.5rem;
        margin-bottom: var(--spacing-xl);
    }
    .welcome-banner .card-body {
        position: relative;
    }
    .dashboard-card {
        transition: all 0.2s ease;
        border: 1px solid var(--academic-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        background: #ffffff;
        box-shadow: var(--shadow-sm);
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(21, 87, 36, 0.15);
    }
    .card-icon-wrapper {
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        background: var(--academic-green-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 2px solid var(--academic-border);
    }
    .dashboard-card:hover .card-icon-wrapper {
        background: var(--academic-primary);
        border-color: var(--academic-primary);
    }
    .card-icon {
        font-size: 2rem;
        color: var(--academic-primary);
        transition: all 0.2s ease;
    }
    .dashboard-card:hover .card-icon {
        color: #ffffff;
    }
    .card-title {
        font-weight: 600;
        letter-spacing: 0.3px;
        color: var(--academic-text);
        margin-bottom: 0.75rem;
        font-family: 'Playfair Display', serif;
        font-size: var(--font-lg);
    }
    .card-text {
        line-height: 1.6;
        color: #6c757d;
        font-size: var(--font-sm);
        margin-bottom: 1.25rem;
    }
    .card-body {
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }
    .btn-card {
        background: var(--academic-primary);
        border: 1px solid var(--academic-primary);
        border-radius: var(--radius-sm);
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        font-size: var(--font-sm);
        transition: all 0.2s ease;
        margin-top: auto;
    }
    .btn-card:hover {
        background: var(--academic-accent);
        border-color: var(--academic-accent);
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
    }
    .btn-card:active {
        transform: translateY(0);
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem 1rem;
        }
        .card-icon-wrapper {
            width: 70px;
            height: 70px;
        }
        .card-icon {
            font-size: 2rem;
        }
    }
</style>

<div class="container-fluid px-2 px-md-3">
    <!-- Welcome Banner -->
    <div class="row mb-4 mb-md-5">
        <div class="col-12">
            <div class="card welcome-banner text-white">
                <div class="card-body text-center py-4 py-md-5 px-3 px-md-4">
                    <h3 class="mb-0" style="font-size: clamp(1.3rem, 4vw, 1.8rem); font-weight: 600; font-family: 'Playfair Display', serif;">
                        <i class="bi bi-person-badge me-2"></i> Selamat Datang, <strong>{{ $user->name }}</strong>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4 g-md-4">
        <!-- Profil Santri -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-person-circle card-icon"></i>
                    </div>
                    <h5 class="card-title" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Profil Santri</h5>
                    <p class="card-text">Lihat dan cetak data profil Anda</p>
                    <a href="{{ route('santri.profil') }}" class="btn btn-card text-white w-100">
                        <i class="bi bi-arrow-right-circle me-2"></i> Buka
                    </a>
                </div>
            </div>
        </div>

        <!-- Profil Pondok -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-building card-icon"></i>
                    </div>
                    <h5 class="card-title" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Profil Pondok</h5>
                    <p class="card-text">Tentang PP HS Al-Fakkar</p>
                    <a href="{{ route('santri.profil-pondok') }}" class="btn btn-card text-white w-100">
                        <i class="bi bi-arrow-right-circle me-2"></i> Buka
                    </a>
                </div>
            </div>
        </div>

        <!-- Album Pondok -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-images card-icon"></i>
                    </div>
                    <h5 class="card-title" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Album Pondok</h5>
                    <p class="card-text">Galeri foto kegiatan pondok</p>
                    <a href="{{ route('santri.album-pondok') }}" class="btn btn-card text-white w-100">
                        <i class="bi bi-arrow-right-circle me-2"></i> Buka
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Aplikasi -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-info-circle-fill card-icon"></i>
                    </div>
                    <h5 class="card-title" style="font-size: clamp(1.1rem, 3vw, 1.3rem);">Info Aplikasi</h5>
                    <p class="card-text">Informasi tentang aplikasi</p>
                    <a href="{{ route('santri.info-aplikasi') }}" class="btn btn-card text-white w-100">
                        <i class="bi bi-arrow-right-circle me-2"></i> Buka
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
