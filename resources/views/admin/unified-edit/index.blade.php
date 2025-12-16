@extends('layouts.app')

@section('title', 'Edit Semua Fitur')

@section('content')
<style>
    .preview-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 2px dashed #28a745;
    }
    .preview-section h5 {
        color: #28a745;
        margin-bottom: 15px;
    }
    .tab-content {
        min-height: 400px;
    }
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        border-bottom-color: #28a745;
        color: #28a745;
    }
    .nav-tabs .nav-link.active {
        color: #28a745;
        background-color: transparent;
        border-bottom-color: #28a745;
        font-weight: 600;
    }
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        text-align: center;
        vertical-align: middle;
        padding: 1rem 0.75rem;
        background-color: #f8f9fa;
    }
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    .table tbody tr {
        transition: all 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .table tbody td {
        vertical-align: middle;
    }
    .table tbody td .badge {
        transition: all 0.3s ease;
    }
    .table tbody td .badge:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .table tbody td .btn {
        transition: all 0.3s ease;
    }
    .table tbody td .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
    .album-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .album-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
</style>

    <div class="container-fluid px-2 px-md-3">
    <div class="row mb-4">
        <div class="col-12">
                <div class="card shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%); color: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); border: none;">
                    <div class="card-body py-4">
                        <h2 class="mb-2" style="font-size: clamp(1.25rem, 3vw, 1.75rem); font-weight: 700;">
                        <i class="bi bi-pencil-square"></i> Edit Semua Fitur
                    </h2>
                        <p class="mb-0 opacity-90" style="font-size: clamp(0.875rem, 2vw, 1rem);">Kelola semua fitur aplikasi dalam satu tempat: Profil Pondok, Data Santri, Album Pondok, Info Aplikasi, dan Pengaturan Tampilan</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" id="success-alert" style="border-left: 4px solid #28a745;">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                <div class="flex-grow-1">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-arrow-clockwise"></i> Halaman akan di-refresh otomatis untuk menampilkan data terbaru...
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <script>
            // Auto refresh halaman setelah 1.5 detik untuk memastikan data terbaru tampil
            setTimeout(function() {
                // Reload tanpa cache untuk memastikan data terbaru
                window.location.href = window.location.href.split('?')[0] + '?refresh=' + new Date().getTime();
            }, 1500);
        </script>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="bi bi-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.unified-edit.update') }}" method="POST" enctype="multipart/form-data" id="unified-edit-form">
        @csrf
        @method('PUT')

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="editTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab">
                    <i class="bi bi-building"></i> Profil Pondok
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="santri-tab" data-bs-toggle="tab" data-bs-target="#santri" type="button" role="tab">
                    <i class="bi bi-people"></i> Data Santri
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="album-tab" data-bs-toggle="tab" data-bs-target="#album" type="button" role="tab">
                    <i class="bi bi-images"></i> Album Pondok
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Info Aplikasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                    <i class="bi bi-gear"></i> Pengaturan Tampilan
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="editTabsContent">
            <!-- Profil Pondok Tab -->
            <div class="tab-pane fade show active" id="profil" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Informasi Umum</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="profil_pondok_nama_pondok" class="form-label">Nama Pondok <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="profil_pondok_nama_pondok" name="profil_pondok[nama_pondok]" value="{{ old('profil_pondok.nama_pondok', $profilPondok->nama_pondok) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="profil_pondok_subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="profil_pondok_subtitle" name="profil_pondok[subtitle]" value="{{ old('profil_pondok.subtitle', $profilPondok->subtitle) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="profil_pondok_logo" class="form-label">Logo Pondok</label>
                            <input type="file" class="form-control" id="profil_pondok_logo" name="profil_pondok[logo]" accept="image/*">
                            @php
                                $profilFresh = $profilPondok->fresh();
                                $logoExists = $profilFresh->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profilFresh->logo);
                            @endphp
                            @if($logoExists)
                                <div class="mt-3">
                                    <p class="mb-2"><strong>Logo saat ini:</strong></p>
                                    <div class="border rounded p-3" style="background: #f8f9fa; display: inline-block;">
                                        <img src="{{ asset('storage/' . $profilFresh->logo) }}?v={{ time() }}" alt="Logo Pondok" class="img-thumbnail" style="max-height: 120px; max-width: 200px; background: transparent;">
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $profilFresh->logo) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Lihat Logo Lengkap
                                        </a>
                                    </div>
                                </div>
                            @else
                                @if($profilFresh->logo)
                                    <div class="alert alert-warning mt-3">
                                        <i class="bi bi-exclamation-triangle"></i> Logo tidak ditemukan di storage.
                                        <br><small>Pastikan storage link sudah dibuat: <code>php artisan storage:link</code></small>
                                    </div>
                                @else
                                    <div class="alert alert-info mt-3">
                                        <i class="bi bi-info-circle"></i> Belum ada logo yang diupload. Silakan upload logo pondok.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-book"></i> Tentang Pondok</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="profil_pondok_tentang" class="form-label">Tentang Pondok</label>
                            <textarea class="form-control" id="profil_pondok_tentang" name="profil_pondok[tentang]" rows="5">{{ old('profil_pondok.tentang', $profilPondok->tentang) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Visi & Misi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="profil_pondok_visi" class="form-label">Visi</label>
                            <textarea class="form-control" id="profil_pondok_visi" name="profil_pondok[visi]" rows="3">{{ old('profil_pondok.visi', $profilPondok->visi) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="profil_pondok_misi" class="form-label">Misi</label>
                            <textarea class="form-control" id="profil_pondok_misi" name="profil_pondok[misi]" rows="5">{{ old('profil_pondok.misi', $profilPondok->misi) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-star"></i> Program & Fasilitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="profil_pondok_program_unggulan" class="form-label">Program Unggulan</label>
                            <textarea class="form-control" id="profil_pondok_program_unggulan" name="profil_pondok[program_unggulan]" rows="5">{{ old('profil_pondok.program_unggulan', $profilPondok->program_unggulan) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="profil_pondok_fasilitas" class="form-label">Fasilitas</label>
                            <textarea class="form-control" id="profil_pondok_fasilitas" name="profil_pondok[fasilitas]" rows="5">{{ old('profil_pondok.fasilitas', $profilPondok->fasilitas) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Preview Profil Pondok -->
                <div class="preview-section" id="preview-profil">
                    <h5><i class="bi bi-eye"></i> Preview Profil Pondok (Hasil Edit Langsung Tampil)</h5>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-success">{{ $profilPondok->fresh()->nama_pondok ?? 'PP HS AL-FAKKAR' }}</h3>
                            <p class="text-muted">{{ $profilPondok->fresh()->subtitle ?? 'Pondok Pesantren HS Al-Fakkar' }}</p>
                            @php
                                $profilFresh = $profilPondok->fresh();
                            @endphp
                            @php
                                $logoExistsPreview = $profilFresh->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profilFresh->logo);
                            @endphp
                            @if($logoExistsPreview)
                                <div class="mt-2 mb-2">
                                    <div class="border rounded p-3" style="background: #f8f9fa; display: inline-block;">
                                        <img src="{{ asset('storage/' . $profilFresh->logo) }}?v={{ time() }}" alt="Logo" class="img-thumbnail" style="max-height: 120px; max-width: 200px; background: transparent;">
                                    </div>
                                    <p class="text-muted small mb-0 mt-2">
                                        <i class="bi bi-check-circle text-success"></i> Logo berhasil diupload
                                    </p>
                                </div>
                            @else
                                @if($profilFresh->logo)
                                    <div class="alert alert-warning mt-2">
                                        <i class="bi bi-exclamation-triangle"></i> Logo tidak ditemukan di storage.
                                        <br><small>Pastikan storage link sudah dibuat: <code>php artisan storage:link</code></small>
                                    </div>
                                @else
                                    <div class="alert alert-info mt-2">
                                        <i class="bi bi-info-circle"></i> Belum ada logo yang diupload.
                                    </div>
                                @endif
                            @endif
                            @if($profilFresh->tentang)
                                <div class="mt-3">
                                    <strong>Tentang:</strong>
                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($profilFresh->tentang, 200) }}</p>
                                </div>
                            @endif
                            @if($profilFresh->visi)
                                <div class="mt-2">
                                    <strong>Visi:</strong>
                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($profilFresh->visi, 150) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Santri Tab -->
            <div class="tab-pane fade" id="santri" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Kelola Data Santri</h5>
                        <a href="{{ route('santri.create') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Santri Baru
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-success" style="background: linear-gradient(135deg, #1e7e34 0%, #28a745 50%, #20c997 100%); color: white;">
                                    <tr>
                                        <th style="text-align: center; width: 60px;">No</th>
                                        <th style="text-align: left;">Nama</th>
                                        <th style="text-align: left;">Username</th>
                                        <th style="text-align: left;">NIS</th>
                                        <th style="text-align: center; width: 120px;">Status</th>
                                        <th style="text-align: center; width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($santri as $index => $item)
                                        <tr>
                                            <td style="text-align: center; font-weight: 600; color: #6c757d;">{{ $santri->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->santriDetail && $item->santriDetail->foto)
                                                        <img src="{{ asset('storage/' . $item->santriDetail->foto) }}" alt="Foto" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    @else
                                                        <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                            <i class="bi bi-person text-white"></i>
                                                        </div>
                                                    @endif
                                                    <span style="font-weight: 600; color: #212529;">{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td style="color: #6c757d; font-weight: 500;">{{ $item->username }}</td>
                                            <td style="color: #1e7e34; font-weight: 700; font-family: 'Courier New', monospace;">{{ $item->santriDetail->nis ?? '-' }}</td>
                                            <td style="text-align: center;">
                                                @if($item->santriDetail)
                                                    @if($item->santriDetail->status_santri === 'aktif')
                                                        <span class="badge bg-success" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: 20px; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3); display: inline-flex; align-items: center; gap: 0.4rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;">
                                                            <i class="bi bi-check-circle"></i> {{ ucfirst($item->santriDetail->status_santri) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: 20px; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3); display: inline-flex; align-items: center; gap: 0.4rem; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;">
                                                            <i class="bi bi-exclamation-circle"></i> {{ ucfirst($item->santriDetail->status_santri) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: 20px;">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                                    <a href="{{ route('santri.show', $item->id) }}" class="btn btn-sm btn-info" title="Lihat Detail" style="padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 6px rgba(0,0,0,0.15); transition: all 0.3s ease; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; min-width: 45px;">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('santri.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit Data" style="padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 6px rgba(0,0,0,0.15); transition: all 0.3s ease; background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); border: none; color: #212529; min-width: 45px;">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-santri-btn" data-url="{{ route('santri.destroy', $item->id) }}" data-csrf="{{ csrf_token() }}" title="Hapus Data" style="padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 6px rgba(0,0,0,0.15); transition: all 0.3s ease; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; min-width: 45px;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mb-0 mt-2">Belum ada data santri</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($santri->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $santri->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Album Pondok Tab -->
            <div class="tab-pane fade" id="album" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-images"></i> Kelola Album Pondok</h5>
                        <a href="{{ route('admin.album.create') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Album Baru
                        </a>
                    </div>
                    <div class="card-body">
                        @if($albums->count() > 0)
                            <div class="row g-3">
                                @foreach($albums as $album)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card h-100 shadow-sm">
                                            <div class="position-relative" style="height: 200px; overflow: hidden;">
                                                @if($album->foto_url)
                                                    <img src="{{ asset('storage/' . $album->foto_url) }}" alt="{{ $album->judul }}" class="w-100 h-100" style="object-fit: cover;">
                                                @else
                                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                                    {{ ucfirst($album->kategori) }}
                                                </span>
                                                @if(!$album->is_active)
                                                    <span class="badge bg-secondary position-absolute top-0 start-0 m-2">Tidak Aktif</span>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title mb-2">{{ Str::limit($album->judul, 30) }}</h6>
                                                <p class="card-text small text-muted mb-2">
                                                    {{ Str::limit($album->deskripsi, 50) }}
                                                </p>
                                                <p class="small mb-2">
                                                    <i class="bi bi-images"></i> {{ $album->fotos->count() }} foto
                                                </p>
                                            </div>
                                            <div class="card-footer bg-transparent border-top-0">
                                                <div class="btn-group w-100" role="group">
                                                    <a href="{{ route('admin.album.show', $album->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.album.edit', $album->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-album-btn" data-url="{{ route('admin.album.destroy', $album->id) }}" data-csrf="{{ csrf_token() }}" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($albums->hasPages())
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $albums->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mb-0 mt-3">Belum ada album</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Aplikasi Tab -->
            <div class="tab-pane fade" id="info" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-info-square"></i> Informasi Umum</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="info_aplikasi_judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="info_aplikasi_judul" name="info_aplikasi[judul]" value="{{ old('info_aplikasi.judul', $infoAplikasi->judul) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="info_aplikasi_tentang" class="form-label">Tentang Aplikasi</label>
                            <textarea class="form-control" id="info_aplikasi_tentang" name="info_aplikasi[tentang]" rows="5">{{ old('info_aplikasi.tentang', $infoAplikasi->tentang) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="info_aplikasi_fitur" class="form-label">Fitur</label>
                            <textarea class="form-control" id="info_aplikasi_fitur" name="info_aplikasi[fitur]" rows="5">{{ old('info_aplikasi.fitur', $infoAplikasi->fitur) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Keamanan & Bantuan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="info_aplikasi_keamanan" class="form-label">Keamanan</label>
                            <textarea class="form-control" id="info_aplikasi_keamanan" name="info_aplikasi[keamanan]" rows="5">{{ old('info_aplikasi.keamanan', $infoAplikasi->keamanan) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="info_aplikasi_bantuan" class="form-label">Bantuan</label>
                            <textarea class="form-control" id="info_aplikasi_bantuan" name="info_aplikasi[bantuan]" rows="5">{{ old('info_aplikasi.bantuan', $infoAplikasi->bantuan) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-code-square"></i> Informasi Teknis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="info_aplikasi_versi" class="form-label">Versi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="info_aplikasi_versi" name="info_aplikasi[versi]" value="{{ old('info_aplikasi.versi', $infoAplikasi->versi) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="info_aplikasi_framework" class="form-label">Framework <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="info_aplikasi_framework" name="info_aplikasi[framework]" value="{{ old('info_aplikasi.framework', $infoAplikasi->framework) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="info_aplikasi_database" class="form-label">Database <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="info_aplikasi_database" name="info_aplikasi[database]" value="{{ old('info_aplikasi.database', $infoAplikasi->database) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Info Aplikasi -->
                <div class="preview-section" id="preview-info">
                    <h5><i class="bi bi-eye"></i> Preview Info Aplikasi (Hasil Edit Langsung Tampil)</h5>
                    <div class="card">
                        <div class="card-body">
                            @php
                                $infoFresh = $infoAplikasi->fresh();
                            @endphp
                            <h4 class="text-success">{{ $infoFresh->judul ?? 'Aplikasi Manajemen Data Santri' }}</h4>
                            @if($infoFresh->tentang)
                                <div class="mt-2">
                                    <strong>Tentang:</strong>
                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($infoFresh->tentang, 200) }}</p>
                                </div>
                            @endif
                            @if($infoFresh->fitur)
                                <div class="mt-2">
                                    <strong>Fitur:</strong>
                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($infoFresh->fitur, 150) }}</p>
                                </div>
                            @endif
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Versi:</strong> {{ $infoFresh->versi ?? '1.0.0' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Framework:</strong> {{ $infoFresh->framework ?? 'Laravel' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Database:</strong> {{ $infoFresh->database ?? 'MySQL' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Tampilan Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                @foreach($appSettings as $group => $groupSettings)
                    <div class="card mb-3">
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
                                        </label>

                                        @if($setting->type === 'text')
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="setting_{{ $setting->key }}" 
                                                name="app_settings[{{ $setting->key }}]" 
                                                value="{{ old("app_settings.{$setting->key}", $setting->value) }}"
                                            >
                                        @elseif($setting->type === 'color')
                                            <div class="input-group">
                                                <input 
                                                    type="color" 
                                                    class="form-control form-control-color" 
                                                    id="setting_{{ $setting->key }}" 
                                                    name="app_settings[{{ $setting->key }}]" 
                                                    value="{{ old("app_settings.{$setting->key}", $setting->value ?? '#28a745') }}"
                                                    style="width: 80px; height: 38px;"
                                                >
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    value="{{ old("app_settings.{$setting->key}", $setting->value ?? '#28a745') }}"
                                                    onchange="document.getElementById('setting_{{ $setting->key }}').value = this.value"
                                                    placeholder="#28a745"
                                                >
                                            </div>
                                        @elseif($setting->type === 'image')
                                            @if($setting->value)
                                                <div class="mb-2" style="background: transparent !important; padding: 5px; border: 1px solid #dee2e6; border-radius: 4px; display: inline-block;">
                                                    <img src="{{ asset('storage/' . $setting->value) }}?v={{ time() }}" alt="Current image" style="max-height: 100px; max-width: 200px; background: transparent !important; background-color: transparent !important; display: block;">
                                                    <p class="text-muted small mb-0 mt-1">Gambar saat ini</p>
                                                </div>
                                            @endif
                                            <input 
                                                type="file" 
                                                class="form-control" 
                                                id="setting_{{ $setting->key }}" 
                                                name="app_settings[{{ $setting->key }}]"
                                                accept="image/*"
                                            >
                                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                        @else
                                            <textarea 
                                                class="form-control" 
                                                id="setting_{{ $setting->key }}" 
                                                name="app_settings[{{ $setting->key }}]" 
                                                rows="3"
                                            >{{ old("app_settings.{$setting->key}", $setting->value) }}</textarea>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Preview Pengaturan -->
                <div class="preview-section" id="preview-settings">
                    <h5><i class="bi bi-eye"></i> Preview Pengaturan Tampilan (Hasil Edit Langsung Tampil)</h5>
                    <div class="card">
                        <div class="card-body">
                            @php
                                $appNamePreview = \App\Models\AppSetting::where('key', 'app_name')->value('value') ?? 'Managemen Data Santri';
                                $appTitlePreview = \App\Models\AppSetting::where('key', 'app_title')->value('value') ?? 'PP HS AL-FAKKAR';
                                $primaryColorPreview = \App\Models\AppSetting::where('key', 'primary_color')->value('value') ?? '#28a745';
                                $appLogoPreview = \App\Models\AppSetting::where('key', 'app_logo')->value('value');
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nama Aplikasi:</strong> {{ $appNamePreview }}</p>
                                    <p><strong>Judul:</strong> {{ $appTitlePreview }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Warna Primer:</strong> 
                                        <span style="display: inline-block; width: 30px; height: 30px; background-color: {{ $primaryColorPreview }}; border: 2px solid #ccc; border-radius: 4px; vertical-align: middle;"></span>
                                        {{ $primaryColorPreview }}
                                    </p>
                                </div>
                            </div>
                            @if($appLogoPreview)
                                <div class="mt-2">
                                    <p><strong>Logo Aplikasi:</strong></p>
                                    <img src="{{ asset('storage/' . $appLogoPreview) }}?v={{ time() }}" alt="Logo" style="max-height: 80px; background: transparent !important; background-color: transparent !important; border: none !important;">
                                </div>
                            @else
                                <p class="text-muted small">Belum ada logo aplikasi</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card mt-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-save"></i> Simpan Semua Perubahan
                    </button>
                </div>
                <p class="text-muted text-center mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> Setelah disimpan, hasil akan langsung tampil di preview section di setiap tab
                </p>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-scroll to preview after successful save
    @if(session('success'))
        setTimeout(function() {
            // Scroll to preview section of active tab
            const activeTab = document.querySelector('.nav-link.active');
            if (activeTab) {
                const targetId = activeTab.getAttribute('data-bs-target');
                if (targetId) {
                    const tabPane = document.querySelector(targetId);
                    if (tabPane) {
                        const previewSection = tabPane.querySelector('.preview-section');
                        if (previewSection) {
                            previewSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            previewSection.style.animation = 'pulse 2s';
                        }
                    }
                }
            }
        }, 500);
    @endif

    // Preview image before upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'img-thumbnail mt-2 preview-image';
                    preview.style.maxHeight = '100px';
                    
                    const existingPreview = this.parentElement.querySelector('.preview-image');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    this.parentElement.appendChild(preview);
                }.bind(this);
                reader.readAsDataURL(file);
            }
        });
    });

    // Handle DELETE santri with AJAX to avoid nested form issues
    document.querySelectorAll('.delete-santri-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Yakin ingin menghapus santri ini?')) {
                return;
            }
            
            const url = this.getAttribute('data-url');
            const csrf = this.getAttribute('data-csrf');
            
            // Create a temporary form outside the main form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrf;
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Handle DELETE album with AJAX to avoid nested form issues
    document.querySelectorAll('.delete-album-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Yakin ingin menghapus album ini?')) {
                return;
            }
            
            const url = this.getAttribute('data-url');
            const csrf = this.getAttribute('data-csrf');
            
            // Create a temporary form outside the main form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrf;
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Form validation before submit
    document.getElementById('unified-edit-form').addEventListener('submit', function(e) {
        // Form akan submit normal dengan method PUT
        // Setelah redirect, preview akan otomatis update dengan data terbaru
    });
</script>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endsection

