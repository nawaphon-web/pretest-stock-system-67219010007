<?php
require 'db.php';

try {
    if ($pdo) {
        echo "<h1>Connected to Database successfully!</h1>";
        echo "<p>Database: stock_system</p>";
        echo "<p>User: user</p>";
    }
} catch (Exception $e) {
    echo "<h1>Connection Failed</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>