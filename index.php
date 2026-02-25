<?php
session_start();
require 'db.php';
require 'includes/Product.php';

$promotions = Product::getPromotions($pdo, 4);
$newArrivals = Product::getNewArrivals($pdo, 4);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStock - พรีเมียมคอมพิวเตอร์และอุปกรณ์ไอที</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="landing-page">
    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-bg-accent"></div>
        <div class="hero-content">
            <h1>ยกระดับประสบการณ์<br>การจัดสเปคคอมพิวเตอร์</h1>
            <p>พบกับอุปกรณ์ฮาร์ดแวร์ระดับไฮเอนด์ และระบบจัดสเปคอัจฉริยะที่แม่นยำที่สุด พร้อมโปรโมชั่นสุดพิเศษประจำวัน
            </p>

            <div class="landing-nav">
                <a href="builder.php" class="nav-card">
                    <i class="fa-solid fa-microchip"></i>
                    <span>จัดสเปคคอม</span>
                </a>
                <a href="rma_check.php" class="nav-card">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>เช็คประกัน/RMA</span>
                </a>
            </div>

            <div style="margin-top: 3rem;">
                <a href="#promotions" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">
                    เลื่อนเพื่อดูโปรโมชั่น <i class="fa-solid fa-chevron-down"
                        style="margin-left: 0.5rem; animation: bounce 2s infinite;"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Hot Deals Section -->
    <section id="promotions" class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title"><i class="fa-solid fa-fire"></i> Hot Deals</h2>
                <p class="section-subtitle">ลดแรงแซงโค้ง สินค้าแนะนำราคาพิเศษวันนี้</p>
            </div>
            <a href="builder.php" style="color: var(--primary-color); text-decoration: none;">ดูทั้งหมด <i
                    class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="promo-grid">
            <?php foreach ($promotions as $p): ?>
                <div class="promo-card">
                    <div class="promo-tag tag-deal">HOT SALE</div>
                    <div class="promo-icon"><i class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                    <div class="promo-info">
                        <h3>
                            <?php echo $p->name; ?>
                        </h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                            <?php
                            $specs = array_slice($p->specifications, 0, 2);
                            echo implode(' | ', array_values($specs));
                            ?>
                        </p>
                    </div>
                    <div class="promo-price">
                        <div class="price-flex">
                            <span class="sale-price">฿
                                <?php echo number_format($p->sale_price); ?>
                            </span>
                            <span class="old-price">฿
                                <?php echo number_format($p->price); ?>
                            </span>
                        </div>
                        <button class="buy-now-btn" onclick="location.href='builder.php?category=<?php
                        // Get category name
                        $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = ?');
                        $stmt->execute([$p->category_id]);
                        echo $stmt->fetchColumn();
                        ?>'">เลือกใสสเปค</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="section" style="background: rgba(255,255,255,0.02);">
        <div class="section-header">
            <div>
                <h2 class="section-title"><i class="fa-solid fa-star"></i> New Arrivals</h2>
                <p class="section-subtitle">อัปเดตอุปกรณ์รุ่นใหม่ล่าสุดก่อนใคร</p>
            </div>
        </div>

        <div class="promo-grid">
            <?php foreach ($newArrivals as $p): ?>
                <div class="promo-card">
                    <div class="promo-tag tag-new">NEW</div>
                    <div class="promo-icon"><i class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                    <div class="promo-info">
                        <h3>
                            <?php echo $p->name; ?>
                        </h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                            <?php
                            $specs = array_slice($p->specifications, 0, 2);
                            echo implode(' | ', array_values($specs));
                            ?>
                        </p>
                    </div>
                    <div class="promo-price">
                        <div class="price-flex">
                            <span class="normal-price">฿
                                <?php echo number_format($p->price); ?>
                            </span>
                        </div>
                        <button class="buy-now-btn" onclick="location.href='builder.php'">จัดสเปคเลย</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer Area -->
    <footer class="section"
        style="padding: 4rem 2rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="margin-bottom: 2rem;">
            <i class="fa-solid fa-microchip" style="font-size: 2rem; color: var(--primary-color);"></i>
            <h2 style="margin-top: 1rem;">TechStock</h2>
            <p style="color: var(--text-muted);">The Ultimate PC Builder Solution</p>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">
            &copy; 2026 TechStock Co., Ltd. All rights reserved.
        </div>
    </footer>

    <style>
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }
    </style>
</body>

</html>