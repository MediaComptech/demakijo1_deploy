<?php

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;

echo "--- PEMERIKSAAN BASIS DATA (DATABASE CHECK) ---\n\n";

try {
    $pdo = Capsule::connection()->getPdo();
    echo "✅ Koneksi Database: BERHASIL\n";
    echo "Driver: " . Capsule::connection()->getDriverName() . "\n";
    echo "Database: " . Capsule::connection()->getDatabaseName() . "\n\n";

    // Get list of tables
    $tables = [];
    $driver = Capsule::connection()->getDriverName();
    if ($driver === 'mysql') {
        $results = Capsule::select("SHOW TABLES");
        $key = "Tables_in_" . Capsule::connection()->getDatabaseName();
        foreach ($results as $row) {
            $tables[] = $row->$key ?? current((array)$row);
        }
    } elseif ($driver === 'sqlite') {
        $results = Capsule::select("SELECT name FROM sqlite_master WHERE type='table'");
        foreach ($results as $row) {
            $tables[] = $row->name;
        }
    }

    echo "✅ Daftar Tabel (" . count($tables) . " tabel ditemukan):\n";
    if (empty($tables)) {
        echo "   ⚠️ database kosong (tidak ada tabel)\n";
    } else {
        foreach ($tables as $table) {
            try {
                $count = Capsule::table($table)->count();
                echo "   - $table: $count record\n";
            } catch (\Exception $e) {
                echo "   - $table: ⚠️ Error hitung record (" . $e->getMessage() . ")\n";
            }
        }
    }

    echo "\n✅ Pemeriksaan Selesai.\n";
} catch (\Exception $e) {
    echo "❌ Koneksi Database: GAGAL\n";
    echo "Error: " . $e->getMessage() . "\n";
}
