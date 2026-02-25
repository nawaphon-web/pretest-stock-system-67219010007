<?php
session_start();
require 'db.php';
require 'includes/Product.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 800px;
            width: 100%;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        .btn-logout {
            background: var(--error-color);
            width: auto;
            margin-top: 0;
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: inline-block;
            color: white;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div class="dashboard-container">
        <div class="header">
            <h1>User Dashboard</h1>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

        <div class="card">
            <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Welcome to the TechStock Employee Portal.</p>

            <div
                style="margin-top: 2rem; display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <div style="background: rgba(59, 130, 246, 0.1); padding: 1.5rem; border-radius: 1rem; cursor: pointer; border: 1px solid rgba(59, 130, 246, 0.2);"
                    onclick="window.location.href='new_sale.php'">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <h3 style="margin-top: 0.5rem;">New Sale</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Explore Promos & Sets</p>
                </div>
                <div style="background: rgba(16, 185, 129, 0.1); padding: 1.5rem; border-radius: 1rem; cursor: pointer; border: 1px solid rgba(16, 185, 129, 0.2);"
                    onclick="window.location.href='builder.php'">
                    <i class="fa-solid fa-computer" style="font-size: 2rem; color: #10b981;"></i>
                    <h3 style="margin-top: 0.5rem;">PC Builder</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Custom configurations</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>