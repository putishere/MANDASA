<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SantriDetail;
use App\Models\ProfilPondok;
use App\Models\InfoAplikasi;
use App\Models\AlbumPondok;
use App\Models\AlbumFoto;
use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;

// Cek apakah diakses via CLI atau browser
$isCli = php_sapi_name() === 'cli';

if ($isCli) {
    echo "=== DATA DATABASE APLIKASI ===\n\n";
    
    // Statistik
    echo "STATISTIK:\n";
    echo "- Total Users: " . User::count() . "\n";
    echo "- Admin: " . User::where('role', 'admin')->count() . "\n";
    echo "- Santri: " . User::where('role', 'santri')->count() . "\n";
    echo "- Detail Santri: " . SantriDetail::count() . "\n";
    echo "- Album: " . AlbumPondok::count() . "\n";
    echo "- Foto Album: " . AlbumFoto::count() . "\n";
    echo "- App Settings: " . AppSetting::count() . "\n\n";
    
    // Users
    echo "=== USERS ===\n";
    $users = User::all();
    foreach ($users as $user) {
        echo "ID: {$user->id} | Name: {$user->name} | Role: {$user->role}\n";
        echo "  Email: " . ($user->email ?? '-') . " | Username: " . ($user->username ?? '-') . "\n";
        echo "  Tanggal Lahir: " . ($user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '-') . "\n";
        echo "\n";
    }
    
    // Santri Detail
    echo "=== SANTRI DETAIL ===\n";
    $santriDetails = SantriDetail::with('user')->get();
    foreach ($santriDetails as $detail) {
        echo "ID: {$detail->id} | NIS: {$detail->nis} | Status: {$detail->status_santri}\n";
        echo "  Nama: {$detail->user->name}\n";
        echo "  Alamat: {$detail->alamat_santri}\n";
        echo "  HP: " . ($detail->nomor_hp_santri ?? '-') . "\n";
        echo "  Wali: {$detail->nama_wali}\n";
        echo "\n";
    }
    
    // Profil Pondok
    echo "=== PROFIL PONDOK ===\n";
    $profil = ProfilPondok::getInstance();
    echo "Nama: {$profil->nama_pondok}\n";
    echo "Subtitle: " . ($profil->subtitle ?? '-') . "\n";
    echo "\n";
    
    // Info Aplikasi
    echo "=== INFO APLIKASI ===\n";
    $info = InfoAplikasi::getInstance();
    echo "Judul: {$info->judul}\n";
    echo "Versi: {$info->versi}\n";
    echo "Framework: {$info->framework}\n";
    echo "\n";
    
    // Album
    echo "=== ALBUM PONDOK ===\n";
    $albums = AlbumPondok::with('fotos')->get();
    foreach ($albums as $album) {
        echo "ID: {$album->id} | Judul: {$album->judul} | Kategori: {$album->kategori}\n";
        echo "  Foto: {$album->fotos->count()} foto\n";
        echo "\n";
    }
    
    echo "=== SELESAI ===\n";
} else {
    // HTML Output untuk Browser
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Database - Managemen Data Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px 0;
        }
        .header-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .section-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .table {
            margin-top: 1rem;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid px-3 px-md-4">
        <!-- Header -->
        <div class="header-card">
            <h1 class="mb-3"><i class="bi bi-database"></i> Data Database Aplikasi</h1>
            <p class="mb-0">Managemen Data Santri - PP HS AL-FAKKAR</p>
        </div>

        <!-- Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <h3 class="text-success mb-2"><?= User::count() ?></h3>
                    <p class="mb-0 text-muted">Total Users</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <h3 class="text-primary mb-2"><?= User::where('role', 'admin')->count() ?></h3>
                    <p class="mb-0 text-muted">Admin</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <h3 class="text-info mb-2"><?= User::where('role', 'santri')->count() ?></h3>
                    <p class="mb-0 text-muted">Santri</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card text-center">
                    <h3 class="text-warning mb-2"><?= AlbumPondok::count() ?></h3>
                    <p class="mb-0 text-muted">Album</p>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-people"></i> Data Users</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Tanggal Lahir</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (User::all() as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><strong><?= htmlspecialchars($user->name) ?></strong></td>
                            <td>
                                <span class="badge <?= $user->role === 'admin' ? 'bg-primary' : 'bg-info' ?>">
                                    <?= strtoupper($user->role) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user->email ?? '-') ?></td>
                            <td><?= htmlspecialchars($user->username ?? '-') ?></td>
                            <td><?= $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '-' ?></td>
                            <td><?= $user->created_at->format('Y-m-d H:i') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Santri Detail -->
        <?php if (SantriDetail::count() > 0): ?>
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-person-badge"></i> Data Santri Detail</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Status</th>
                            <th>Alamat</th>
                            <th>HP Santri</th>
                            <th>Wali</th>
                            <th>HP Wali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (SantriDetail::with('user')->get() as $detail): ?>
                        <tr>
                            <td><?= $detail->id ?></td>
                            <td><strong><?= htmlspecialchars($detail->user->name) ?></strong></td>
                            <td><?= htmlspecialchars($detail->nis) ?></td>
                            <td>
                                <span class="badge <?= $detail->status_santri === 'aktif' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= strtoupper($detail->status_santri) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($detail->alamat_santri) ?></td>
                            <td><?= htmlspecialchars($detail->nomor_hp_santri ?? '-') ?></td>
                            <td><?= htmlspecialchars($detail->nama_wali) ?></td>
                            <td><?= htmlspecialchars($detail->nomor_hp_wali ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Profil Pondok -->
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-building"></i> Profil Pondok</h3>
            <?php $profil = ProfilPondok::getInstance(); ?>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Pondok:</strong> <?= htmlspecialchars($profil->nama_pondok) ?></p>
                    <p><strong>Subtitle:</strong> <?= htmlspecialchars($profil->subtitle ?? '-') ?></p>
                    <?php if ($profil->tentang): ?>
                    <p><strong>Tentang:</strong><br><?= nl2br(htmlspecialchars($profil->tentang)) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if ($profil->visi): ?>
                    <p><strong>Visi:</strong><br><?= nl2br(htmlspecialchars($profil->visi)) ?></p>
                    <?php endif; ?>
                    <?php if ($profil->misi): ?>
                    <p><strong>Misi:</strong><br><?= nl2br(htmlspecialchars($profil->misi)) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Info Aplikasi -->
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-info-circle"></i> Info Aplikasi</h3>
            <?php $info = InfoAplikasi::getInstance(); ?>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Judul:</strong> <?= htmlspecialchars($info->judul) ?></p>
                    <p><strong>Versi:</strong> <?= htmlspecialchars($info->versi) ?></p>
                    <p><strong>Framework:</strong> <?= htmlspecialchars($info->framework) ?></p>
                    <p><strong>Database:</strong> <?= htmlspecialchars($info->database) ?></p>
                </div>
                <div class="col-md-8">
                    <?php if ($info->tentang): ?>
                    <p><strong>Tentang:</strong><br><?= nl2br(htmlspecialchars($info->tentang)) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Album -->
        <?php if (AlbumPondok::count() > 0): ?>
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-images"></i> Album Pondok</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th>Jumlah Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (AlbumPondok::with('fotos')->get() as $album): ?>
                        <tr>
                            <td><?= $album->id ?></td>
                            <td><strong><?= htmlspecialchars($album->judul) ?></strong></td>
                            <td><span class="badge bg-secondary"><?= ucfirst($album->kategori) ?></span></td>
                            <td><?= htmlspecialchars($album->deskripsi ? substr($album->deskripsi, 0, 50) . '...' : '-') ?></td>
                            <td><?= $album->urutan ?></td>
                            <td>
                                <span class="badge <?= $album->is_active ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $album->is_active ? 'Aktif' : 'Tidak Aktif' ?>
                                </span>
                            </td>
                            <td><?= $album->fotos->count() ?> foto</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- App Settings -->
        <?php if (AppSetting::count() > 0): ?>
        <div class="section-card">
            <h3 class="mb-4"><i class="bi bi-gear"></i> App Settings</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Type</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (AppSetting::all() as $setting): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($setting->key) ?></strong></td>
                            <td>
                                <?php if ($setting->type === 'image'): ?>
                                    <?php if ($setting->value): ?>
                                        <a href="<?= asset('storage/' . $setting->value) ?>" target="_blank">Lihat Gambar</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?= htmlspecialchars(substr($setting->value ?? '-', 0, 100)) ?>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-info"><?= $setting->type ?></span></td>
                            <td><span class="badge bg-secondary"><?= $setting->group ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="text-center mt-4 mb-4">
            <p class="text-muted">
                <i class="bi bi-database"></i> Database: <strong>database/database.sqlite</strong><br>
                Generated: <?= date('Y-m-d H:i:s') ?>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <?php
}

