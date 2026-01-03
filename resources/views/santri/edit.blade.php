@extends('layouts.app')

@section('title', 'Edit Data Santri')

@section('content')
<style>
    .page-header {
        background: var(--academic-primary);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-lg);
        box-shadow: var(--shadow-md);
    }
    .page-header h1 {
        font-size: var(--font-2xl);
        margin: 0;
    }
    .page-header h2 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        margin: 0;
        font-weight: 600;
    }
    .card-header {
        background: var(--academic-primary);
        color: white;
        font-size: var(--font-lg);
        padding: var(--spacing-md) var(--spacing-lg);
        font-weight: 600;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }
    .card {
        border: 1px solid var(--academic-border);
        box-shadow: var(--shadow-sm);
        border-radius: var(--radius-md);
        overflow: hidden;
    }
    .form-label {
        font-weight: 600;
        font-size: clamp(0.9rem, 2vw, 1rem);
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .form-control, .form-select {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: clamp(0.6rem, 1.5vw, 0.85rem);
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--academic-primary);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    }
    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        border-left: none;
        border-radius: 0 8px 8px 0;
    }
    .input-group .form-control {
        border-right: none;
        border-radius: 8px 0 0 8px;
    }
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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
        padding: 2rem 1.5rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .photo-upload-container:hover {
        border-color: var(--academic-primary);
        background: #f0f8f4;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
    }
    .btn-upload-photo {
        background: var(--academic-primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
    }
    .btn-upload-photo:hover {
        background: #218838;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
    }
    .photo-info-text {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: #6c757d;
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
    .current-photo-preview {
        margin-top: 1rem;
        text-align: center;
    }
    .current-photo-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 10px;
        border: 2px solid #28a745;
        object-fit: cover;
    }
    .btn-crop {
        background: var(--academic-primary);
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
            <i class="bi bi-pencil"></i> Edit Data Santri
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

    <form action="{{ route('santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Kolom Kiri -->
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $santri->name) }}" required placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '') }}" required>
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="alamat_santri" class="form-label">Alamat Santri <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat_santri" name="alamat_santri" rows="4" required placeholder="Masukkan alamat lengkap">{{ old('alamat_santri', $santri->santriDetail->alamat_santri ?? '') }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto</label>
                            <div class="photo-upload-container">
                                <input type="file" class="form-control d-none" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" onchange="handleFileSelect(this)">
                                <label for="foto" class="btn-upload-photo" style="cursor: pointer;">
                                    <i class="bi bi-cloud-upload me-2"></i> Pilih Foto
                                </label>
                                <p class="photo-info-text mb-0">
                                    <i class="bi bi-info-circle me-1"></i> Format: JPEG, PNG, atau JPG. Maksimal 10MB.
                                </p>
                                
                                @if($santri->santriDetail && $santri->santriDetail->foto)
                                    <div class="current-photo-preview mt-3">
                                        <p class="text-muted small mb-2">Foto saat ini:</p>
                                        <img src="{{ asset('storage/' . $santri->santriDetail->foto) }}" alt="Foto saat ini" id="current-photo">
                                        <p class="text-muted small mt-2">
                                            <a href="{{ asset('storage/' . $santri->santriDetail->foto) }}" target="_blank">Lihat Full Size</a>
                                        </p>
                                    </div>
                                @endif
                                
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
                    </div>
                    
                    <!-- Kolom Kanan -->
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $santri->username) }}" required placeholder="Masukkan username">
                        </div>
                        
                        <div class="mb-4">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $santri->santriDetail->tahun_masuk ?? date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" required>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i> Contoh: {{ date('Y') }}
                            </small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="nis" class="form-label">NIS (Nomor Induk Santri) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $santri->santriDetail->nis ?? '') }}" required placeholder="Masukkan NIS">
                        </div>
                        
                        <div class="mb-4">
                            <label for="nomor_hp_santri" class="form-label">Nomor HP Santri</label>
                            <input type="text" class="form-control" id="nomor_hp_santri" name="nomor_hp_santri" value="{{ old('nomor_hp_santri', $santri->santriDetail->nomor_hp_santri ?? '') }}" placeholder="Masukkan nomor HP">
                        </div>
                        
                        <div class="mb-4">
                            <label for="status_santri" class="form-label">Status Santri <span class="text-danger">*</span></label>
                            <select class="form-select" id="status_santri" name="status_santri" required>
                                <option value="aktif" {{ old('status_santri', $santri->santriDetail->status_santri ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="boyong" {{ old('status_santri', $santri->santriDetail->status_santri ?? '') === 'boyong' ? 'selected' : '' }}>Boyong</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Wali</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_wali" class="form-label">Nama Wali <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_wali" name="nama_wali" value="{{ old('nama_wali', $santri->santriDetail->nama_wali ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nomor_hp_wali" class="form-label">Nomor HP Wali</label>
                        <input type="text" class="form-control" id="nomor_hp_wali" name="nomor_hp_wali" value="{{ old('nomor_hp_wali', $santri->santriDetail->nomor_hp_wali ?? '') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="alamat_wali" class="form-label">Alamat Wali <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_wali" name="alamat_wali" rows="3" required>{{ old('alamat_wali', $santri->santriDetail->alamat_wali ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-between flex-wrap btn-form-group">
            <a href="{{ route('santri.index') }}" class="btn btn-secondary btn-form">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-form" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white;">
                <i class="bi bi-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection

