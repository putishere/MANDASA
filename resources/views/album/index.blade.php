@extends('layouts.app')

@section('title', 'Album Pondok')

@section('content')
<style>
    .album-card {
        background: white;
        border-radius: 15px;
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    .album-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(40, 167, 69, 0.2);
    }
    .album-image-wrapper {
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .album-image {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 250px;
        object-fit: contain;
        object-position: center;
    }
    .album-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #28a745;
        font-size: 4rem;
    }
    .album-title {
        font-weight: 600;
        color: #28a745;
        margin-bottom: 0.5rem;
    }
    .album-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .album-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    .kategori-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #28a745;
        color: white;
    }
</style>

<div class="container">
    <div class="mb-4">
        <h2 class="text-success">
            <i class="bi bi-images"></i> Album Pondok Pesantren
        </h2>
        <p class="text-muted">Galeri foto kegiatan dan aktivitas di Pondok Pesantren HS Al-Fakkar</p>
    </div>

    @if($albums && $albums->count() > 0)
        <div class="row g-3 g-md-4">
            @foreach($albums as $album)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card album-card h-100 shadow-sm">
                    <div class="album-image-wrapper position-relative">
                        @php
                            $fotoUrl = $album->foto_url;
                        @endphp
                        @if($fotoUrl)
                            <img src="{{ asset('storage/' . $fotoUrl) }}" class="album-image" alt="{{ $album->judul }}" loading="lazy">
                        @else
                            <div class="album-placeholder">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                        @if($album->kategori)
                            <span class="badge bg-success kategori-badge album-badge">
                                {{ ucfirst($album->kategori) }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="album-title">{{ $album->judul }}</h5>
                        @if($album->deskripsi)
                            <p class="album-description flex-grow-1">{{ \Illuminate\Support\Str::limit($album->deskripsi, 80) }}</p>
                        @endif
                        <small class="text-muted">
                            <i class="bi bi-images"></i> {{ $album->fotos->count() }} foto
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card album-card shadow">
            <div class="card-body p-5 text-center">
                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada album yang tersedia. Album akan ditampilkan setelah admin mengupload foto.</p>
            </div>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('santri.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

