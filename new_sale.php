<?php
session_start();
require 'db.php';
require 'includes/Product.php';

// Check auth
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch promotions, bundles, and new arrivals
$bundles = Product::findBundles($pdo);
$promotions = Product::findFeaturedPromotions($pdo, 6);
$newArrivals = Product::findNewArrivals($pdo, 6);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions & Recommended Sets - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: #f8fafc;">

    <div class="container animate-fade-in">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-weight: 800; color: #1e293b;">โปรโมชั่นพิเศษ</h1>
                <p style="color: var(--text-muted);">เลือกชมเซตคอมแนะนำและดีลสุดคุ้มสำหรับสมาชิกเท่านั้น</p>
            </div>
            <a href="builder.php" class="btn btn-primary">
                <i class="fa-solid fa-microchip"></i> จัดสเปคเองเครื่องใหม่
            </a>
        </header>

        <!-- Curated Sets Section -->
        <div style="margin-bottom: 4rem;">
            <h2
                style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fa-solid fa-layer-group" style="color: #8b5cf6;"></i> เซตคอมพิวเตอร์แนะนำ
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
                <?php foreach ($bundles as $bundle): ?>
                    <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                        <div
                            style="height: 120px; background: linear-gradient(135deg, #8b5cf6 0%, #d8b4fe 100%); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fa-solid fa-rocket" style="font-size: 4rem;"></i>
                        </div>
                        <div style="padding: 2rem; flex: 1;">
                            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">
                                <?php echo htmlspecialchars($bundle->name); ?></h3>
                            <p
                                style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem; height: 3em; overflow: hidden;">
                                <?php echo htmlspecialchars($bundle->description); ?>
                            </p>
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                                <div>
                                    <span
                                        style="font-size: 0.75rem; color: #94a3b8; font-weight: 700;">ราคาพิเศษเพียง</span>
                                    <div style="font-size: 1.75rem; font-weight: 800; color: #8b5cf6;">
                                        ฿<?php echo number_format($bundle->price); ?></div>
                                </div>
                                <a href="bundle_view.php?id=<?php echo $bundle->id; ?>" class="btn btn-primary"
                                    style="background: #8b5cf6;">ดูของแถมและตัวเครื่อง</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Flash Sale Section -->
        <div>
            <h2
                style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fa-solid fa-bolt" style="color: #ef4444;"></i> สินค้าลดราคาพิเศษ
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php foreach ($promotions as $p): ?>
                    <div class="product-card">
                        <div class="product-image-container" style="height: 150px;">
                            <i class="fa-solid <?php echo $p->icon ?? 'fa-box'; ?>"></i>
                        </div>
                        <div class="product-details">
                            <h4 style="font-size: 1rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($p->name); ?>
                            </h4>
                            <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 1rem;">
                                <span
                                    style="font-size: 1.25rem; font-weight: 800; color: #ef4444;">฿<?php echo number_format($p->sale_price); ?></span>
                                <span
                                    style="text-decoration: line-through; color: #94a3b8; font-size: 0.8rem;">฿<?php echo number_format($p->price); ?></span>
                            </div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="btn btn-outline"
                                style="width: 100%;">ตรวจสอบสต็อก</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>

</html>