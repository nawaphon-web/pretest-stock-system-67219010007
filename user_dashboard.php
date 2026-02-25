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
    <title>Customer Dashboard - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: #f8fafc;">
    <div class="container animate-fade-in">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b;">ยินดีต้อนรับ</h1>
                <p style="color: var(--text-muted);">TechStock - ระบบจัดการสเปคคอมพิวเตอร์และสต็อกสินค้า</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="logout.php" class="btn btn-outline"
                    style="color: #ef4444; border-color: #fecaca;">ออกจากระบบ</a>
            </div>
        </header>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            <!-- Profile Card -->
            <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
                <div
                    style="width: 80px; height: 80px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #64748b; font-weight: 800;">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div>
                    <h2 style="font-size: 1.5rem;"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <p style="color: #64748b;">สถานะ: สมาชิกปกติ</p>
                </div>
            </div>

            <!-- Shop Actions -->
            <div class="card">
                <div style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;"><i
                        class="fa-solid fa-shopping-bag"></i></div>
                <h3 style="margin-bottom: 0.5rem;">สินค้าและโปรโมชั่น</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">
                    ดูรายการสินค้าล่าสุดและเซตคอมประกอบแนะนำ</p>
                <a href="new_sale.php" class="btn btn-primary" style="width: 100%;">เลือกดูสินค้า</a>
            </div>

            <div class="card">
                <div style="color: #10b981; font-size: 2rem; margin-bottom: 1rem;"><i class="fa-solid fa-microchip"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem;">จัดสเปคคอมพิวเตอร์</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">
                    สร้างเครื่องของคุณเองพร้อมระบบตรวจสอบความเข้ากันได้</p>
                <a href="builder.php" class="btn btn-primary" style="width: 100%; background: #10b981;">เริ่มจัดสเปค</a>
            </div>

            <div class="card">
                <div style="color: #f59e0b; font-size: 2rem; margin-bottom: 1rem;"><i
                        class="fa-solid fa-shield-check"></i></div>
                <h3 style="margin-bottom: 0.5rem;">เช็คประกันสินค้า</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">
                    ตรวจสอบระยะเวลาประกันหรือแจ้งเคลมสินค้า (RMA)</p>
                <a href="rma_check.php" class="btn btn-outline" style="width: 100%;">ตรวจสอบประกัน</a>
            </div>
        </div>
    </div>
</body>

</html>