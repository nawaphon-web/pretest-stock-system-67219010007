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

                <!-- RMA Tracking (Optional but kept for functionality) -->
                <div style="background: rgba(251, 146, 60, 0.1); padding: 1.5rem; border-radius: 1rem; cursor: pointer; border: 1px solid rgba(251, 146, 60, 0.2);"
                    onclick="window.location.href='rma_check.php'">
                    <i class="fa-solid fa-shield-virus" style="font-size: 2rem; color: #fb923c;"></i>
                    <h3 style="margin-top: 0.5rem;">Warranty & RMA</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Check claim status</p>
                </div>

                <!-- Order History Module -->
                <div style="grid-column: span 2; background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                    <h3 style="margin-bottom: 1rem;"><i class="fa-solid fa-clock-rotate-left"></i> Recent Orders</h3>
                    <div style="max-height: 250px; overflow-y: auto;">
                        <?php
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

if (empty($orders)) {
    echo '<p style="color: var(--text-muted); text-align: center; padding: 1rem;">No order history found.</p>';
}
else {
    foreach ($orders as $order) {
        $statusClass = 'status-' . $order['status'];
        echo '
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(255,255,255,0.03); border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '</div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted);">' . $order['created_at'] . '</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-weight: 700; color: var(--primary-color); font-size: 0.9rem;">฿' . number_format($order['total_amount'], 2) . '</div>
                                        <span class="status-badge ' . $statusClass . '" style="font-size: 0.6rem; padding: 0.1rem 0.4rem;">' . strtoupper($order['status']) . '</span>
                                    </div>
                                </div>';
    }
}
?>
                    </div>
                </div>
                </div>
            </div>
            
            <style>
                .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
                .status-paid { background: rgba(16, 185, 129, 0.2); color: #10b981; }
                .status-processing { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
                .status-shipped { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; }
                .status-completed { background: rgba(16, 185, 129, 0.4); color: #10b981; }
            </style>
        </div>
    </div>
</body>

</html>