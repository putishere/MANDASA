@extends('layouts.app')

@section('title', 'Tambah Foto Album')

@section('content')
<style>
    .form-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: clamp(1rem, 2vw, 1.5rem);
        border-radius: 15px 15px 0 0;
    }
    .form-header h4 {
        font-size: clamp(1.1rem, 2.5vw, 1.5rem);
        margin: 0;
        font-weight: 600;
    }
    .form-label {
        font-weight: 600;
        font-size: clamp(0.9rem, 2vw, 1rem);
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: clamp(0.5rem, 1.5vw, 0.75rem);
        border-radius: 8px;
    }
    .btn-form {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: clamp(0.5rem, 1.5vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
        border-radius: 8px;
    }
    @media (max-width: 576px) {
        .btn-form-group {
            flex-direction: column;
        }
        .btn-form-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }
    }
</style>

<div class="container">
    <div class="mb-4">
        <a href="{{ route('admin.album.manage') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="form-header">
            <h4>
                <i class="bi bi-plus-circle"></i> Tambah Foto Album
            </h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.album.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Foto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/gif" required onchange="validateFileSize(this)">
                    <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 10MB</div>
                    @error('foto')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div id="fileSizeError" class="text-danger small mt-1" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i> Ukuran file melebihi 10MB. Silakan pilih file yang lebih kecil.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="urutan" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('urutan') is-invalid @enderror" id="urutan" name="urutan" value="{{ old('urutan', 0) }}" min="0">
                        @error('urutan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-form-group">
                    <a href="{{ route('admin.album.manage') }}" class="btn btn-secondary btn-form">Batal</a>
                    <button type="submit" class="btn btn-success btn-form">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateFileSize(input) {
    const fileSizeError = document.getElementById('fileSizeError');
    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
    
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size;
        
        if (fileSize > maxSize) {
            fileSizeError.style.display = 'block';
            input.value = ''; // Clear the input
            input.classList.add('is-invalid');
        } else {
            fileSizeError.style.display = 'none';
            input.classList.remove('is-invalid');
        }
    }
}
</script>
@endsection

