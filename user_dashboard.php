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
            max-width: 1200px;
            width: 100%;
            padding: 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: minmax(150px, auto);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .bento-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.2);
        }

        .bento-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            transform: translateX(-100%);
            transition: 0.5s;
        }

        .bento-card:hover::before {
            transform: translateX(100%);
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .module-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #fff 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body style="display: flex; justify-content: center; align-items: flex-start; min-height: 100vh;">
    <div class="dashboard-container">
        <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
            <div>
                <h1 class="shimmer-text" style="font-size: 3rem; font-weight: 800; letter-spacing: -2px;">ยินดีต้อนรับ
                </h1>
                <div style="display: flex; align-items: center; gap: 1rem; color: var(--text-muted);">
                    <span class="stat-badge"><i class="fa-solid fa-circle"
                            style="font-size: 0.5rem; animation: pulse 2s infinite;"></i> ระบบพร้อมใช้งาน</span>
                    <span>TECHSTOCK DASHBOARD</span>
                </div>
            </div>
            <a href="logout.php" class="cyber-btn"
                style="padding: 0.6rem 1.5rem; font-size: 0.8rem; border-color: var(--error-color); color: var(--error-color);">ออกจากระบบ</a>
        </header>

        <div class="bento-grid">
            <!-- Profile Module -->
            <div class="bento-card" style="grid-column: span 2; display: flex; align-items: center; gap: 2rem;">
                <div
                    style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center; font-size: 3rem; border: 4px solid var(--glass-border);">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div>
                    <h2 style="font-size: 2rem;"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <p style="color: var(--text-muted);">ลูกค้าสมาชิก TechStock</p>
                    <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--primary-color);">สถานะ: สมาชิกปกติ</p>
                </div>
            </div>

            <!-- Fast Action: New Sale -->
            <div class="bento-card"
                style="cursor: pointer; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);"
                onclick="window.location.href='new_sale.php'">
                <div class="module-icon"><i class="fa-solid fa-cart-plus"></i></div>
                <h3>โปรโมชั่น & เซตแนะนำ</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.5rem;">เลือกซื้ออุปกรณ์ราคาพิเศษ
                    หรือเซตคอมพิวเตอร์ที่เราจัดไว้ให้</p>
                <div style="margin-top: 1.5rem; color: var(--primary-color); font-weight: 700;">ไปหน้าโปรโมชั่น <i
                        class="fa-solid fa-arrow-right"></i></div>
            </div>

            <!-- PC Builder Module -->
            <div class="bento-card"
                style="cursor: pointer; background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, transparent 100%);"
                onclick="window.location.href='builder.php'">
                <div class="module-icon"
                    style="background: linear-gradient(135deg, #fff 0%, var(--success-color) 100%); -webkit-background-clip: text;">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <h3>จัดสเปคคอมพิวเตอร์</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.5rem;">เลือกชิ้นส่วนคอมพิวเตอร์เอง
                    พร้อมระบบเช็คความเข้ากันได้</p>
                <div style="margin-top: 1.5rem; color: var(--success-color); font-weight: 700;">เริ่มจัดสเปค <i
                        class="fa-solid fa-arrow-right"></i></div>
            </div>

            <!-- RMA Tracking -->
            <div class="bento-card" style="cursor: pointer;" onclick="window.location.href='rma_check.php'">
                <div class="module-icon"
                    style="background: linear-gradient(135deg, #fff 0%, #fb923c 100%); -webkit-background-clip: text;">
                    <i class="fa-solid fa-shield-virus"></i>
                </div>
                <h3>เช็คประกัน & เคลมสินค้า</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.5rem;">ตรวจสอบสถานะการรับประกัน
                    และติดตามงานเคลมของคุณ</p>
            </div>

            <!-- Order History Module -->
            <div class="bento-card" style="grid-column: span 2;">
                <h3 style="margin-bottom: 1.5rem;"><i class="fa-solid fa-clock-rotate-left"></i> ประวัติการสั่งซื้อ</h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

if (empty($orders)) {
    echo '<p style="color: var(--text-muted); text-align: center; padding: 2rem;">คุณยังไม่มีประวัติการสั่งซื้อ</p>';
}
else {
    foreach ($orders as $order) {
        $statusClass = 'status-' . $order['status'];
        echo '
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 1rem; margin-bottom: 0.75rem;">
                                <div>
                                    <div style="font-weight: 700;">Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">' . $order['created_at'] . '</div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 700; color: var(--primary-color);">฿' . number_format($order['total_amount'], 2) . '</div>
                                    <span class="status-badge ' . $statusClass . '" style="font-size: 0.6rem; padding: 0.2rem 0.5rem;">' . strtoupper($order['status']) . '</span>
                                </div>
                            </div>';
    }
}
?>
                </div>
            </div>

            <!-- System Stats -->
            <div class="bento-card">
                <h4
                    style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 1rem; text-transform: uppercase;">
                    สินค้าทั้งหมดในระบบ</h4>
                <div style="font-size: 2.5rem; font-weight: 800;">275<span
                        style="font-size: 1rem; color: var(--text-muted); margin-left: 0.5rem;">ชิ้น</span></div>
                <div
                    style="width: 100%; height: 4px; background: rgba(255,255,255,0.05); margin-top: 1rem; border-radius: 2px;">
                    <div
                        style="width: 85%; height: 100%; background: var(--primary-color); border-radius: 2px; box-shadow: 0 0 10px var(--primary-color);">
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

    <style>
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }
    </style>
</body>

</html>