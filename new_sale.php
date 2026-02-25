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
    <title>New Sale - TechStock Promotional Hub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hub-container {
            max-width: 1200px;
            width: 100%;
            padding: 2rem;
            height: 100vh;
            overflow-y: auto;
        }

        .hub-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 3rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1.5rem;
        }

        .bundle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-bottom: 4rem;
        }

        .bundle-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            position: relative;
            transition: 0.4s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .bundle-card:hover {
            transform: scale(1.02);
            border-color: var(--primary-color);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.2);
        }

        .bundle-card.hot {
            border-color: #f43f5e;
        }

        .bundle-card.hot::after {
            content: 'HOT DEAL';
            position: absolute;
            top: 1rem;
            right: -2rem;
            background: #f43f5e;
            color: white;
            padding: 0.5rem 3rem;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: 800;
        }

        .bundle-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, white 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .item-list-mini {
            margin: 1.5rem 0;
            padding: 0;
            list-style: none;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .item-list-mini li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .btn-hub-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: 1rem;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-primary-hub {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-hub {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-hub-action:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>

<body style="display: flex; justify-content: center; align-items: flex-start;">
    <div class="hub-container">
        <div class="hub-header">
            <div>
                <nav style="margin-bottom: 0.5rem;"><a href="user_dashboard.php"
                        style="color: var(--text-muted); text-decoration: none;">Dashboard</a> / New Sale</nav>
                <h1 style="font-size: 2.5rem;"><i class="fa-solid fa-basket-shopping"></i> Promotional Hub</h1>
                <p class="subtitle">เลือกเซตคอมพิวเตอร์แนะนำ หรืออุปกรณ์ราคาพิเศษสำหรับลูกค้า</p>
            </div>
            <a href="builder.php" class="btn-hub-action btn-outline-hub">
                <i class="fa-solid fa-gear"></i> Manual PC Builder
            </a>
        </div>

        <!-- Curated PC Sets -->
        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-layer-group" style="color: var(--primary-color);"></i>
            Curated PC Sets</h2>
        <div class="bundle-grid">
            <?php foreach ($bundles as $b): ?>
                <div class="bundle-card <?php echo $b->is_hot ? 'hot' : ''; ?>">
                    <div class="bundle-icon"><i class="fa-solid <?php echo $b->icon; ?>"></i></div>
                    <h3 style="font-size: 1.75rem; color: white;">
                        <?php echo $b->name; ?>
                    </h3>
                    <p style="color: var(--text-muted); margin-top: 0.5rem;">
                        <?php echo $b->description; ?>
                    </p>

                    <ul class="item-list-mini">
                        <?php
                        $items = $b->getItems($pdo);
                        foreach (array_slice($items, 0, 4) as $item): ?>
                            <li><i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 0.7rem;"></i>
                                <?php echo $item->name; ?>
                            </li>
                        <?php endforeach; ?>
                        <li style="font-style: italic;">+ และอุปกรณ์อื่นๆ อีกมากมาย</li>
                    </ul>

                    <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                        <div class="price-flex" style="margin-bottom: 1.5rem; align-items: baseline;">
                            <span style="font-size: 2.25rem; font-weight: 800; color: #f43f5e;">฿
                                <?php echo number_format($b->discount_price); ?>
                            </span>
                            <span style="text-decoration: line-through; color: var(--text-muted); margin-left: 1rem;">฿
                                <?php echo number_format($b->total_price); ?>
                            </span>
                        </div>
                        <div style="display: flex; gap: 1rem;">
                            <a href="bundle_view.php?id=<?php echo $b->id; ?>" class="btn-hub-action btn-outline-hub"
                                style="flex: 1;">View Details</a>
                            <button onclick="selectBundle(<?php echo $b->id; ?>)" class="btn-hub-action btn-primary-hub"
                                style="flex: 1.5;">Select this Set</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Special Offers & New Arrivals (Mini Grid) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div>
                <h2 style="margin-bottom: 1.5rem;"><i class="fa-solid fa-fire" style="color: #f43f5e;"></i> Special
                    Offers</h2>
                <div class="promo-mini-grid" style="grid-template-columns: 1fr 1fr;">
                    <?php foreach ($promotions as $p): ?>
                        <div class="promo-card" style="padding: 1.5rem;">
                            <div class="promo-tag tag-deal">DEAL</div>
                            <div class="promo-icon" style="font-size: 2.5rem;"><i
                                    class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                            <h4 style="margin-bottom: 0.5rem;">
                                <?php echo $p->name; ?>
                            </h4>
                            <div class="price-flex">
                                <span class="sale-price" style="font-size: 1.25rem;">฿
                                    <?php echo number_format($p->sale_price); ?>
                                </span>
                            </div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="buy-now-btn"
                                style="text-decoration: none; text-align: center;">View Item</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h2 style="margin-bottom: 1.5rem;"><i class="fa-solid fa-star" style="color: #fbbf24;"></i> New Hardware
                </h2>
                <div class="promo-mini-grid" style="grid-template-columns: 1fr 1fr;">
                    <?php foreach ($newArrivals as $p): ?>
                        <div class="promo-card" style="padding: 1.5rem;">
                            <div class="promo-tag tag-new">NEW</div>
                            <div class="promo-icon" style="font-size: 2.5rem;"><i
                                    class="fa-solid <?php echo $p->icon ?: 'fa-box'; ?>"></i></div>
                            <h4 style="margin-bottom: 0.5rem;">
                                <?php echo $p->name; ?>
                            </h4>
                            <div class="price-flex">
                                <span class="normal-price" style="font-size: 1.25rem;">฿
                                    <?php echo number_format($p->price); ?>
                                </span>
                            </div>
                            <a href="product_view.php?id=<?php echo $p->id; ?>" class="buy-now-btn"
                                style="text-decoration: none; text-align: center;">View Item</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function selectBundle(id) {
            // Logic to load bundle into builder
            // We'll redirect to builder.php with a bundle_id parameter
            window.location.href = `builder.php?load_bundle=${id}`;
        }
    </script>
</body>

</html>