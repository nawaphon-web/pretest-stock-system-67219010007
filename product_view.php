<?php
session_start();
require 'db.php';
require 'includes/Product.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: new_sale.php");
    exit;
}

$p = Product::findById($pdo, $id);
if (!$p) {
    header("Location: new_sale.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $p->name; ?> - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: #f8fafc;">
    <div class="container animate-fade-in">
        <header style="margin-bottom: 3rem;">
            <a href="new_sale.php" style="text-decoration: none; color: #64748b; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> กลับไปหน้าสินค้า
            </a>
        </header>

        <div class="card" style="display: grid; grid-template-columns: 400px 1fr; gap: 3rem; padding: 3rem;">
            <div
                style="background: #f1f5f9; border-radius: 1rem; display: flex; align-items: center; justify-content: center; height: 400px; font-size: 10rem; color: #cbd5e1;">
                <i class="fa-solid <?php echo $p->icon ?? 'fa-box'; ?>"></i>
            </div>

            <div>
                <span class="badge <?php echo $p->stock > 0 ? 'badge-success' : 'badge-danger'; ?>"
                    style="margin-bottom: 1rem;">
                    <?php echo $p->stock > 0 ? 'สินค้าพร้อมจำหน่าย' : 'สินค้าหมด'; ?>
                </span>
                <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($p->name); ?></h1>
                <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 2rem;">รหัสสินค้า:
                    TECH-<?php echo str_pad($p->id, 5, '0', STR_PAD_LEFT); ?></p>

                <div
                    style="background: #f8fafc; padding: 2rem; border-radius: 1rem; margin-bottom: 2.5rem; border: 1px solid #f1f5f9;">
                    <div style="font-size: 0.875rem; font-weight: 700; color: #94a3b8; margin-bottom: 0.5rem;">ราคาพิเศษ
                    </div>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color);">
                        ฿<?php echo number_format($p->sale_price ?? $p->price); ?>
                    </div>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <h3
                        style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; display: inline-block;">
                        ข้อมูลทางเทคนิค
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <?php if ($p->specifications): ?>
                            <?php foreach ($p->specifications as $key => $value): ?>
                                <div style="display: flex; gap: 0.5rem; font-size: 0.9rem;">
                                    <span
                                        style="color: #94a3b8; font-weight: 600; min-width: 100px;"><?php echo strtoupper(str_replace('_', ' ', $key)); ?>:</span>
                                    <span style="color: #475569;"><?php echo $value; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button onclick="location.href='builder.php?load_part=<?php echo $p->id; ?>'"
                        class="btn btn-primary" style="flex: 1; height: 3.5rem;">
                        <i class="fa-solid fa-plus-circle"></i> เลือกชิ้นนี้ใส่สเปคคอม
                    </button>
                    <button class="btn btn-outline" style="flex: 1;"><i class="fa-solid fa-copy"></i>
                        เปรียบเทียบสเปค</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>