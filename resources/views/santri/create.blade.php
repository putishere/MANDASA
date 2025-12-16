@extends('layouts.app')

@section('title', 'Tambah Santri Baru')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: clamp(1rem, 2vw, 1.5rem);
        border-radius: 15px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .page-header h2 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        margin: 0;
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        font-size: clamp(1rem, 2.5vw, 1.25rem);
        padding: clamp(0.75rem, 1.5vw, 1rem);
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
    .file-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .photo-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    .photo-upload-container:hover {
        border-color: #28a745;
        background: #f0f8f4;
    }
    .photo-preview-container {
        position: relative;
        margin-top: 1rem;
        display: none;
    }
    .photo-preview-container.active {
        display: block;
    }
    .photo-preview-wrapper {
        max-width: 100%;
        max-height: 400px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 10px;
        background: #000;
    }
    .photo-preview-wrapper img {
        max-width: 100%;
        max-height: 400px;
        display: block;
    }
    .photo-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-crop {
        background: #28a745;
        color: white;
        border: none;
    }
    .btn-crop:hover {
        background: #218838;
        color: white;
    }
    .btn-reset-photo {
        background: #6c757d;
        color: white;
        border: none;
    }
    .btn-reset-photo:hover {
        background: #5a6268;
        color: white;
    }
</style>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper = null;
    let currentPhotoFile = null;
    
    function handleFileSelect(input) {
        const file = input.files[0];
        const errorDiv = document.getElementById('file-error');
        const previewContainer = document.getElementById('photo-preview-container');
        const previewImg = document.getElementById('photo-preview');
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        // Reset previous state
        errorDiv.style.display = 'none';
        previewContainer.classList.remove('active');
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        if (!file) {
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            errorDiv.textContent = 'Ukuran file terlalu besar! Maksimal 10MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB';
            errorDiv.style.display = 'block';
            input.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            errorDiv.textContent = 'Format file tidak didukung! Gunakan JPEG, PNG, atau JPG.';
            errorDiv.style.display = 'block';
            input.value = '';
            return;
        }
        
        // Show preview
        currentPhotoFile = file;
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.classList.add('active');
            
            // Initialize cropper
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(previewImg, {
                aspectRatio: 3 / 4,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
        
        reader.readAsDataURL(file);
    }
    
    // Crop button handler
    document.getElementById('btn-crop').addEventListener('click', function() {
        if (!cropper) {
            return;
        }
        
        const canvas = cropper.getCroppedCanvas({
            width: 600,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        // Convert to blob
        canvas.toBlob(function(blob) {
            // Create a new File object from blob
            const file = new File([blob], currentPhotoFile.name, {
                type: currentPhotoFile.type,
                lastModified: Date.now()
            });
            
            // Create a new FileList-like object
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Replace the file input
            const fileInput = document.getElementById('foto');
            fileInput.files = dataTransfer.files;
            
            // Update preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            // Hide cropper
            cropper.destroy();
            cropper = null;
            
            // Show success message
            const errorDiv = document.getElementById('file-error');
            errorDiv.style.display = 'block';
            errorDiv.className = 'text-success mt-2';
            errorDiv.innerHTML = '<i class="bi bi-check-circle"></i> Foto berhasil dipotong!';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        }, currentPhotoFile.type, 0.9);
    });
    
    // Reset button handler
    document.getElementById('btn-reset-photo').addEventListener('click', function() {
        const fileInput = document.getElementById('foto');
        const previewContainer = document.getElementById('photo-preview-container');
        const errorDiv = document.getElementById('file-error');
        
        fileInput.value = '';
        previewContainer.classList.remove('active');
        errorDiv.style.display = 'none';
        currentPhotoFile = null;
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
    
    // Validate on form submit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('foto');
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const maxSize = 10 * 1024 * 1024;
                    
                    if (file.size > maxSize) {
                        e.preventDefault();
                        const errorDiv = document.getElementById('file-error');
                        errorDiv.textContent = 'Ukuran file terlalu besar! Maksimal 10MB.';
                        errorDiv.style.display = 'block';
                        errorDiv.className = 'text-danger mt-2';
                        return false;
                    }
                }
            });
        }
    });
</script>

<div class="container">
    <div class="page-header">
        <h2>
            <i class="bi bi-person-plus"></i> Tambah Santri Baru
        </h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <h5>Data Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tahun_masuk" class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" required>
                        <small class="text-muted">Contoh: {{ date('Y') }}</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="nis" class="form-label">NIS (Nomor Induk Santri) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="alamat_santri" class="form-label">Alamat Santri <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_santri" name="alamat_santri" rows="3" required>{{ old('alamat_santri') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nomor_hp_santri" class="form-label">Nomor HP Santri</label>
                        <input type="text" class="form-control" id="nomor_hp_santri" name="nomor_hp_santri" value="{{ old('nomor_hp_santri') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <div class="photo-upload-container">
                            <input type="file" class="form-control d-none" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" onchange="handleFileSelect(this)">
                            <label for="foto" class="btn btn-outline-success mb-2" style="cursor: pointer;">
                                <i class="bi bi-cloud-upload"></i> Pilih Foto
                            </label>
                            <p class="text-muted mb-0 small">
                                <i class="bi bi-info-circle"></i> Format: JPEG, PNG, atau JPG. Maksimal 10MB.
                            </p>
                            
                            <div id="file-error" class="text-danger mt-2" style="display: none;"></div>
                            
                            <!-- Preview dan Crop Area -->
                            <div class="photo-preview-container" id="photo-preview-container">
                                <div class="photo-preview-wrapper">
                                    <img id="photo-preview" src="" alt="Preview">
                                </div>
                                <div class="photo-actions">
                                    <button type="button" class="btn btn-sm btn-crop" id="btn-crop">
                                        <i class="bi bi-scissors"></i> Potong Foto
                                    </button>
                                    <button type="button" class="btn btn-sm btn-reset-photo" id="btn-reset-photo">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden input untuk cropped image -->
                            <input type="hidden" id="cropped-image" name="cropped_image">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_santri" class="form-label">Status Santri <span class="text-danger">*</span></label>
                        <select class="form-select" id="status_santri" name="status_santri" required>
                            <option value="aktif" {{ old('status_santri') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="boyong" {{ old('status_santri') === 'boyong' ? 'selected' : '' }}>Boyong</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>Data Wali</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_wali" class="form-label">Nama Wali <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_wali" name="nama_wali" value="{{ old('nama_wali') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nomor_hp_wali" class="form-label">Nomor HP Wali</label>
                        <input type="text" class="form-control" id="nomor_hp_wali" name="nomor_hp_wali" value="{{ old('nomor_hp_wali') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="alamat_wali" class="form-label">Alamat Wali <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_wali" name="alamat_wali" rows="3" required>{{ old('alamat_wali') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-between flex-wrap btn-form-group">
            <a href="{{ route('santri.index') }}" class="btn btn-secondary btn-form">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-success btn-form">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection

