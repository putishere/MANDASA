<?php
/**
 * Script untuk test koneksi MySQL secara langsung
 */

echo "========================================\n";
echo "  TEST KONEKSI MYSQL LARAGON\n";
echo "========================================\n\n";

$host = '127.0.0.1';
$port = 3306;
$username = 'root';
$password = '';
$database = 'managemen_data_santri';

// Test 1: Koneksi tanpa database
echo "1. Test koneksi ke MySQL server...\n";
try {
    $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Koneksi ke MySQL server berhasil!\n\n";
} catch (PDOException $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: List databases
echo "2. List semua database...\n";
try {
    $stmt = $pdo->query('SHOW DATABASES');
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "   ✅ Ditemukan " . count($databases) . " database:\n";
    foreach ($databases as $db) {
        echo "      - {$db}\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Koneksi ke database spesifik
echo "3. Test koneksi ke database '{$database}'...\n";
try {
    $pdo->exec("USE `{$database}`");
    echo "   ✅ Koneksi ke database '{$database}' berhasil!\n\n";
} catch (PDOException $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    echo "   💡 Database mungkin belum dibuat. Buat dengan:\n";
    echo "      CREATE DATABASE {$database};\n\n";
}

// Test 4: List tables
if (in_array($database, $databases)) {
    echo "4. List tabel di database '{$database}'...\n";
    try {
        $pdo->exec("USE `{$database}`");
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   ✅ Ditemukan " . count($tables) . " tabel:\n";
        foreach ($tables as $table) {
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `{$table}`");
            $count = $countStmt->fetchColumn();
            echo "      - {$table} ({$count} rows)\n";
        }
        echo "\n";
    } catch (PDOException $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

// Test 5: Test query
if (in_array($database, $databases)) {
    echo "5. Test query SELECT...\n";
    try {
        $pdo->exec("USE `{$database}`");
        if (in_array('users', $tables ?? [])) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            $count = $stmt->fetchColumn();
            echo "   ✅ Query berhasil! Total users: {$count}\n\n";
        }
    } catch (PDOException $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "========================================\n";
echo "  RINGKASAN\n";
echo "========================================\n\n";

if (isset($pdo)) {
    echo "✅ MySQL server berjalan dengan baik\n";
    echo "✅ Port 3306 dapat diakses\n";
    if (in_array($database, $databases ?? [])) {
        echo "✅ Database '{$database}' ada dan dapat diakses\n";
        echo "✅ Total tabel: " . count($tables ?? []) . "\n";
    } else {
        echo "⚠️  Database '{$database}' belum dibuat\n";
    }
} else {
    echo "❌ Tidak dapat terhubung ke MySQL server\n";
    echo "\n💡 Solusi:\n";
    echo "   1. Pastikan MySQL berjalan di Laragon\n";
    echo "   2. Cek port 3306 tidak digunakan aplikasi lain\n";
    echo "   3. Restart Laragon (Stop All → Start All)\n";
}

echo "\n";

