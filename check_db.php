<?php
require 'db.php';

try {
    echo "Filesystem path: " . __DIR__ . "\n";
    echo "Checking tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);

    if (in_array('users', $tables)) {
        echo "Users table exists.\n";
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Users found: " . count($users) . "\n";
        foreach ($users as $u) {
            echo "ID: " . $u['id'] . ", User: " . $u['username'] . ", Role: " . $u['role'] . "\n";
        }
    } else {
        echo "Users table does NOT exist.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>