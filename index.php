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
                        <h3><?php echo $p->name; ?></h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                            <?php
                            $specs = array_slice($p->specifications, 0, 2);
                            echo implode(' | ', array_values($specs));
                            ?>
                        </p>
                    </div>
                    <div class="promo-price">
                        <div class="price-flex">
                            <span class="sale-price">฿<?php echo number_format($p->sale_price); ?></span>
                            <span class="old-price">฿<?php echo number_format($p->price); ?></span>
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
                        <h3><?php echo $p->name; ?></h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                            <?php
                            $specs = array_slice($p->specifications, 0, 2);
                            echo implode(' | ', array_values($specs));
                            ?>
                        </p>
                    </div>
                    <div class="promo-price">
                        <div class="price-flex">
                            <span class="normal-price">฿<?php echo number_format($p->price); ?></span>
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
        .hero-section-advanced {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle at 70% 30%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 20% 70%, rgba(216, 180, 254, 0.05) 0%, transparent 50%);
        }

        .hero-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: var(--primary-color);
            filter: blur(200px);
            opacity: 0.1;
            top: 10%;
            right: 10%;
            z-index: 0;
            pointer-events: none;
        }

        .landing-grid-advanced {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .nav-card-tech {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 3rem;
            text-align: center;
            text-decoration: none;
            transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-card-tech:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .nav-card-tech i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.3));
        }

        .nav-card-tech span {
            display: block;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
        }

        .section-header-tech {
            margin-bottom: 4rem;
            text-align: center;
        }

        .section-header-tech h2 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .section-header-tech p {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
        }
    </style>
    </head>

    <body class="landing-page" style="background: var(--bg-color);">
        <div class="hero-glow"></div>

        <!-- Hero Section -->
        <header class="hero-section-advanced">
            <div class="hero-content" style="text-align: center; position: relative; z-index: 1;">
                <div
                    style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 4px; color: var(--primary-color); margin-bottom: 2rem;">
                    // NEXT_GEN_HARDWARE_INTERFACE</div>
                <h1 class="shimmer-text"
                    style="font-size: clamp(3rem, 10vw, 6rem); line-height: 1; margin-bottom: 2rem;">TECHSTOCK_OS</h1>
                <p style="font-size: 1.25rem; max-width: 700px; margin: 0 auto 4rem; line-height: 1.6;">
                    Experience high-performance computing through our elite workstation configuration engine and premium
                    hardware inventory.
                </p>

                <div class="landing-grid-advanced" style="max-width: 900px; margin-inline: auto;">
                    <a href="builder.php" class="nav-card-tech">
                        <i class="fa-solid fa-microchip"></i>
                        <span>SYSTEM_BUILDER</span>
                    </a>
                    <a href="login.php" class="nav-card-tech">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>ACCESS_TERMINAL</span>
                    </a>
                    <a href="rma_check.php" class="nav-card-tech">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>ASSET_PROTECTION</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Hot Deals Section -->
        <section id="promotions" class="section">
            <div class="section-header-tech">
                <h2>[01] ACTIVE_MARKET_DEALS</h2>
                <p>CURATED_HARDWARE_SUBSIDIES</p>
            </div>

            <div class="promo-grid">
                <?php foreach ($promotions as $p): ?>
                    <div class="glass-panel" style="padding: 3rem; border-radius: 2.5rem; text-align: center;">
                        <div
                            style="background: var(--accent-color); color: white; display: inline-block; padding: 0.25rem 1rem; border-radius: 2rem; font-size: 0.6rem; font-weight: 900; margin-bottom: 2rem;">
                            FLASH_DEAL</div>
                        <div style="font-size: 4rem; color: var(--accent-color); margin-bottom: 2rem;"><i
                                class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                        <h3 style="font-size: 1.5rem; margin-bottom: 1rem;"><?php echo $p->name; ?></h3>
                        <div
                            style="display: flex; justify-content: center; align-items: baseline; gap: 1rem; margin-bottom: 2rem;">
                            <span
                                style="font-size: 2rem; font-weight: 800; color: white;">฿<?php echo number_format($p->sale_price); ?></span>
                            <span
                                style="text-decoration: line-through; color: var(--text-muted);">฿<?php echo number_format($p->price); ?></span>
                        </div>
                        <button class="cyber-btn"
                            style="width: 100%; border-color: var(--accent-color); color: var(--accent-color);"
                            onclick="location.href='builder.php'">ACQUIRE_ASSET</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- New Arrivals Section -->
        <section class="section">
            <div class="section-header-tech">
                <h2>[02] NEURAL_SYNC_INVENTORY</h2>
                <p>RECENT_HARDWARE_INTEGRATIONS</p>
            </div>

            <div class="promo-grid">
                <?php foreach ($newArrivals as $p): ?>
                    <div class="glass-panel" style="padding: 3rem; border-radius: 2.5rem; text-align: center;">
                        <div
                            style="background: var(--secondary-color); color: white; display: inline-block; padding: 0.25rem 1rem; border-radius: 2rem; font-size: 0.6rem; font-weight: 900; margin-bottom: 2rem;">
                            NEW_ARRIVAL</div>
                        <div style="font-size: 4rem; color: var(--secondary-color); margin-bottom: 2rem;"><i
                                class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                        <h3 style="font-size: 1.5rem; margin-bottom: 1rem;"><?php echo $p->name; ?></h3>
                        <div style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 2rem;">
                            ฿<?php echo number_format($p->price); ?></div>
                        <button class="cyber-btn"
                            style="width: 100%; border-color: var(--secondary-color); color: var(--secondary-color);"
                            onclick="location.href='builder.php'">VIEW_DETAILS</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <footer
            style="padding: 6rem 2rem; text-align: center; background: rgba(0,0,0,0.3); border-top: 1px solid var(--glass-border);">
            <div style="margin-bottom: 3rem;">
                <div class="shimmer-text" style="font-size: 2.5rem; font-weight: 800; letter-spacing: -2px;">TECHSTOCK
                </div>
                <p
                    style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 4px; margin-top: 1rem;">
                    INTELLIGENT_HARDWARE_SYSTEMS</p>
            </div>
            <div style="display: flex; justify-content: center; gap: 3rem; margin-bottom: 4rem;">
                <a href="#"
                    style="color: var(--text-muted); text-decoration: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">Data
                    Protocols</a>
                <a href="#"
                    style="color: var(--text-muted); text-decoration: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">Security
                    Grid</a>
                <a href="#"
                    style="color: var(--text-muted); text-decoration: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">Access
                    Logs</a>
            </div>
            <div style="font-size: 0.6rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                &copy; 2026 TechStock Global Grid. All neural links reserved.
            </div>
        </footer>
    </body>

</html>