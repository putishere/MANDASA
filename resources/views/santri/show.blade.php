@extends('layouts.app')

@section('title', 'Detail Santri')

@section('content')
<style>
    .page-header {
        background: var(--academic-primary);
        color: white;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-lg);
        box-shadow: var(--shadow-md);
    }
    .page-header h2 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        margin: 0;
        font-weight: 600;
    }
    .detail-card {
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid var(--academic-border);
    }
    .detail-card-header {
        background: var(--academic-primary);
        color: white;
        padding: var(--spacing-md) var(--spacing-lg);
        font-size: clamp(1.1rem, 2.5vw, 1.35rem);
        font-weight: 600;
    }
    .detail-card-body {
        padding: clamp(1.25rem, 3vw, 2rem);
    }
    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: var(--spacing-md);
        padding: var(--spacing-sm) 0;
        border-bottom: 1px solid var(--academic-border);
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
    .info-value strong {
        color: var(--academic-primary);
        font-weight: 600;
    }
    .photo-container {
        text-align: center;
        padding: var(--spacing-lg);
        background: var(--academic-green-light);
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        border: 1px solid var(--academic-border);
    }
    .photo-container img {
        width: 100%;
        max-width: 280px;
        height: auto;
        aspect-ratio: 3/4;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        margin-top: 0;
    }
    .photo-placeholder {
        width: 100%;
        max-width: 280px;
        aspect-ratio: 3/4;
        margin: 0 auto;
        background: var(--academic-green-light);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--academic-primary);
        font-size: 4rem;
        border: 1px solid var(--academic-border);
    }
    .badge-custom {
        font-size: clamp(0.875rem, 2vw, 1rem);
        padding: 0.5rem 1rem;
        border-radius: 20px;
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
    .info-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e9ecef, transparent);
        margin: 1.5rem 0;
    }
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .page-header h2 {
            width: 100%;
        }
        .btn-action-group {
            flex-direction: column;
            width: 100%;
        }
        .btn-action-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }
        .info-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .info-label {
            font-weight: 700;
            font-size: 0.875rem;
        }
        .photo-container {
            margin-bottom: 1.5rem;
        }
        .photo-container img,
        .photo-placeholder {
            max-width: 100%;
        }
    }
    @media (min-width: 768px) {
        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
            align-items: start;
        }
        .photo-container {
            position: sticky;
            top: 1rem;
            height: fit-content;
        }
    }
    @media print {
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        body * {
            visibility: visible;
        }
        .container {
            max-width: 100%;
            padding: 0;
        }
        .page-header, .btn-action-group {
            display: none !important;
        }
        .detail-card {
            position: relative;
            width: 100%;
            box-shadow: none;
            border: none;
            margin: 0;
            padding: 0;
        }
        .detail-card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 25px;
            border-radius: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-only {
            display: block;
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .print-only-subtitle {
            display: block;
            font-size: 15px;
            opacity: 0.95;
        }
        .screen-only {
            display: none;
        }
        .detail-card-body {
            padding: 0 20px;
            max-width: 100%;
        }
        .detail-layout {
            display: table !important;
            width: 100%;
            margin-bottom: 20px;
        }
        .detail-layout > div:first-child {
            display: table-cell !important;
            width: 60%;
            vertical-align: top;
            padding-right: 25px;
        }
        .photo-container {
            display: table-cell !important;
            width: 40%;
            vertical-align: top;
            text-align: center;
            page-break-inside: avoid;
            padding-top: 0;
            padding-left: 25px;
            position: relative !important;
            top: 0 !important;
            background: transparent !important;
        }
        .photo-container img {
            max-width: 220px;
            max-height: 280px;
            width: 100%;
            border: 3px solid #28a745;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 0 auto;
            display: block;
        }
        .photo-placeholder {
            width: 220px;
            height: 280px;
            margin: 0 auto;
            background: #f5f5f5;
            border: 3px solid #28a745;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #28a745;
        }
        .detail-layout > div:first-child {
            display: block !important;
            width: 60% !important;
        }
        .info-row {
            display: table !important;
            width: 100%;
            page-break-inside: avoid;
            border-bottom: 1px solid #e8e8e8;
            margin-bottom: 0;
            table-layout: fixed;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            display: table-cell !important;
            font-weight: 600;
            width: 38%;
            color: #495057;
            padding: 11px 10px;
            font-size: 12px;
            vertical-align: top;
        }
        .info-value {
            display: table-cell !important;
            color: #212529;
            padding: 11px 10px;
            font-size: 12px;
            vertical-align: top;
        }
        .info-value strong {
            color: #212529;
            font-weight: 600;
        }
        .info-value a {
            color: #212529;
            text-decoration: none;
        }
        .info-value a i {
            display: none;
        }
        .badge-custom {
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .info-divider {
            display: none !important;
        }
        footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 11px;
            color: #666;
            clear: both;
        }
        footer p {
            margin: 5px 0;
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
    }
</style>

<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
        <h2>
            <i class="bi bi-person-badge"></i> Detail Santri
        </h2>
        <div class="d-flex gap-2 btn-action-group">
            <a href="{{ route('santri.download-pdf', $santri->id) }}?download=1" class="btn btn-danger btn-action">
                <i class="bi bi-file-pdf"></i> Unduh PDF
            </a>
            <button onclick="window.print()" class="btn btn-info btn-action">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-warning btn-action">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('santri.index') }}" class="btn btn-secondary btn-action">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-card-header">
            <span class="print-only">PP HS Al-Fakkar</span>
            <span class="print-only-subtitle">Informasi Lengkap Santri</span>
            <span class="screen-only"><i class="bi bi-person-circle"></i> Informasi Lengkap Santri</span>
        </div>
        <div class="detail-card-body">
            <div class="detail-layout">
                <!-- Data Santri -->
                <div>
                    <!-- Data Pribadi Santri -->
                    <div class="info-row">
                        <div class="info-label">NIS</div>
                        <div class="info-value">{{ $santri->santriDetail->nis ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><strong>{{ $santri->name }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Username</div>
                        <div class="info-value">{{ $santri->username ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Lahir</div>
                        <div class="info-value">{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tahun Masuk</div>
                        <div class="info-value"><strong>{{ $santri->santriDetail->tahun_masuk ?? '-' }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $santri->santriDetail->alamat_santri ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nomor HP</div>
                        <div class="info-value">
                            @if($santri->santriDetail && $santri->santriDetail->nomor_hp_santri)
                                <a href="tel:{{ $santri->santriDetail->nomor_hp_santri }}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> {{ $santri->santriDetail->nomor_hp_santri }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($santri->santriDetail)
                                <span class="badge badge-custom {{ $santri->santriDetail->status_santri === 'aktif' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($santri->santriDetail->status_santri) }}
                                </span>
                            @else
                                <span class="badge badge-custom bg-secondary">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Wali</div>
                        <div class="info-value"><strong>{{ $santri->santriDetail->nama_wali ?? '-' }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat Wali</div>
                        <div class="info-value">{{ $santri->santriDetail->alamat_wali ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nomor HP Wali</div>
                        <div class="info-value">
                            @if($santri->santriDetail && $santri->santriDetail->nomor_hp_wali)
                                <a href="tel:{{ $santri->santriDetail->nomor_hp_wali }}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> {{ $santri->santriDetail->nomor_hp_wali }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Foto -->
                <div class="photo-container">
                    @if($santri->santriDetail && $santri->santriDetail->foto)
                        <img src="{{ asset('storage/' . $santri->santriDetail->foto) }}" alt="Foto {{ $santri->name }}">
                    @else
                        <div class="photo-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <footer class="print-footer" style="display: none;">
            <p><strong>&copy; {{ date('Y') }} PP HS Al-Fakkar</strong></p>
            <p>Dokumen ini dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        </footer>
    </div>
</div>

<style>
    @media print {
        .print-footer {
            display: block !important;
        }
    }
    @media screen {
        .print-only, .print-only-subtitle {
            display: none;
        }
        .screen-only {
            display: inline;
        }
    }
</style>
@endsection
