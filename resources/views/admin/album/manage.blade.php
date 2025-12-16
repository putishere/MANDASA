@extends('layouts.app')

@section('title', 'Kelola Album Pondok')

@section('content')
<style>
    .album-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: clamp(1rem, 2vw, 1.5rem);
        border-radius: 15px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .album-header h2 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        margin: 0;
        font-weight: 600;
    }
    .album-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .album-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .album-image-wrapper {
        width: 100%;
        overflow: hidden;
        background: #f8f9fa;
        position: relative;
        cursor: pointer;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        max-height: 400px;
    }
    .album-image-wrapper:hover {
        opacity: 0.9;
    }
    .album-image-wrapper:hover::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
    .album-image {
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 400px;
        display: block;
        object-fit: contain;
        object-position: center;
        transition: transform 0.3s ease;
    }
    .album-image-wrapper:hover .album-image {
        transform: scale(1.02);
    }
    .album-placeholder {
        width: 100%;
        min-height: 200px;
        max-height: 400px;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .album-placeholder {
        height: 200px;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .empty-state {
        padding: clamp(2rem, 5vw, 4rem) 1rem;
    }
    .empty-state i {
        font-size: clamp(3rem, 8vw, 5rem);
        color: #6c757d;
        opacity: 0.5;
    }
    .empty-state p {
        font-size: clamp(0.9rem, 2vw, 1.1rem);
        margin: 1rem 0;
    }
    .btn-add-photo {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: clamp(0.5rem, 1.5vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
        border-radius: 8px;
        white-space: nowrap;
    }
    @media (max-width: 576px) {
        .album-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .album-header h2 {
            width: 100%;
        }
        .btn-add-photo {
            width: 100%;
        }
    }
</style>

<div class="container">
    <div class="album-header d-flex justify-content-between align-items-center flex-wrap">
        <h2>
            <i class="bi bi-images"></i> Kelola Album Pondok
        </h2>
        <a href="{{ route('admin.album.create') }}" class="btn btn-light btn-add-photo">
            <i class="bi bi-plus-circle"></i> Tambah Foto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($albums->count() > 0)
        <div class="row g-3 g-md-4">
            @foreach($albums as $album)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card album-card h-100 shadow-sm">
                    <a href="{{ route('admin.album.show', $album->id) }}" class="album-image-wrapper text-decoration-none">
                        @php
                            $coverFoto = $album->coverFoto;
                            $firstFoto = $album->fotos->first();
                            $fotoUrl = $coverFoto ? $coverFoto->foto : ($firstFoto ? $firstFoto->foto : ($album->foto ?? null));
                        @endphp
                        @if($fotoUrl)
                            <img src="{{ asset('storage/' . $fotoUrl) }}" class="album-image" alt="{{ $album->judul }}" loading="lazy">
                        @else
                            <div class="album-placeholder">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2" style="font-size: clamp(0.9rem, 2vw, 1rem); font-weight: 600;">
                            {{ $album->judul }}
                        </h6>
                        <p class="card-text small text-muted mb-2">
                            <i class="bi bi-tag"></i> {{ $kategoriOptions[$album->kategori] ?? $album->kategori }}
                        </p>
                        @if($album->deskripsi)
                            <p class="card-text small text-muted mb-2 flex-grow-1" style="font-size: clamp(0.75rem, 1.5vw, 0.875rem);">
                                {{ \Illuminate\Support\Str::limit($album->deskripsi, 50) }}
                            </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="badge {{ $album->is_active ? 'bg-success' : 'bg-secondary' }}" style="font-size: clamp(0.7rem, 1.5vw, 0.8rem);">
                                {{ $album->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="text-muted small" style="font-size: clamp(0.7rem, 1.5vw, 0.8rem);">#{{ $album->urutan }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.album.edit', $album->id) }}" class="btn btn-sm btn-outline-primary" style="font-size: clamp(0.75rem, 1.5vw, 0.875rem);">
                                <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                            </a>
                            <form action="{{ route('admin.album.destroy', $album->id) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size: clamp(0.75rem, 1.5vw, 0.875rem);">
                                    <i class="bi bi-trash"></i> <span class="d-none d-sm-inline">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($albums->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $albums->links() }}
            </div>
        @endif
    @else
        <div class="card shadow-sm">
            <div class="card-body empty-state text-center">
                <i class="bi bi-images"></i>
                <p class="text-muted">Belum ada foto album. Silakan tambah foto pertama.</p>
                <a href="{{ route('admin.album.create') }}" class="btn btn-success btn-add-photo">
                    <i class="bi bi-plus-circle"></i> Tambah Foto
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

