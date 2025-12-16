@extends('layouts.app')

@section('title', 'Pengaturan Tampilan Aplikasi')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
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
    .page-header p {
        font-size: clamp(0.875rem, 2vw, 1rem);
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }
    .card-header {
        font-size: clamp(1rem, 2.5vw, 1.25rem);
        padding: clamp(0.75rem, 1.5vw, 1rem);
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
    @media (max-width: 768px) {
        .btn-form-group {
            flex-direction: column;
        }
        .btn-form-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h2>
                    <i class="bi bi-gear"></i> Pengaturan Tampilan Aplikasi
                </h2>
                <p>Kelola tampilan dan konfigurasi aplikasi</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.app-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        @if($group === 'general')
                            <i class="bi bi-info-circle"></i> Informasi Umum
                        @elseif($group === 'appearance')
                            <i class="bi bi-palette"></i> Tampilan
                        @else
                            {{ ucfirst($group) }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($groupSettings as $setting)
                            <div class="col-md-6 mb-3">
                                <label for="setting_{{ $setting->key }}" class="form-label">
                                    {{ $setting->description ?? ucfirst(str_replace('_', ' ', $setting->key)) }}
                                    @if($setting->type === 'image')
                                        <span class="text-muted">(Gambar)</span>
                                    @endif
                                </label>

                                @if($setting->type === 'text')
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="setting_{{ $setting->key }}" 
                                        name="{{ $setting->key }}" 
                                        value="{{ old($setting->key, $setting->value) }}"
                                    >
                                @elseif($setting->type === 'color')
                                    <div class="input-group">
                                        <input 
                                            type="color" 
                                            class="form-control form-control-color" 
                                            id="setting_{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            value="{{ old($setting->key, $setting->value ?? '#28a745') }}"
                                            style="width: 80px; height: 38px;"
                                        >
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            value="{{ old($setting->key, $setting->value ?? '#28a745') }}"
                                            onchange="document.getElementById('setting_{{ $setting->key }}').value = this.value"
                                            placeholder="#28a745"
                                        >
                                    </div>
                                @elseif($setting->type === 'image')
                                    @if($setting->value)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $setting->value) }}" alt="Current image" class="img-thumbnail" style="max-height: 100px;">
                                            <p class="text-muted small mb-0">Gambar saat ini</p>
                                        </div>
                                    @endif
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        id="setting_{{ $setting->key }}" 
                                        name="{{ $setting->key }}"
                                        accept="image/*"
                                    >
                                    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                @else
                                    <textarea 
                                        class="form-control" 
                                        id="setting_{{ $setting->key }}" 
                                        name="{{ $setting->key }}" 
                                        rows="3"
                                    >{{ old($setting->key, $setting->value) }}</textarea>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap btn-form-group">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-form">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success btn-form">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Preview image before upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxHeight = '100px';
                    
                    const existingPreview = this.parentElement.querySelector('.preview-image');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    preview.className += ' preview-image';
                    this.parentElement.appendChild(preview);
                }.bind(this);
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection

