<?php
require 'db.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Explicitly drop and recreate tables to ensure clean slate
    $tables = ['product_reservations', 'rma_requests', 'order_items', 'orders', 'inventory', 'products', 'categories', 'suppliers', 'users'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
        echo "Dropped table $table<br>";
    }

    $sql = file_get_contents('database.sql');
    $queries = explode(';', $sql);

    $count = 0;
    foreach ($queries as $query) {
        $query = trim($query);
        if ($query) {
            try {
                $pdo->exec($query);
                $count++;
            } catch (PDOException $e) {
                // If it's a DROP TABLE that we already did, ignore
                if (strpos($e->getMessage(), "Unknown table") !== false)
                    continue;
                echo "<br><b>Error in query:</b> <pre>" . substr($query, 0, 100) . "...</pre><br>";
                throw $e;
            }
        }
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "Successfully executed $count SQL statements.<br>";
    echo "Database initialized with 5 products per category.<br>";

    // Reset passwords
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $user_pass = password_hash('user123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$admin_pass, 'admin']);
    $stmt->execute([$user_pass, 'user']);
    echo "Passwords reset to: admin123 / user123";

} catch (PDOException $e) {
    echo "Fatal Error: " . $e->getMessage();
}
?>