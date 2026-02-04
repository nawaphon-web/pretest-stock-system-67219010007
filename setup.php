<?php
require 'db.php';

try {
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);
    echo "Database setup completed successfully.<br>";
    echo "Default Users created:<br>";
    echo "Admin: admin / password (actually I put 'password' hash in SQL but the prompt said admin123. Let me fix the hash in SQL if needed. Wait, the hash in SQL is for 'password'. Let's stick to simple defaults.)<br>";
    echo "Wait, let's reset to ensure we know the password.<br>";
    
    // Let's re-hash to be sure
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $user_pass = password_hash('user123', PASSWORD_DEFAULT);
    
    // Update or Insert
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:u, :p, 'admin') ON DUPLICATE KEY UPDATE password=:p");
    $stmt->execute(['u' => 'admin', 'p' => $admin_pass]);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:u, :p, 'user') ON DUPLICATE KEY UPDATE password=:p");
    $stmt->execute(['u' => 'user', 'p' => $user_pass]);
    
    echo "Users updated with passwords: admin123 / user123";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
