<?php
session_start();
require 'db.php';
require 'includes/Product.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = Product::findById($pdo, $id);

if (!$product) {
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $product->name; ?> - TechStock
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .view-container {
            max-width: 1000px;
            width: 100%;
            padding: 4rem 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .product-hero-advanced {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            padding: 5rem;
            border-radius: 3rem;
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        .product-hero-advanced::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }

        .visual-terminal {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10rem;
            color: var(--primary-color);
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            aspect-ratio: 1;
            box-shadow: inset 0 0 50px rgba(0, 0, 0, 0.5);
            filter: drop-shadow(0 0 30px rgba(59, 130, 246, 0.2));
        }

        .spec-row-tech {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
            border-bottom: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .spec-row-tech:hover {
            background: rgba(255, 255, 255, 0.02);
            padding-left: 1.5rem;
        }

        .spec-row-tech strong {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
        }

        .price-command {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
        }
    </style>
</head>

<body style="display: flex; justify-content: center; min-height: 100vh; background: var(--bg-color);">
    <div class="view-container">
        <a href="new_sale.php"
            style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 3rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem;"></i> กลับไปหน้าโปรโมชั่น
        </a>

        <div class="product-hero-advanced">
            <div class="visual-terminal">
                <i class="fa-solid <?php echo $product->icon ?: 'fa-box'; ?>"></i>
            </div>

            <div class="product-info" style="position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <span
                        style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); padding: 0.4rem 1rem; border-radius: 2rem; font-size: 0.65rem; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.2);">
                        <i class="fa-solid fa-circle"
                            style="font-size: 0.4rem; vertical-align: middle; margin-right: 0.5rem;"></i>
                        สินค้าพร้อมจำหน่าย [คงเหลือ <?php echo $product->stock; ?> ชิ้น]
                    </span>
                    <span
                        style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">รหัสสินค้า:
                        <?php echo str_pad($product->id, 6, '0', STR_PAD_LEFT); ?></span>
                </div>

                <h1
                    style="font-size: 3rem; font-weight: 800; line-height: 1; letter-spacing: -2px; margin-bottom: 2rem;">
                    <?php echo $product->name; ?>
                </h1>

                <div class="specs-module">
                    <h4
                        style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 3px; color: var(--primary-color); margin-bottom: 1rem;">
                        // รายละเอียดคุณสมบัติอุปกรณ์</h4>
                    <div
                        style="border: 1px solid var(--glass-border); border-bottom: none; border-radius: 1rem; overflow: hidden;">
                        <?php foreach ($product->specifications as $key => $val): ?>
                            <div class="spec-row-tech">
                                <strong><?php echo str_replace('_', ' ', $key); ?></strong>
                                <span style="font-weight: 600; color: white;"><?php echo $val; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="price-command">
                    <?php if ($product->is_promotion): ?>
                        <div
                            style="color: var(--accent-color); font-size: 0.7rem; text-transform: uppercase; font-weight: 800; margin-bottom: 0.5rem;">
                            สินค้าราคาพิเศษเฉพาะวันนี้</div>
                        <div style="display: flex; align-items: baseline; gap: 1.5rem;">
                            <span
                                style="font-size: 3.5rem; font-weight: 800; color: white;">฿<?php echo number_format($product->sale_price); ?></span>
                            <span
                                style="font-size: 1.25rem; color: var(--text-muted); text-decoration: line-through;">฿<?php echo number_format($product->price); ?></span>
                        </div>
                    <?php else: ?>
                        <div
                            style="color: var(--primary-color); font-size: 0.7rem; text-transform: uppercase; font-weight: 800; margin-bottom: 0.5rem;">
                            ราคาปกติ</div>
                        <span
                            style="font-size: 3.5rem; font-weight: 800; color: white;">฿<?php echo number_format($product->price); ?></span>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 3rem;">
                    <button onclick="location.href='builder.php'" class="cyber-btn" style="padding: 1.25rem;">
                        เลือกชิ้นนี้ใส่สเปคคอม
                    </button>
                    <button class="cyber-btn" style="border-color: var(--glass-border); color: var(--text-muted);">
                        เปรียบเทียบอุปกรณ์
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>