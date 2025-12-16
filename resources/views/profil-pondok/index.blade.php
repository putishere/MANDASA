@extends('layouts.app')

@section('title', 'Profil Pondok')

@section('content')
<style>
    .pondok-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 15px 15px 0 0;
        text-align: center;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .pondok-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .pondok-header h3 {
        font-size: 1.3rem;
        font-weight: 400;
        opacity: 0.95;
        margin: 0;
    }
    .pondok-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .logo-container {
        text-align: center;
        padding: 2rem 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        margin: 1.5rem;
        border-radius: 12px;
    }
    .logo-container img {
        max-width: clamp(200px, 40vw, 300px);
        height: auto;
        background: transparent;
    }
    .section-card {
        background: #f8f9fa;
        border-left: 4px solid #28a745;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    .section-card:hover {
        background: #f0f7f4;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.15);
        transform: translateY(-2px);
    }
    .section-card h4 {
        color: #28a745;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-card h4 i {
        font-size: 1.4rem;
    }
    .section-card h5 {
        color: #28a745;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-card h5 i {
        font-size: 1.2rem;
    }
    .section-content {
        color: #495057;
        text-align: justify;
        line-height: 1.8;
        font-size: 0.95rem;
        margin: 0;
    }
    .section-content ul {
        padding-left: 1.5rem;
        margin: 0.5rem 0;
    }
    .section-content li {
        margin-bottom: 0.5rem;
    }
    .logo-placeholder {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 50%;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-placeholder i {
        font-size: 80px;
        color: #28a745;
    }
    @media (max-width: 768px) {
        .pondok-header {
            padding: 2rem 1rem;
        }
        .pondok-header h1 {
            font-size: 2rem;
        }
        .pondok-header h3 {
            font-size: 1.1rem;
        }
        .section-card {
            padding: 1.25rem;
        }
        .logo-container {
            margin: 1rem;
            padding: 1.5rem 1rem;
        }
    }
</style>

<div class="container">
    <div class="mb-4">
        <div class="page-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: clamp(1rem, 2vw, 1.5rem); border-radius: 15px; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
            <h2 style="font-size: clamp(1.25rem, 3vw, 1.75rem); margin: 0; font-weight: 600;">
                <i class="bi bi-building"></i> Profil Pondok Pesantren
            </h2>
        </div>
    </div>

    <div class="card pondok-card shadow-lg">
        <div class="pondok-header">
            <h1>{{ $profil->nama_pondok ?? 'PP HS AL-FAKKAR' }}</h1>
            <h3>{{ $profil->subtitle ?? 'Pondok Pesantren HS Al-Fakkar' }}</h3>
        </div>

        <div class="card-body p-4">
            <!-- Logo Pondok -->
            <div class="logo-container">
                @php
                    $profilFresh = $profil->fresh();
                    $logoExists = $profilFresh->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profilFresh->logo);
                @endphp
                @if($logoExists)
                    <img src="{{ asset('storage/' . $profilFresh->logo) }}?v={{ time() }}" alt="Logo {{ $profilFresh->nama_pondok }}" class="img-fluid" style="background: transparent; max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <p class="text-success mt-3 mb-0">
                        <i class="bi bi-check-circle"></i> Logo Pondok
                    </p>
                @else
                    <div class="logo-placeholder">
                        <i class="bi bi-building"></i>
                    </div>
                    <p class="text-muted mt-3 mb-1">Logo Pondok</p>
                    <small class="text-muted">Logo belum diupload</small>
                    @if($profilFresh->logo)
                        <div class="alert alert-warning mt-3" style="font-size: 0.875rem;">
                            <i class="bi bi-exclamation-triangle"></i> Logo tidak ditemukan di storage.
                            <br><small>Pastikan storage link sudah dibuat: <code>php artisan storage:link</code></small>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Artikel Tentang Pondok -->
            @if($profil->tentang)
                <div class="section-card">
                    <h4>
                        <i class="bi bi-book-fill"></i>
                        Tentang Pondok Pesantren
                    </h4>
                    <div class="section-content">
                        {!! nl2br(e($profil->tentang)) !!}
                    </div>
                </div>
            @endif
            
            @if($profil->visi)
                <div class="section-card">
                    <h5>
                        <i class="bi bi-eye-fill"></i>
                        Visi
                    </h5>
                    <div class="section-content">
                        {!! nl2br(e($profil->visi)) !!}
                    </div>
                </div>
            @endif

            @if($profil->misi)
                <div class="section-card">
                    <h5>
                        <i class="bi bi-bullseye"></i>
                        Misi
                    </h5>
                    <div class="section-content">
                        {!! nl2br(e($profil->misi)) !!}
                    </div>
                </div>
            @endif

            @if($profil->program_unggulan)
                <div class="section-card">
                    <h5>
                        <i class="bi bi-star-fill"></i>
                        Program Unggulan
                    </h5>
                    <div class="section-content">
                        {!! nl2br(e($profil->program_unggulan)) !!}
                    </div>
                </div>
            @endif

            @if($profil->fasilitas)
                <div class="section-card">
                    <h5>
                        <i class="bi bi-building-check"></i>
                        Fasilitas
                    </h5>
                    <div class="section-content">
                        {!! nl2br(e($profil->fasilitas)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('santri.dashboard') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            @endif
        @endauth
    </div>
</div>
@endsection
