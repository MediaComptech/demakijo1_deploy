<?php
// db_test.php - Temporary database query diagnostic
header("Content-Type: text/html");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Database Diagnostic</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f8fafc; color: #334155; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background: #e2e8f0; }
        pre { background: #0f172a; color: #38bdf8; padding: 15px; border-radius: 8px; }
    </style>
</head>
<body>
    <h2>Database Diagnostic SDN Demakijo 1</h2>
    
    <?php
    // Load .env
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) {
        echo "<p style='color:red;'>Error: File .env tidak ditemukan di: $envPath</p>";
        exit;
    }
    
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $env[$name] = $value;
    }
    
    echo "<h3>1. Info Koneksi .env</h3>";
    echo "Host: <b>" . ($env['DB_HOST'] ?? '127.0.0.1') . "</b><br>";
    echo "Database: <b>" . ($env['DB_DATABASE'] ?? '') . "</b><br>";
    echo "Username: <b>" . ($env['DB_USERNAME'] ?? '') . "</b><br>";
    
    try {
        $dsn = "mysql:host=" . ($env['DB_HOST'] ?? '127.0.0.1') . ";port=" . ($env['DB_PORT'] ?? '3306') . ";dbname=" . ($env['DB_DATABASE'] ?? '') . ";charset=utf8";
        $pdo = new PDO($dsn, $env['DB_USERNAME'] ?? '', $env['DB_PASSWORD'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        echo "<p style='color:green;'>✓ Berhasil terhubung ke database!</p>";
        
        // Query beritas
        echo "<h3>2. Data dari tabel 'beritas'</h3>";
        $stmt = $pdo->query("SELECT id, judul, is_published, created_at FROM beritas ORDER BY id DESC");
        $beritas = $stmt->fetchAll();
        
        if (count($beritas) > 0) {
            echo "Total records: <b>" . count($beritas) . "</b>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Judul</th><th>Status Publikasi</th><th>Tanggal Dibuat</th></tr>";
            foreach ($beritas as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                echo "<td>" . ($row['is_published'] ? 'Publikasi' : 'Draft') . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tabel 'beritas' kosong.</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error Koneksi: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
