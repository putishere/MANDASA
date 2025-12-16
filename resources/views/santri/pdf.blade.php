<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Santri - {{ $santri->name }}</title>
    <style>
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
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .header p {
            font-size: 15px;
            opacity: 0.95;
            margin: 0;
        }
        .content {
            padding: 0 20px;
            max-width: 100%;
        }
        .content-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .data-section {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 25px;
        }
        .photo-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: center;
            page-break-inside: avoid;
            padding-top: 0;
            padding-left: 25px;
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
            padding: 12px 18px;
            font-size: 17px;
            margin-bottom: 18px;
            border-radius: 6px;
            font-weight: 600;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .info-table td {
            padding: 11px 10px;
            border-bottom: 1px solid #e8e8e8;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 38%;
            color: #495057;
        }
        .info-table td:last-child {
            color: #212529;
        }
        .photo-section img {
            max-width: 220px;
            max-height: 280px;
            width: 100%;
            border: 3px solid #28a745;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
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
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 11px;
            color: #666;
            clear: both;
        }
        .footer p {
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 20px 0;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .header {
                margin-bottom: 20px;
            }
            .content {
                padding: 0 15px;
            }
            .footer {
                margin-top: 25px;
            }
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PP HS Al-Fakkar</h1>
        <p>Informasi Lengkap Santri</p>
    </div>

    <div class="content">
        <div class="content-wrapper">
            <!-- Data Santri -->
            <div class="data-section">
                <!-- Data Pribadi Santri -->
                <div class="info-section">
                    <h2>Informasi Lengkap Santri</h2>
                    <table class="info-table">
                        <tr>
                            <td>NIS</td>
                            <td>{{ $santri->santriDetail->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama Lengkap</td>
                            <td><strong>{{ $santri->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{ $santri->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $santri->santriDetail->alamat_santri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP</td>
                            <td>{{ $santri->santriDetail->nomor_hp_santri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                @if($santri->santriDetail)
                                    <span class="badge {{ $santri->santriDetail->status_santri === 'aktif' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($santri->santriDetail->status_santri) }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Wali</td>
                            <td><strong>{{ $santri->santriDetail->nama_wali ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Alamat Wali</td>
                            <td>{{ $santri->santriDetail->alamat_wali ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP Wali</td>
                            <td>{{ $santri->santriDetail->nomor_hp_wali ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Foto Santri -->
            <div class="photo-section">
                @if($santri->santriDetail && $santri->santriDetail->foto)
                    <img src="{{ asset('storage/' . $santri->santriDetail->foto) }}" alt="Foto {{ $santri->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="photo-placeholder" style="display: none;">
                        <span>📷</span>
                    </div>
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

    <script>
        // Auto print when PDF opens (optional - bisa di-comment jika tidak ingin auto print)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>

