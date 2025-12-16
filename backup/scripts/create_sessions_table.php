<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "=== MEMBUAT TABEL SESSIONS ===\n\n";

try {
    // Cek apakah tabel sessions sudah ada
    if (Schema::hasTable('sessions')) {
        echo "✅ Tabel sessions sudah ada.\n";
        exit(0);
    }

    // Buat tabel sessions
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });

    echo "✅ Tabel sessions berhasil dibuat!\n";
    echo "\nSilakan refresh halaman aplikasi.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

