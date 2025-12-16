@extends('layouts.app')

@section('title', 'Edit Profil Pondok')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="text-success">
            <i class="bi bi-pencil-square"></i> Edit Profil Pondok Pesantren
        </h2>
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

    <form action="{{ route('admin.profil-pondok.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Informasi Umum</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_pondok" class="form-label">Nama Pondok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_pondok" name="nama_pondok" value="{{ old('nama_pondok', $profil->nama_pondok) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $profil->subtitle) }}" placeholder="Contoh: Pondok Pesantren HS Al-Fakkar">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo Pondok</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    @php
                        $profilFresh = $profil->fresh();
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
                    <label for="tentang" class="form-label">Tentang Pondok</label>
                    <textarea class="form-control" id="tentang" name="tentang" rows="5" placeholder="Deskripsi tentang pondok pesantren">{{ old('tentang', $profil->tentang) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-eye"></i> Visi & Misi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="visi" class="form-label">Visi</label>
                    <textarea class="form-control" id="visi" name="visi" rows="3" placeholder="Visi pondok pesantren">{{ old('visi', $profil->visi) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="misi" class="form-label">Misi</label>
                    <textarea class="form-control" id="misi" name="misi" rows="5" placeholder="Misi pondok pesantren (bisa dalam bentuk list)">{{ old('misi', $profil->misi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-star"></i> Program & Fasilitas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="program_unggulan" class="form-label">Program Unggulan</label>
                    <textarea class="form-control" id="program_unggulan" name="program_unggulan" rows="5" placeholder="Program unggulan pondok pesantren">{{ old('program_unggulan', $profil->program_unggulan) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas</label>
                    <textarea class="form-control" id="fasilitas" name="fasilitas" rows="5" placeholder="Fasilitas yang tersedia di pondok pesantren">{{ old('fasilitas', $profil->fasilitas) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.profil-pondok') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

