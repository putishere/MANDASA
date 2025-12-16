<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Santri - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 15px;
            text-align: center;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .header p {
            font-size: 11px;
            opacity: 0.95;
            margin: 0;
        }
        .content {
            padding: 0 15px;
            max-width: 100%;
        }
        .content-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .data-section {
            display: table-cell;
            width: 65%;
            vertical-align: top;
            padding-right: 15px;
        }
        .photo-section {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            page-break-inside: avoid;
            padding-top: 0;
            padding-left: 15px;
        }
        .photo-section img,
        .photo-placeholder {
            margin-top: 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .info-section {
            margin-bottom: 0;
        }
        .info-section h2 {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .info-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e8e8e8;
            font-size: 10px;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 35%;
            color: #495057;
        }
        .info-table td:last-child {
            color: #212529;
        }
        .photo-section img {
            max-width: 140px;
            max-height: 180px;
            width: 100%;
            border: 2px solid #28a745;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            object-fit: cover;
            object-position: center;
        }
        .photo-placeholder {
            width: 140px;
            height: 180px;
            margin: 0 auto;
            background: #f5f5f5;
            border: 2px solid #28a745;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #28a745;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 9px;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #000;
        }
        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 9px;
            color: #666;
            clear: both;
        }
        .footer p {
            margin: 2px 0;
        }
        @media print {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .header {
                margin-bottom: 10px;
                padding: 10px 12px;
            }
            .header h1 {
                font-size: 16px;
            }
            .header p {
                font-size: 10px;
            }
            .content {
                padding: 0 12px;
            }
            .content-wrapper {
                margin-bottom: 6px;
            }
            .info-section h2 {
                padding: 5px 10px;
                font-size: 11px;
                margin-bottom: 6px;
            }
            .info-table td {
                padding: 4px 6px;
                font-size: 9px;
            }
            .photo-section img {
                max-width: 120px;
                max-height: 160px;
            }
            .photo-placeholder {
                width: 120px;
                height: 160px;
                font-size: 32px;
            }
            .footer {
                margin-top: 8px;
                padding-top: 6px;
                font-size: 8px;
            }
            .badge {
                padding: 2px 8px;
                font-size: 8px;
            }
        }
        @page {
            margin: 0.8cm;
            size: A4 portrait;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PP HS Al-Fakkar</h1>
        <p>Informasi Lengkap Santri</p>
    </div>

    @if($detail)
    <div class="content">
        <div class="content-wrapper">
            <!-- Data Santri -->
            <div class="data-section">
                <div class="info-section">
                    <h2>Informasi Lengkap Santri</h2>
                    <table class="info-table">
                        <tr>
                            <td>NIS</td>
                            <td>{{ $detail->nis }}</td>
                        </tr>
                        <tr>
                            <td>Nama Lengkap</td>
                            <td><strong>{{ $user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{ $user->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $detail->alamat_santri }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP</td>
                            <td>{{ $detail->nomor_hp_santri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                @if($detail)
                                    <span class="badge {{ $detail->status_santri === 'aktif' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($detail->status_santri) }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Wali</td>
                            <td><strong>{{ $detail->nama_wali ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Alamat Wali</td>
                            <td>{{ $detail->alamat_wali ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP Wali</td>
                            <td>{{ $detail->nomor_hp_wali ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Foto Santri -->
            <div class="photo-section">
                @if($detail->foto)
                    @php
                        $fotoPath = public_path('storage/' . $detail->foto);
                        $fotoUrl = asset('storage/' . $detail->foto);
                    @endphp
                    @if(file_exists($fotoPath))
                        <img src="{{ $fotoUrl }}" alt="Foto {{ $user->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="photo-placeholder" style="display: none;">
                            <span>📷</span>
                        </div>
                    @else
                        <div class="photo-placeholder">
                            <span>📷</span>
                        </div>
                    @endif
                @else
                    <div class="photo-placeholder">
                        <span>📷</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p><strong>&copy; {{ date('Y') }} PP HS Al-Fakkar</strong></p>
            <p>Dokumen ini dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        </div>
    </div>
    @endif
</body>
</html>
