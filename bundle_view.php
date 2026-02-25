<?php
session_start();
require 'db.php';
require 'includes/Product.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: new_sale.php");
    exit;
}

$bundle = Product::findById($pdo, $id);
if (!$bundle) {
    header("Location: new_sale.php");
    exit;
}

// Fetch component details if list exists
$components = [];
if ($bundle->specifications && isset($bundle->specifications['components'])) {
    foreach ($bundle->specifications['components'] as $cat => $prodId) {
        $prod = Product::findById($pdo, $prodId);
        if ($prod)
            $components[$cat] = $prod;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $bundle->name; ?> - TechStock Bundle</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: #f8fafc;">
    <div class="container animate-fade-in">
        <header style="margin-bottom: 3rem;">
            <a href="new_sale.php" style="text-decoration: none; color: #64748b; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> กลับไปหน้าโปรโมชั่น
            </a>
        </header>

        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
            <div>
                <div class="card"
                    style="margin-bottom: 2rem; background: linear-gradient(135deg, white 0%, #f1f5f9 100%);">
                    <h1 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($bundle->name); ?></h1>
                    <p style="color: #64748b; line-height: 1.6; font-size: 1.125rem;">
                        <?php echo htmlspecialchars($bundle->description); ?></p>
                </div>

                <div class="card">
                    <h3
                        style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: #1e293b;">
                        <i class="fa-solid fa-list-ol" style="color: #8b5cf6;"></i> อุปกรณ์ในเซตนี้
                    </h3>
                    <div style="display: grid; gap: 1rem;">
                        <?php foreach ($components as $cat => $p): ?>
                            <div
                                style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #f1f5f9;">
                                <div
                                    style="width: 50px; height: 50px; background: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--primary-color); border: 1px solid #e2e8f0;">
                                    <i class="fa-solid <?php echo $p->icon ?? 'fa-box'; ?>"></i>
                                </div>
                                <div style="flex: 1;">
                                    <span
                                        style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;"><?php echo $cat; ?></span>
                                    <span style="font-weight: 600; color: #1e293b;"><?php echo $p->name; ?></span>
                                </div>
                                <div style="color: #94a3b8; font-size: 0.8rem;">Ready</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <aside>
                <div class="card" style="position: sticky; top: 2rem; border-top: 4px solid #8b5cf6;">
                    <div style="font-weight: 700; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase;">
                        ราคาทั้งเซต</div>
                    <div style="font-size: 3rem; font-weight: 800; color: #8b5cf6; margin-bottom: 1rem;">
                        ฿<?php echo number_format($bundle->price); ?></div>

                    <div
                        style="background: rgba(16, 185, 129, 0.1); color: #166534; padding: 1rem; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 2rem;">
                        <i class="fa-solid fa-tags"></i> คุณประหยัดไปได้ถึง <strong>฿3,500</strong> เมื่อซื้อเซตนี้
                    </div>

                    <button onclick="location.href='builder.php?load_bundle=<?php echo $bundle->id; ?>'"
                        class="btn btn-primary" style="width: 100%; height: 3.5rem; background: #8b5cf6;">
                        <i class="fa-solid fa-check-circle"></i> ตกลง ใช้เซตนี้
                    </button>
                    <p style="text-align: center; color: #94a3b8; font-size: 0.75rem; margin-top: 1.5rem;">
                        * คุณสามารถปรับแต่งอุปกรณ์บางชิ้นได้ในหน้าจัดสเปค
                    </p>
                </div>
            </aside>
        </div>
    </div>
</body>

</html>