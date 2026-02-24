<?php
require 'db.php';

try {
    $sql = file_get_contents('database.sql');
    // Using exec for the whole block. If it fails, we'll suggest splitting.
    $pdo->exec($sql);
    echo "Database structure and initial data loaded.<br>";

    // Reset passwords to known values
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $user_pass = password_hash('user123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$admin_pass, 'admin']);
    $stmt->execute([$user_pass, 'user']);

    echo "Passwords reset to: admin123 / user123";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>