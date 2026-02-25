<?php
session_start();
require 'db.php';
require 'includes/Bundle.php';
require 'includes/Product.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$bundle = Bundle::findById($pdo, $id);

if (!$bundle) {
    echo "Bundle not found.";
    exit;
}
$items = $bundle->getItems($pdo);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $bundle->name; ?> - TechStock Bundles
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bundle-view {
            max-width: 1000px;
            width: 100%;
            padding: 4rem 2rem;
        }

        .bundle-header-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            padding: 4rem;
            border-radius: 2rem;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .bundle-header-card .icon-bg {
            position: absolute;
            right: -2rem;
            bottom: -2rem;
            font-size: 15rem;
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .item-card-lite {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .item-card-lite i {
            font-size: 2rem;
            color: var(--primary-color);
            width: 50px;
            text-align: center;
        }

        .saving-tag {
            background: #f43f5e;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            display: inline-block;
            margin-top: 1rem;
        }
    </style>
</head>

<body style="display: flex; justify-content: center;">
    <div class="bundle-view">
        <a href="new_sale.php"
            style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Hub
        </a>

        <div class="bundle-header-card">
            <i class="fa-solid <?php echo $bundle->icon; ?> icon-bg"></i>
            <h1 style="font-size: 3rem; color: white; margin-bottom: 1rem;">
                <?php echo $bundle->name; ?>
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 1.25rem; max-width: 600px; margin: 0 auto;">
                <?php echo $bundle->description; ?>
            </p>

            <div class="saving-tag">
                ประหยัดคนเดียวได้ถึง ฿
                <?php echo number_format($bundle->total_price - $bundle->discount_price); ?>!
            </div>

            <div style="margin-top: 3rem;">
                <div style="font-size: 1.5rem; opacity: 0.9;">ราคายกเซตเพียง</div>
                <div style="font-size: 4rem; font-weight: 900; color: white;">฿
                    <?php echo number_format($bundle->discount_price); ?>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 2rem;">รายการอุปกรณ์ในเซตนี้</h2>
        <div class="items-grid">
            <?php foreach ($items as $item): ?>
                <div class="item-card-lite">
                    <i class="fa-solid <?php echo $item->icon ?: 'fa-box'; ?>"></i>
                    <div>
                        <h4 style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                            <?php
                            // Fetch category name
                            $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = ?');
                            $stmt->execute([$item->category_id]);
                            echo $stmt->fetchColumn();
                            ?>
                        </h4>
                        <h3 style="font-size: 1rem;">
                            <?php echo $item->name; ?>
                        </h3>
                        <div style="color: var(--primary-color); font-weight: 700;">฿
                            <?php echo number_format($item->price); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div
            style="margin-top: 4rem; text-align: center; padding: 3rem; background: rgba(59, 130, 246, 0.05); border-radius: 2rem; border: 1px dashed rgba(59, 130, 246, 0.3);">
            <h3 style="margin-bottom: 1.5rem;">ต้องการดำเนินการต่อด้วยเซตนี้ใช่หรือไม่?</h3>
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <button onclick="location.href='new_sale.php'" class="btn-back">เลือกเซตอื่น</button>
                <button onclick="selectBundle(<?php echo $bundle->id; ?>)" class="btn-checkout"
                    style="padding: 1rem 3rem;">เลือกเซตนี้ทันที</button>
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