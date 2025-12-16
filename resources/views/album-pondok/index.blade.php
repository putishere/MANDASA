@extends('layouts.app')

@section('title', 'Album Pondok')

@section('content')
<style>
    .album-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }
    .album-card {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .album-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .album-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }
</style>

<div class="container">
    <div class="album-header text-center">
        <h2>Album Pondok Pesantren</h2>
        <p class="mb-0">Galeri Foto Kegiatan Pondok Pesantren MS. Al-Fakkar</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-camera"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Kegiatan Belajar Mengajar</h5>
                    <p class="card-text text-muted small">Foto kegiatan pembelajaran di kelas dan masjid</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-people"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Kegiatan Ekstrakurikuler</h5>
                    <p class="card-text text-muted small">Foto kegiatan olahraga, seni, dan organisasi</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-book"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Program Tahfidz</h5>
                    <p class="card-text text-muted small">Foto kegiatan menghafal Al-Qur'an</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Acara Pondok</h5>
                    <p class="card-text text-muted small">Foto perayaan hari besar dan acara pondok</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Prestasi Santri</h5>
                    <p class="card-text text-muted small">Foto penghargaan dan prestasi santri</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card album-card">
                <div class="album-image">
                    <i class="bi bi-building"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Fasilitas Pondok</h5>
                    <p class="card-text text-muted small">Foto asrama, masjid, dan fasilitas lainnya</p>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle"></i> 
        <strong>Info:</strong> Album foto akan diupdate secara berkala. 
        Untuk menambahkan foto, silakan hubungi admin.
    </div>

    <div class="mt-3">
        <a href="{{ route('santri.dashboard') }}" class="btn btn-success">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

