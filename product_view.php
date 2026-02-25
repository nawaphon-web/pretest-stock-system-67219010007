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
            max-width: 900px;
            width: 100%;
            padding: 4rem 2rem;
            animation: fadeIn 0.5s ease-out;
        }

        .product-hero {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 4rem;
            background: var(--card-bg);
            padding: 4rem;
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
        }

        .product-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            color: var(--primary-color);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            aspect-ratio: 1;
        }

        .spec-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .price-tag-large {
            font-size: 3rem;
            font-weight: 800;
            margin: 2rem 0;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body style="display: flex; justify-content: center;">
    <div class="view-container">
        <a href="new_sale.php"
            style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Hub
        </a>

        <div class="product-hero">
            <div class="product-visual">
                <i class="fa-solid <?php echo $product->icon ?: 'fa-box'; ?>"></i>
            </div>

            <div class="product-info">
                <span class="spec-badge">AVAILABLE IN STOCK (
                    <?php echo $product->stock; ?>)
                </span>
                <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">
                    <?php echo $product->name; ?>
                </h1>

                <div style="margin-top: 2rem;">
                    <h4
                        style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; margin-bottom: 1rem;">
                        Specifications</h4>
                    <div style="display: grid; gap: 0.75rem;">
                        <?php foreach ($product->specifications as $key => $val): ?>
                            <div
                                style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.03); border-radius: 0.5rem;">
                                <strong style="text-transform: capitalize; color: var(--text-muted);">
                                    <?php echo $key; ?>:
                                </strong>
                                <span>
                                    <?php echo $val; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="price-tag-large">
                    <?php if ($product->is_promotion): ?>
                        <span style="font-size: 1rem; color: #f43f5e; text-transform: uppercase;">Promotional Price</span>
                        <span style="color: #f43f5e;">฿
                            <?php echo number_format($product->sale_price); ?>
                        </span>
                        <span style="font-size: 1.25rem; color: var(--text-muted); text-decoration: line-through;">฿
                            <?php echo number_format($product->price); ?>
                        </span>
                    <?php else: ?>
                        <span style="font-size: 1rem; color: var(--primary-color); text-transform: uppercase;">Standard
                            Price</span>
                        <span style="color: var(--primary-color);">฿
                            <?php echo number_format($product->price); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button onclick="location.href='builder.php'" class="btn-checkout" style="flex: 2;">Add to
                        Build</button>
                    <button class="btn-back" style="flex: 1;">Compare</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>