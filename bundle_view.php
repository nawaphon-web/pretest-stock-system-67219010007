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
            max-width: 1200px;
            width: 100%;
            padding: 4rem 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .bundle-header-tech {
            background: var(--card-bg);
            backdrop-filter: blur(30px);
            padding: 5rem;
            border-radius: 3rem;
            border: 1px solid var(--glass-border);
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }

        .bundle-header-tech::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--neon-blue), var(--primary-color));
            box-shadow: 0 0 20px var(--primary-color);
        }

        .items-terminal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .item-card-mini {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            transition: 0.3s;
        }

        .item-card-mini:hover {
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(10px);
        }

        .item-card-mini i {
            font-size: 2.5rem;
            color: var(--primary-color);
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.3));
        }

        .savings-alert {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            padding: 0.75rem 1.5rem;
            border-radius: 3rem;
            font-size: 0.8rem;
            font-weight: 800;
            border: 1px solid rgba(16, 185, 129, 0.2);
            margin-top: 2rem;
        }
    </style>
</head>

<body style="display: flex; justify-content: center; min-height: 100vh; background: var(--bg-color);">
    <div class="bundle-view">
        <a href="new_sale.php"
            style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 3rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem;"></i> กลับไปหน้าโปรโมชั่น
        </a>

        <div class="bundle-header-tech">
            <i class="fa-solid <?php echo $bundle->icon; ?>"
                style="position: absolute; right: -2rem; bottom: -2rem; font-size: 20rem; opacity: 0.03; transform: rotate(-15deg); color: white;"></i>
            <h1 class="shimmer-text"
                style="font-size: 4rem; font-weight: 800; margin-bottom: 1.5rem; letter-spacing: -3px;">
                <?php echo $bundle->name; ?>
            </h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 700px; margin: 0 auto; line-height: 1.6;">
                <?php echo $bundle->description; ?>
            </p>

            <div class="savings-alert">
                <i class="fa-solid fa-shield-halved"></i> คุณประหยัดไปได้ถึง:
                ฿<?php echo number_format($bundle->total_price - $bundle->discount_price); ?> ทันที!
            </div>

            <div style="margin-top: 4rem;">
                <div
                    style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 4px; color: var(--primary-color); margin-bottom: 1rem;">
                    // ราคาสุดคุ้มทั้งเซต</div>
                <div style="font-size: 5rem; font-weight: 900; color: white; line-height: 1;">
                    ฿<?php echo number_format($bundle->discount_price); ?></div>
            </div>
        </div>

        <h2
            style="margin-bottom: 2rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 4px; color: var(--primary-color);">
            [01] รายการอุปกรณ์ในเซตนี้</h2>
        <div class="items-terminal-grid">
            <?php foreach ($items as $item): ?>
                <div class="item-card-mini">
                    <i class="fa-solid <?php echo $item->icon ?: 'fa-box'; ?>"></i>
                    <div style="flex: 1;">
                        <div
                            style="color: var(--text-muted); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">
                            <?php
                            $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = ?');
                            $stmt->execute([$item->category_id]);
                            echo $stmt->fetchColumn();
                            ?>
                        </div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;"><?php echo $item->name; ?></h3>
                        <div style="color: var(--neon-blue); font-weight: 700;">฿<?php echo number_format($item->price); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div
            style="margin-top: 5rem; text-align: center; padding: 5rem; background: var(--card-bg); backdrop-filter: blur(20px); border-radius: 3rem; border: 1px solid var(--glass-border);">
            <h3 style="font-size: 1.5rem; margin-bottom: 2rem;">ต้องการใช้สเปคคอมพิวเตอร์เซตนี้ใช่หรือไม่?</h3>
            <div style="display: flex; gap: 2rem; justify-content: center;">
                <button onclick="location.href='new_sale.php'" class="cyber-btn"
                    style="border-color: var(--glass-border); color: var(--text-muted); padding: 1rem 3rem;">เลือกเซตอื่น</button>
                <button onclick="selectBundle(<?php echo $bundle->id; ?>)" class="cyber-btn"
                    style="padding: 1rem 5rem;">ตกลง ใช้เซตนี้</button>
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