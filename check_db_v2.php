<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Debug Info:\n";
echo "DB_HOST Env: " . getenv('DB_HOST') . "\n";

$host = getenv('DB_HOST') ?: 'stock_db'; // Try forcing stock_db if env missing
$db = 'stock_system';
$user = 'user';
$pass = 'password';
$charset = 'utf8mb4';

echo "Attempting connection to $host...\n";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connection Successful!\n";

    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";

} catch (\PDOException $e) {
    echo "Connection Failed: " . $e->getMessage() . "\n";
}
?>