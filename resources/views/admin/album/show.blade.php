@extends('layouts.app')

@section('title', 'Detail Album - ' . $album->judul)

@section('content')
<style>
    .album-detail-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: clamp(1rem, 2vw, 1.5rem);
        border-radius: 15px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .album-detail-header h2 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        margin: 0;
        font-weight: 600;
    }
    .album-detail-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .album-detail-card-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: clamp(1rem, 2vw, 1.5rem);
        font-size: clamp(1.1rem, 2.5vw, 1.35rem);
        font-weight: 600;
    }
    .album-detail-card-body {
        padding: clamp(1.25rem, 3vw, 2rem);
    }
    .info-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 1rem;
        padding: clamp(0.75rem, 1.5vw, 1rem) 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: start;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: clamp(0.875rem, 2vw, 1rem);
    }
    .info-value {
        color: #212529;
        font-size: clamp(0.875rem, 2vw, 1rem);
        word-break: break-word;
    }
    .btn-action {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: clamp(0.5rem, 1.5vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
        border-radius: 8px;
        white-space: nowrap;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .foto-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .foto-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
    }
    .foto-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .foto-item.cover-foto {
        border: 3px solid #28a745;
    }
    .foto-item.cover-foto::before {
        content: 'Foto Profil';
        position: absolute;
        top: 10px;
        right: 10px;
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    .foto-image-wrapper {
        width: 100%;
        overflow: hidden;
        background: #f8f9fa;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        max-height: 500px;
    }
    .foto-image {
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 500px;
        display: block;
        object-fit: contain;
        object-position: center;
    }
    .foto-actions {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        gap: 5px;
        z-index: 2;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .foto-item:hover .foto-actions {
        opacity: 1;
    }
    .foto-action-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .foto-action-btn:hover {
        transform: scale(1.1);
    }
    .btn-set-cover {
        background: #28a745;
        color: white;
    }
    .btn-edit-foto {
        background: #17a2b8;
        color: white;
    }
    .btn-delete-foto {
        background: #dc3545;
        color: white;
    }
    .foto-info {
        padding: 1rem;
        background: #f8f9fa;
        border-top: 2px solid #28a745;
    }
    .cover-foto .foto-info {
        display: block;
    }
    .foto-item:not(.cover-foto) .foto-info {
        display: none;
    }
    .foto-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        color: #212529;
    }
    .foto-description {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }
    .add-foto-form {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .info-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .info-label {
            font-weight: 700;
            font-size: 0.875rem;
        }
        .foto-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
    }
</style>

<div class="container">
    <div class="album-detail-header d-flex justify-content-between align-items-center flex-wrap">
        <h2>
            <i class="bi bi-images"></i> Detail Album - {{ $album->judul }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.album.edit', $album->id) }}" class="btn btn-warning btn-action">
                <i class="bi bi-pencil"></i> Edit Album
            </a>
            <a href="{{ route('admin.album.manage') }}" class="btn btn-secondary btn-action">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Informasi Album -->
    <div class="album-detail-card">
        <div class="album-detail-card-header">
            <i class="bi bi-info-circle"></i> Informasi Album
        </div>
        <div class="album-detail-card-body">
            <div class="info-row">
                <div class="info-label">Judul</div>
                <div class="info-value"><strong>{{ $album->judul }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Kategori</div>
                <div class="info-value">
                    <span class="badge bg-success">
                        {{ $kategoriOptions[$album->kategori] ?? $album->kategori }}
                    </span>
                </div>
            </div>
            @if($album->deskripsi)
            <div class="info-row">
                <div class="info-label">Deskripsi</div>
                <div class="info-value">{{ $album->deskripsi }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="badge {{ $album->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $album->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Foto</div>
                <div class="info-value"><strong>{{ $album->fotos->count() }}</strong> foto</div>
            </div>
        </div>
    </div>

    <!-- Form Tambah Foto -->
    <div class="album-detail-card">
        <div class="album-detail-card-header">
            <i class="bi bi-plus-circle"></i> Tambah Foto Baru
        </div>
        <div class="album-detail-card-body">
            <form action="{{ route('admin.album.fotos.store', $album->id) }}" method="POST" enctype="multipart/form-data" class="add-foto-form">
                @csrf
                <div class="mb-3">
                    <label for="fotos" class="form-label">Pilih Foto <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('fotos.*') is-invalid @enderror" id="fotos" name="fotos[]" accept="image/jpeg,image/png,image/jpg,image/gif" multiple required>
                    <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 10MB per foto. Bisa pilih lebih dari satu foto.</div>
                    @error('fotos.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul (Opsional)</label>
                    <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul untuk semua foto">
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2" placeholder="Deskripsi untuk semua foto"></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-upload"></i> Upload Foto
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Foto -->
    <div class="album-detail-card">
        <div class="album-detail-card-header">
            <i class="bi bi-images"></i> Daftar Foto ({{ $album->fotos->count() }})
        </div>
        <div class="album-detail-card-body">
            @if($album->fotos->count() > 0)
                <div class="foto-grid">
                    @foreach($album->fotos as $foto)
                    <div class="foto-item {{ $foto->is_cover ? 'cover-foto' : '' }}">
                        <div class="foto-image-wrapper">
                            <img src="{{ asset('storage/' . $foto->foto) }}" class="foto-image" alt="{{ $foto->judul ?? 'Foto' }}" loading="lazy">
                            <div class="foto-actions">
                                @if(!$foto->is_cover)
                                <form action="{{ route('admin.album.fotos.set-cover', [$album->id, $foto->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="foto-action-btn btn-set-cover" title="Jadikan Foto Profil">
                                        <i class="bi bi-star"></i>
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="foto-action-btn btn-edit-foto" data-bs-toggle="modal" data-bs-target="#editFotoModal{{ $foto->id }}" title="Edit Foto">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.album.fotos.destroy', [$album->id, $foto->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="foto-action-btn btn-delete-foto" title="Hapus Foto">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($foto->is_cover && ($foto->judul || $foto->deskripsi))
                        <div class="foto-info">
                            @if($foto->judul)
                            <div class="foto-title">{{ $foto->judul }}</div>
                            @endif
                            @if($foto->deskripsi)
                            <div class="foto-description">{{ $foto->deskripsi }}</div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Modal Edit Foto -->
                    <div class="modal fade" id="editFotoModal{{ $foto->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Foto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.album.fotos.update', [$album->id, $foto->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3 text-center">
                                            <img src="{{ asset('storage/' . $foto->foto) }}" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="judul{{ $foto->id }}" class="form-label">Judul</label>
                                            <input type="text" class="form-control" id="judul{{ $foto->id }}" name="judul" value="{{ $foto->judul }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="deskripsi{{ $foto->id }}" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi{{ $foto->id }}" name="deskripsi" rows="3">{{ $foto->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto{{ $foto->id }}" class="form-label">Ganti Foto (Opsional)</label>
                                            <input type="file" class="form-control" id="foto{{ $foto->id }}" name="foto" accept="image/jpeg,image/png,image/jpg,image/gif">
                                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-images" style="font-size: 4rem; color: #6c757d; opacity: 0.5;"></i>
                    <p class="text-muted mt-3">Belum ada foto dalam album ini. Silakan tambah foto pertama.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
