<?php
session_start();
require 'db.php';
require 'includes/Product.php';
require 'includes/Bundle.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$bundles = Bundle::findAll($pdo);
$promotions = Product::getPromotions($pdo, 4);
$newArrivals = Product::getNewArrivals($pdo, 4);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรโมชั่นและเซตแนะนำ - TechStock Promotional Hub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hub-container {
            max-width: 1400px;
            width: 100%;
            padding: 3rem;
            animation: fadeIn 0.8s ease-out;
        }

        .hub-header {
            margin-bottom: 4rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .bundle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 5rem;
        }

        .bundle-card-tech {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bundle-card-tech:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(59, 130, 246, 0.2);
        }

        .bundle-card-tech.hot {
            border-color: var(--accent-color);
        }

        .bundle-card-tech.hot::before {
            content: 'HIGH PRIORITY';
            position: absolute;
            top: 2rem;
            right: -2.5rem;
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 3rem;
            transform: rotate(45deg);
            font-size: 0.6rem;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .bundle-icon-glow {
            font-size: 4rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
            filter: drop-shadow(0 0 15px var(--primary-color));
        }

        .tech-list {
            margin: 2rem 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .tech-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .tech-item i {
            color: var(--primary-color);
            font-size: 0.6rem;
        }

        .price-tag-advanced {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
    </style>
</head>

<body style="display: flex; justify-content: center; align-items: flex-start; min-height: 100vh;">
    <div class="hub-container">
        <div class="hub-header">
            <div>
                <nav style="margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">
                    <a href="user_dashboard.php" style="color: var(--text-muted); text-decoration: none;">หน้าแรก</a>
                    <span style="color:var(--primary-color)">//</span> โปรโมชั่นทั้งหมด
                </nav>
                <h1 class="shimmer-text" style="font-size: 3.5rem; font-weight: 800; letter-spacing: -3px;">
                    โปรโมชั่นพิเศษ</h1>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">เซตคอมพิวเตอร์แนะนำและอุปกรณ์ราคาพิเศษสำหรับคุณ
                </p>
            </div>
            <a href="builder.php" class="cyber-btn" style="border-radius: 2rem;">
                <i class="fa-solid fa-microchip"></i> จัดสเปคเองเครื่องใหม่
            </a>
        </div>

        <h2
            style="margin-bottom: 2rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 4px; color: var(--primary-color);">
            [01] เซตคอมพิวเตอร์แนะนำ</h2>
        <div class="bundle-grid">
            <?php foreach ($bundles as $b): ?>
                <div class="bundle-card-tech <?php echo $b->is_hot ? 'hot' : ''; ?>">
                    <div class="bundle-icon-glow"><i class="fa-solid <?php echo $b->icon; ?>"></i></div>
                    <h3 style="font-size: 2rem; margin-bottom: 1rem;"><?php echo $b->name; ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;"><?php echo $b->description; ?>
                    </p>

                    <div class="tech-list">
                        <?php
                        $items = $b->getItems($pdo);
                        foreach (array_slice($items, 0, 4) as $item): ?>
                            <div class="tech-item">
                                <i class="fa-solid fa-square"></i> <?php echo $item->name; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="tech-item" style="font-style: italic;">... [+ Full Component Set]</div>
                    </div>

                    <div style="margin-top: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div class="price-tag-advanced">
                            <span
                                style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">ราคาสุดคุ้มทั้งเซต</span>
                            <div style="display: flex; align-items: baseline; gap: 1rem;">
                                <span
                                    style="font-size: 2.5rem; font-weight: 800; color: var(--neon-blue);">฿<?php echo number_format($b->discount_price); ?></span>
                                <span
                                    style="text-decoration: line-through; color: var(--text-muted); font-size: 1rem;">฿<?php echo number_format($b->total_price); ?></span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem;">
                        <a href="bundle_view.php?id=<?php echo $b->id; ?>" class="cyber-btn"
                            style="text-align: center; border-radius: 1rem; padding: 0.75rem; font-size: 0.7rem; border-color: var(--glass-border); color: var(--text-muted);">ดูรายละเอียด</a>
                        <button onclick="selectBundle(<?php echo $b->id; ?>)" class="cyber-btn"
                            style="border-radius: 1rem; padding: 0.75rem; font-size: 0.7rem;">เริ่มจัดสเปคเซตนี้</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
            <div>
                <h2
                    style="margin-bottom: 2rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 4px; color: var(--accent-color);">
                    [02] สินค้าราคาพิเศษ</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <?php foreach ($promotions as $p): ?>
                        <div class="glass-panel" style="padding: 2rem; border-radius: 2rem; position: relative;">
                            <div
                                style="position: absolute; top: 1rem; right: 1rem; background: var(--accent-color); color: white; padding: 0.2rem 0.6rem; border-radius: 2rem; font-size: 0.6rem; font-weight: 800;">
                                PROMO</div>
                            <i class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"
                                style="font-size: 2.5rem; margin-bottom: 1.5rem; color: var(--accent-color);"></i>
                            <h4
                                style="font-size: 1rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo $p->name; ?>
                            </h4>
                            <div style="font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 1.5rem;">
                                ฿<?php echo number_format($p->sale_price); ?></div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="cyber-btn"
                                style="width: 100%; display: block; text-align: center; font-size: 0.6rem; padding: 0.6rem; border-color: var(--accent-color); color: var(--accent-color);">ดูสินค้าชิ้นนี้</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h2
                    style="margin-bottom: 2rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 4px; color: var(--secondary-color);">
                    [03] สินค้าเข้าใหม่</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <?php foreach ($newArrivals as $p): ?>
                        <div class="glass-panel" style="padding: 2rem; border-radius: 2rem; position: relative;">
                            <div
                                style="position: absolute; top: 1rem; right: 1rem; background: var(--secondary-color); color: white; padding: 0.2rem 0.6rem; border-radius: 2rem; font-size: 0.6rem; font-weight: 800;">
                                NEW</div>
                            <i class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"
                                style="font-size: 2.5rem; margin-bottom: 1.5rem; color: var(--secondary-color);"></i>
                            <h4
                                style="font-size: 1rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo $p->name; ?>
                            </h4>
                            <div style="font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 1.5rem;">
                                ฿<?php echo number_format($p->price); ?></div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="cyber-btn"
                                style="width: 100%; display: block; text-align: center; font-size: 0.6rem; padding: 0.6rem; border-color: var(--secondary-color); color: var(--secondary-color);">ดูรายละเอียด</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectBundle(id) {
            window.location.href = `builder.php?load_bundle=${id}`;
        }
    </script>
</body>

</html>