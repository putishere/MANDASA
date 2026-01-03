@extends('layouts.app')

@section('title', 'Daftar Santri')

@section('content')
<style>
    .page-header {
        background: var(--academic-primary);
        color: white;
        padding: clamp(1.25rem, 2.5vw, 2rem);
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-xl);
        box-shadow: var(--shadow-md);
    }
    .page-header h2 {
        font-size: clamp(1.5rem, 3.5vw, 2rem);
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        font-family: 'Playfair Display', serif;
        letter-spacing: 0.5px;
    }
    .page-header h2 i {
        margin-right: 0.5rem;
        font-size: 1.1em;
    }
    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    .search-form {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        flex: 1;
        min-width: 250px;
    }
    .search-input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }
    .search-input-group .form-control {
        background: white;
        border: 2px solid transparent;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: clamp(0.875rem, 2vw, 1rem);
        flex: 1;
        min-width: 200px;
        transition: all 0.3s ease;
    }
    .search-input-group .form-control:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    .search-input-group .form-control::placeholder {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .search-input-group .form-select {
        background: white;
        border: 2px solid transparent;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: clamp(0.875rem, 2vw, 1rem);
        min-width: 130px;
        transition: all 0.3s ease;
    }
    .search-input-group .form-select:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    .btn-search {
        background: rgba(255, 255, 255, 0.25);
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: clamp(0.875rem, 2vw, 1rem);
        white-space: nowrap;
        transition: all 0.3s ease;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    .btn-search:hover {
        background: rgba(255, 255, 255, 0.35);
        border-color: rgba(255, 255, 255, 0.6);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .btn-reset {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: white;
        padding: 0.625rem 0.875rem;
        border-radius: 8px;
        font-size: clamp(0.875rem, 2vw, 1rem);
        white-space: nowrap;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    .btn-reset:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.6);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .btn-add {
        font-size: clamp(0.9rem, 2vw, 1.1rem);
        padding: clamp(0.625rem, 1.5vw, 0.875rem) clamp(1.25rem, 3vw, 2rem);
        border-radius: 10px;
        white-space: nowrap;
        background: white;
        color: var(--academic-primary);
        border: 2px solid transparent;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-add:hover {
        background: #f8f9fa;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        color: #1e7e34;
    }
    .btn-add i {
        margin-right: 0.5rem;
    }
    .search-info {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid rgba(255, 255, 255, 0.25);
        font-size: clamp(0.85rem, 1.8vw, 1rem);
        opacity: 0.95;
        font-weight: 500;
    }
    .table-responsive {
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        background: white;
        border: 1px solid var(--academic-border);
    }
    .card {
        border: 1px solid var(--academic-border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        background: white;
    }
    .card-body {
        padding: 0;
    }
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table thead {
        border-radius: 15px 15px 0 0;
        overflow: hidden;
    }
    .table thead {
        background: var(--academic-primary);
        color: white;
        border-bottom: 2px solid var(--academic-green-dark);
    }
    .table thead th {
        border: none;
        padding: var(--spacing-md) var(--spacing-sm);
        font-size: var(--font-sm);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        text-align: center;
        font-family: 'Inter', sans-serif;
    }
    .table thead th:first-child {
        text-align: center;
        width: 60px;
    }
    .table thead th:nth-child(2) {
        text-align: center;
        width: 100px;
    }
    .table thead th:last-child {
        text-align: center;
        width: 200px;
    }
    .table tbody td {
        padding: var(--spacing-md) var(--spacing-sm);
        font-size: var(--font-md);
        vertical-align: middle;
        border-bottom: 1px solid var(--academic-border);
        text-align: center;
        transition: all 0.2s ease;
    }
    .table tbody td:first-child {
        font-weight: 600;
        color: #6c757d;
    }
    .table tbody td:nth-child(3),
    .table tbody td:nth-child(4) {
        text-align: left;
    }
    .table tbody tr {
        border-bottom: 1px solid var(--academic-border);
    }
    .table tbody tr:hover {
        background-color: var(--academic-green-light);
        transition: all 0.2s ease;
    }
    .table tbody tr:last-child {
        border-bottom: none;
    }
    .table tbody tr:last-child:hover {
        border-radius: 0 0 15px 15px;
    }
    .photo-thumb {
        width: clamp(50px, 8vw, 60px);
        height: clamp(50px, 8vw, 60px);
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        display: block;
        margin: 0 auto;
        cursor: pointer;
    }
    .photo-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.35);
        border-color: var(--academic-primary);
    }
    .photo-placeholder {
        width: clamp(50px, 8vw, 60px);
        height: clamp(50px, 8vw, 60px);
        border-radius: 50%;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--academic-primary);
        border: 3px solid #e9ecef;
        font-size: clamp(1.25rem, 2.5vw, 1.5rem);
        font-weight: 600;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .photo-placeholder:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
        border-color: var(--academic-primary);
    }
    .btn-action {
        font-size: clamp(0.75rem, 1.4vw, 0.85rem);
        padding: clamp(0.4rem, 0.8vw, 0.5rem) clamp(0.65rem, 1.2vw, 0.85rem);
        margin: 0.1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.25s ease;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        white-space: nowrap;
        min-width: fit-content;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .btn-action:active {
        transform: translateY(0);
    }
    .btn-action.btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    .btn-action.btn-info:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        color: white;
    }
    .btn-action.btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }
    .btn-action.btn-warning:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        color: #212529;
    }
    .btn-action.btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    .btn-action.btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        color: white;
    }
    .btn-action i {
        margin-right: 0.25rem;
    }
    .badge {
        padding: 0.4rem 0.9rem;
        font-size: clamp(0.75rem, 1.4vw, 0.8rem);
        font-weight: 600;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .badge.bg-success {
        background: var(--academic-success) !important;
    }
    .badge.bg-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }
    .badge i {
        font-size: 0.85em;
    }
    .nis-text {
        font-weight: 700;
        color: var(--academic-primary);
        font-family: 'Courier New', monospace;
        font-size: clamp(0.85rem, 1.8vw, 0.95rem);
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .name-text {
        color: #212529;
        font-size: clamp(0.9rem, 1.9vw, 1rem);
        font-weight: 600;
        display: inline-block;
        line-height: 1.4;
    }
    .username-text {
        color: #6c757d;
        font-size: clamp(0.85rem, 1.7vw, 0.9rem);
        font-weight: 500;
    }
    .date-text {
        color: #6c757d;
        font-size: clamp(0.85rem, 1.7vw, 0.9rem);
        font-weight: 500;
    }
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        justify-content: center;
        align-items: center;
    }
    @media (min-width: 576px) {
        .action-buttons {
            flex-direction: row;
        }
    }
    .pagination {
        margin: 0;
        gap: 0.5rem;
    }
    .pagination .page-link {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        color: var(--academic-primary);
        padding: 0.5rem 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .pagination .page-link:hover {
        background: var(--academic-primary);
        color: white;
        border-color: var(--academic-primary);
    }
    .pagination .page-item.active .page-link {
        background: var(--academic-primary);
        border-color: var(--academic-primary);
        color: white;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem;
        }
        .page-header h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        .header-actions {
            width: 100%;
            flex-direction: column;
            gap: 0.75rem;
        }
        .search-form {
            width: 100%;
            flex-direction: column;
        }
        .search-input-group {
            width: 100%;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.2);
        }
        .search-input-group .form-control,
        .search-input-group .form-select {
            width: 100%;
            min-width: auto;
        }
        .btn-add,
        .btn-search,
        .btn-reset {
            width: 100%;
        }
        .table {
            font-size: 0.8rem;
        }
        .table thead th {
            padding: 0.875rem 0.5rem;
            font-size: 0.75rem;
        }
        .table tbody td {
            padding: 0.875rem 0.5rem;
            font-size: 0.8rem;
        }
        .table thead th:first-child,
        .table tbody td:first-child {
            width: 40px;
            padding: 0.5rem;
        }
        .table thead th:nth-child(2),
        .table tbody td:nth-child(2) {
            width: 70px;
            padding: 0.5rem;
        }
        .photo-thumb,
        .photo-placeholder {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
        .action-buttons {
            flex-direction: column;
            width: 100%;
            gap: 0.25rem;
        }
        .btn-action {
            width: 100%;
            margin: 0.15rem 0;
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.7rem;
        }
        .nis-text,
        .name-text {
            font-size: 0.8rem;
        }
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        .pagination .page-link {
            padding: 0.4rem 0.7rem;
            font-size: 0.85rem;
        }
    }
    @media (max-width: 992px) and (min-width: 769px) {
        .search-input-group .form-control {
            min-width: 180px;
        }
        .search-input-group .form-select {
            min-width: 120px;
        }
    }
</style>

<div class="container-fluid px-3 px-md-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <h2>
                <i class="bi bi-people"></i> Daftar Santri
            </h2>
            <div class="header-actions">
                <form method="GET" action="{{ route('santri.index') }}" class="search-form">
                    <div class="search-input-group">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama, NIS, username...">
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="boyong" {{ request('status') == 'boyong' ? 'selected' : '' }}>Boyong</option>
                        </select>
                        <button type="submit" class="btn btn-search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('santri.index') }}" class="btn btn-reset">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>
                <a href="{{ route('santri.create') }}" class="btn btn-add">
                    <i class="bi bi-person-plus"></i> Tambah Santri Baru
                </a>
            </div>
        </div>
        
        @if(request('search') || request('status'))
            <div class="search-info">
                <i class="bi bi-info-circle"></i> 
                Menampilkan hasil pencarian
                @if(request('search'))
                    untuk "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('status'))
                    dengan status "<strong>{{ ucfirst(request('status')) }}</strong>"
                @endif
                ({{ $santri->total() }} hasil ditemukan)
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid var(--academic-success); font-weight: 600; padding: 1rem 1.25rem; margin-bottom: 1.5rem; background: var(--academic-green-light); border: 1px solid var(--academic-border);">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2em;"></i> 
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 0.9em;"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #dc3545; font-weight: 600; padding: 1rem 1.25rem; margin-bottom: 1.5rem; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border: none; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.25);">
            <i class="bi bi-exclamation-circle-fill me-2" style="font-size: 1.2em;"></i> 
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 0.9em;"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th class="d-none d-md-table-cell">Username</th>
                            <th class="d-none d-lg-table-cell">Tanggal Lahir</th>
                            <th class="d-none d-lg-table-cell">Tahun Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santri as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $santri->firstItem() + $index }}</td>
                            <td style="text-align: center;">
                                @if($item->santriDetail && $item->santriDetail->foto)
                                    <img src="{{ asset('storage/' . $item->santriDetail->foto) }}" alt="{{ $item->name }}" class="photo-thumb">
                                @else
                                    <div class="photo-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: left;">
                                <span class="nis-text">{{ $item->santriDetail->nis ?? '-' }}</span>
                            </td>
                            <td style="text-align: left;">
                                <span class="name-text">{{ $item->name }}</span>
                            </td>
                            <td class="d-none d-md-table-cell username-text" style="text-align: left;">{{ $item->username ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell date-text" style="text-align: center;">{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                            <td class="d-none d-lg-table-cell" style="color: #6c757d; font-weight: 500; text-align: center;">{{ $item->santriDetail->tahun_masuk ?? '-' }}</td>
                            <td style="text-align: center;">
                                @if($item->santriDetail)
                                    @if($item->santriDetail->status_santri === 'aktif')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> {{ ucfirst($item->santriDetail->status_santri) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-exclamation-circle"></i> {{ ucfirst($item->santriDetail->status_santri) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons">
                                    <a href="{{ route('santri.show', $item->id) }}" class="btn btn-sm btn-info btn-action" title="Lihat Detail">
                                        <i class="bi bi-eye"></i> <span class="d-none d-sm-inline">Detail</span>
                                    </a>
                                    <a href="{{ route('santri.edit', $item->id) }}" class="btn btn-sm btn-warning btn-action" title="Edit Data">
                                        <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                                    </a>
                                    <form action="{{ route('santri.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri {{ $item->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus Data">
                                            <i class="bi bi-trash"></i> <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                                        <i class="bi bi-inbox" style="font-size: 4rem; color: var(--academic-primary);"></i>
                                    </div>
                                    <h5 class="text-muted mb-2" style="font-weight: 600; font-size: 1.25rem;">Tidak ada data santri ditemukan</h5>
                                    <p class="text-muted mb-4" style="font-size: 1rem; opacity: 0.8;">Mulai dengan menambahkan data santri baru</p>
                                    <a href="{{ route('santri.create') }}" class="btn btn-lg shadow-sm" style="background: var(--academic-primary); border-color: var(--academic-primary); color: white; border-radius: 8px; padding: 0.75rem 2rem; font-weight: 600;">
                                        <i class="bi bi-person-plus"></i> Tambah Santri Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($santri->hasPages())
                <div class="p-4 border-top" style="background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);">
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                        {{ $santri->links() }}
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Menampilkan {{ $santri->firstItem() ?? 0 }} - {{ $santri->lastItem() ?? 0 }} dari {{ $santri->total() }} santri
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

