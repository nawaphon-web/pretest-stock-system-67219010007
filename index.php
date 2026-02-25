<?php
session_start();
require 'db.php';
require 'includes/Product.php';

// Fetch Hot Deals (Promotions)
$promoProducts = Product::findFeaturedPromotions($pdo, 4);

// Fetch New Arrivals
$newProducts = Product::findNewArrivals($pdo, 4);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStock - Professional Hardware Store</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: #f8fafc;">

    <!-- Hero Section -->
    <header class="hero">
        <div class="container animate-fade-in">
            <h1 style="color: #1e1b4b;">สร้างคอมสเปคเทพ ในราคาที่คุณกำหนด</h1>
            <p>พบกับศูนย์รวมอุปกรณ์คอมพิวเตอร์ระดับพรีเมียม พร้อมระบบจัดสเปคอัจฉริยะที่เช็คความเข้ากันได้ 100%</p>

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="builder.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                    <i class="fa-solid fa-microchip"></i> เริ่มจัดสเปคคอมพิวเตอร์
                </a>
                <a href="login.php" class="btn btn-outline"
                    style="padding: 1rem 2.5rem; font-size: 1.1rem; background: white;">
                    <i class="fa-solid fa-user-lock"></i> เข้าสู่ระบบสมาชิก
                </a>
            </div>

            <!-- Fast Actions -->
            <div style="margin-top: 3rem; display: flex; gap: 2rem; justify-content: center;">
                <a href="rma_check.php"
                    style="text-decoration: none; color: #4b5563; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-shield-check" style="color: #10b981;"></i> ตรวจสอบสถานะการเคลม
                </a>
                <span style="color: #d1d5db;">|</span>
                <a href="tracking.php"
                    style="text-decoration: none; color: #4b5563; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-truck-fast" style="color: #3b82f6;"></i> ติดตามสถานะสินค้า
                </a>
            </div>
        </div>
    </header>

    <!-- Promotions Section -->
    <section class="container" style="padding: 6rem 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
            <div>
                <h2 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">ดีลเด็ดวันนี้
                </h2>
                <p style="color: #64748b;">Today's Hot Promotions</p>
            </div>
            <a href="new_sale.php"
                style="color: var(--primary-color); font-weight: 700; text-decoration: none;">ดูทั้งหมด <i
                    class="fa-solid fa-chevron-right"></i></a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($promoProducts as $p): ?>
                <div class="product-card">
                    <div
                        style="position: absolute; top: 1rem; right: 1rem; background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; z-index: 2;">
                        PROMO</div>
                    <div class="product-image-container">
                        <i class="fa-solid <?php echo $p->icon ?? 'fa-box'; ?>"></i>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($p->name); ?></h3>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 1rem;">
                            <span
                                style="font-size: 1.5rem; font-weight: 800; color: #ef4444;">฿<?php echo number_format($p->sale_price); ?></span>
                            <span
                                style="text-decoration: line-through; color: #94a3b8; font-size: 0.9rem;">฿<?php echo number_format($p->price); ?></span>
                        </div>
                        <a href="product_view.php?id=<?php echo $p->id; ?>" class="btn btn-outline"
                            style="width: 100%;">ดูรายละเอียด</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section style="background: white; padding: 6rem 1rem;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
                <div>
                    <h2 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">
                        สินค้ามาใหม่</h2>
                    <p style="color: #64748b;">New Technology Arrivals</p>
                </div>
                <a href="new_sale.php"
                    style="color: var(--primary-color); font-weight: 700; text-decoration: none;">ดูทั้งหมด <i
                        class="fa-solid fa-chevron-right"></i></a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <?php foreach ($newProducts as $p): ?>
                    <div class="product-card" style="box-shadow: none; border-color: #f1f5f9; background: #fdfdfd;">
                        <div
                            style="position: absolute; top: 1rem; right: 1rem; background: #22c55e; color: white; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; z-index: 2;">
                            NEW</div>
                        <div class="product-image-container" style="background: white;">
                            <i class="fa-solid <?php echo $p->icon ?? 'fa-box'; ?>" style="color: #cbd5e1;"></i>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($p->name); ?></h3>
                            <div class="product-price">฿<?php echo number_format($p->price); ?></div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="btn btn-primary"
                                style="width: 100%; height: 3rem;">ซื้อเลย</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #0f172a; color: white; padding: 4rem 1rem; text-align: center;">
        <div class="container">
            <h2 style="font-weight: 800; margin-bottom: 1rem;">TECHSTOCK PC</h2>
            <p style="color: #94a3b8; margin-bottom: 2rem;">Professional Computer Hardware & Services</p>
            <div style="color: #475569; font-size: 0.875rem;">© 2024 TechStock System. All rights reserved.</div>
        </div>
    </footer>

</body>

</html>