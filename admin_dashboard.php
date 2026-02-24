<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 800px;
            width: 100%;
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

<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

        <div class="card">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">You have full access to manage the system.</p>

            <div
                style="margin-top: 2rem; display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <a href="admin_users.php" style="text-decoration: none; color: inherit;">
                    <div
                        style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.1); transition: 0.2s;">
                        <i class="fa-solid fa-users" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <h3 style="margin-top: 0.5rem;">Manage Users</h3>
                    </div>
                </a>
                <a href="admin_products.php" style="text-decoration: none; color: inherit;">
                    <div
                        style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.1); transition: 0.2s;">
                        <i class="fa-solid fa-box" style="font-size: 2rem; color: #10b981;"></i>
                        <h3 style="margin-top: 0.5rem;">Manage Products</h3>
                    </div>
                </a>
                <div
                    style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-chart-line" style="font-size: 2rem; color: #f59e0b;"></i>
                    <h3 style="margin-top: 0.5rem;">Sales Reports</h3>
                </div>
            </div>
        </div>
    </div>
</body>

</html>