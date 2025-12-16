@extends('layouts.app')

@section('title', 'Profil Santri')

@section('content')
<style>
    .profile-card {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 15px;
    }
    .profile-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .table th {
        color: #495057;
        font-weight: 600;
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
        width: 30%;
    }
    .table td {
        color: #212529;
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
        width: 70%;
    }
    .table tr {
        transition: background-color 0.2s ease;
    }
    .table tr:hover {
        background-color: #f8f9fa;
    }
    .table tr:not(:last-child) {
        border-bottom: 1px solid #e9ecef;
    }
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 0.9rem;
            padding: 0.5rem 0.25rem;
        }
        .table th {
            width: 35% !important;
        }
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">
            <i class="bi bi-person-circle"></i> Profil Santri
        </h2>
        <div>
            <button onclick="window.print()" class="btn btn-success me-2">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('santri.profil.download') }}" class="btn btn-outline-success">
                <i class="bi bi-download"></i> Unduh PDF
            </a>
        </div>
    </div>

    @if($detail)
    <div class="card profile-card shadow">
        <div class="profile-header p-4">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if($detail->foto)
                        <img src="{{ asset('storage/' . $detail->foto) }}" alt="Foto Santri" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white;">
                    @else
                        <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle text-success" style="font-size: 100px;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h3 class="mb-2">{{ $user->name }}</h3>
                    <p class="mb-1"><i class="bi bi-card-text"></i> NIS: <strong>{{ $detail->nis }}</strong></p>
                    <p class="mb-1"><i class="bi bi-person-badge"></i> Username: <strong>{{ $user->username }}</strong></p>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark">
                            Status: {{ ucfirst($detail->status_santri) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-success border-bottom pb-2 mb-4">
                        <i class="bi bi-info-circle"></i> Informasi Lengkap
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Lengkap</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $detail->alamat_santri }}</td>
                        </tr>
                        <tr>
                            <th>Nomor HP</th>
                            <td>
                                @if($detail->nomor_hp_santri)
                                    <a href="tel:{{ $detail->nomor_hp_santri }}" class="text-decoration-none">{{ $detail->nomor_hp_santri }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $detail->status_santri === 'aktif' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($detail->status_santri) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Nama Wali</th>
                            <td>{{ $detail->nama_wali }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Wali</th>
                            <td>{{ $detail->alamat_wali }}</td>
                        </tr>
                        <tr>
                            <th>Nomor HP Wali</th>
                            <td>
                                @if($detail->nomor_hp_wali)
                                    <a href="tel:{{ $detail->nomor_hp_wali }}" class="text-decoration-none">{{ $detail->nomor_hp_wali }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> Data detail santri belum lengkap. Silakan hubungi admin.
    </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('santri.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<style media="print">
    .btn, .navbar, footer, .d-flex.justify-content-between {
        display: none !important;
    }
    .profile-card {
        box-shadow: none !important;
    }
</style>
@endsection
