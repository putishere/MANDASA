@extends('layouts.app')

@section('title', 'Dashboard Santri')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.25);
        border: none;
        overflow: hidden;
        position: relative;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    .welcome-banner .card-body {
        position: relative;
        z-index: 1;
    }
    .dashboard-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .dashboard-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.25);
    }
    .card-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.25rem;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .dashboard-card:hover .card-icon-wrapper {
        transform: scale(1.15) rotate(5deg);
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .card-icon {
        font-size: 2.5rem;
        color: #28a745;
        transition: all 0.3s ease;
    }
    .dashboard-card:hover .card-icon {
        color: #ffffff;
        transform: scale(1.1);
    }
    .card-title {
        font-weight: 600;
        letter-spacing: 0.3px;
        color: #212529;
        margin-bottom: 0.75rem;
    }
    .card-text {
        line-height: 1.6;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .card-body {
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }
    .btn-card {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        margin-top: auto;
    }
    .btn-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(40, 167, 69, 0.4);
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
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
                    <h3 class="mb-0" style="font-size: clamp(1.3rem, 4vw, 1.8rem); font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
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
