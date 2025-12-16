@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<style>
    .welcome-hero {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
        border-radius: 25px;
        padding: 4rem 2rem;
        margin: 2rem auto;
        max-width: 1200px;
        position: relative;
        overflow: hidden;
    }
    .welcome-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(40, 167, 69, 0.05) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    .welcome-icon {
        font-size: 5rem;
        color: #28a745;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .welcome-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .welcome-card:hover {
        transform: translateY(-5px);
    }
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .feature-item {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .feature-item i {
        font-size: 2.5rem;
        color: #28a745;
        margin-bottom: 1rem;
    }
    .btn-login {
        padding: 1rem 3rem;
        font-size: 1.2rem;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }
</style>

<div class="container">
    <div class="welcome-hero">
        <div class="welcome-card">
            <div class="text-center">
                <div class="welcome-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h1 class="text-success mb-3" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: bold;">
                    MANAGEMEN DATA SANTRI
                </h1>
                <h2 class="text-success mb-4" style="font-size: clamp(1.1rem, 3vw, 1.4rem); font-weight: 600;">
                    PP HS AL-FAKKAR
                </h2>
                <div class="mb-4">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-house-door"></i> Selamat Datang
                    </span>
                </div>
                <p class="lead text-muted mb-4" style="line-height: 1.8;">
                    Aplikasi manajemen data santri Pondok Pesantren HS Al-Fakkar.<br>
                    Selamat datang di halaman beranda aplikasi.
                </p>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-success btn-login me-2">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    @else
                        <a href="{{ route('santri.dashboard') }}" class="btn btn-success btn-login me-2">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-success btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk ke Aplikasi
                    </a>
                @endauth
            </div>
        </div>

        <div class="feature-grid">
            <div class="feature-item">
                <i class="bi bi-people-fill"></i>
                <h5 class="text-success mt-2">Data Santri</h5>
                <p class="text-muted small mb-0">Kelola data santri dengan mudah</p>
            </div>
            <div class="feature-item">
                <i class="bi bi-building"></i>
                <h5 class="text-success mt-2">Profil Pondok</h5>
                <p class="text-muted small mb-0">Informasi lengkap pondok pesantren</p>
            </div>
            <div class="feature-item">
                <i class="bi bi-shield-check"></i>
                <h5 class="text-success mt-2">Aman & Terpercaya</h5>
                <p class="text-muted small mb-0">Sistem keamanan terjamin</p>
            </div>
            <div class="feature-item">
                <i class="bi bi-speedometer2"></i>
                <h5 class="text-success mt-2">Dashboard Modern</h5>
                <p class="text-muted small mb-0">Interface yang user-friendly</p>
            </div>
        </div>
    </div>
</div>
@endsection
