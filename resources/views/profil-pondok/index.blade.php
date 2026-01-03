@extends('layouts.app')

@section('title', 'Profil Pondok')

@section('content')
<style>
    .pondok-header {
        background: var(--academic-primary);
        color: white;
        padding: 2rem 1.5rem;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    .pondok-header .logo-wrapper {
        margin-bottom: 1rem;
    }
    .pondok-header .logo-wrapper img {
        max-width: 120px;
        max-height: 120px;
        width: auto;
        height: auto;
        background: white;
        border-radius: 50%;
        padding: 8px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .pondok-header h1 {
        font-size: var(--font-2xl);
        font-weight: 600;
        font-family: 'Playfair Display', serif;
        margin-bottom: 0.5rem;
        color: white;
    }
    .pondok-header h3 {
        font-size: var(--font-md);
        font-weight: 400;
        opacity: 0.95;
        margin: 0;
        color: white;
    }
    .pondok-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        border: 1px solid var(--academic-border);
        margin-bottom: 2rem;
    }
    .logo-container {
        text-align: center;
        padding: 1.5rem 1rem;
        background: var(--academic-green-very-light);
        margin: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--academic-border);
    }
    .logo-container img {
        max-width: clamp(120px, 25vw, 180px);
        max-height: 180px;
        width: auto;
        height: auto;
        background: transparent;
        object-fit: contain;
        border-radius: var(--radius-sm);
    }
    .section-card {
        background: #ffffff;
        border-left: 4px solid var(--academic-primary);
        border: 1px solid var(--academic-border);
        border-left-width: 4px;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
        transition: all 0.2s ease;
    }
    .section-card:hover {
        box-shadow: var(--shadow-md);
        border-left-color: var(--academic-accent);
    }
    .section-card h4 {
        color: var(--academic-primary);
        font-weight: 600;
        font-family: 'Playfair Display', serif;
        font-size: var(--font-xl);
        margin-bottom: var(--spacing-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-card h4 i {
        font-size: var(--font-xl);
    }
    .section-card h5 {
        color: var(--academic-primary);
        font-weight: 600;
        font-family: 'Playfair Display', serif;
        font-size: var(--font-lg);
        margin-bottom: var(--spacing-sm);
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
        background: var(--academic-secondary);
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
        color: var(--academic-primary);
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
            padding: 1rem;
        }
        .logo-container img {
            max-width: clamp(100px, 20vw, 150px) !important;
            max-height: 150px !important;
        }
    }
</style>

<div class="container">
    <div class="mb-4">
        <div class="page-header" style="background: var(--academic-primary); color: white; padding: 1.25rem 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
            <h2 style="font-size: var(--font-xl); margin: 0; font-weight: 600; font-family: 'Playfair Display', serif;">
                <i class="bi bi-building"></i> Profil Pondok Pesantren
            </h2>
        </div>
    </div>

    <div class="card pondok-card">
        <div class="pondok-header">
            @php
                $profilFresh = $profil->fresh();
                $logoExists = $profilFresh->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profilFresh->logo);
            @endphp
            @if($logoExists)
                <div class="logo-wrapper">
                    <img src="{{ asset('storage/' . $profilFresh->logo) }}?v={{ time() }}" alt="Logo {{ $profilFresh->nama_pondok }}">
                </div>
            @endif
            <h1>{{ $profil->nama_pondok ?? 'PP HS AL-FAKKAR' }}</h1>
            <h3>{{ $profil->subtitle ?? 'Pondok Pesantren HS Al-Fakkar' }}</h3>
        </div>

        <div class="card-body p-4">
            <!-- Logo Pondok (jika tidak di header) -->
            @if(!$logoExists)
                <div class="logo-container">
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
                </div>
            @endif

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
