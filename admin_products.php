<?php
session_start();
require 'db.php';
require 'includes/Product.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    Product::delete($pdo, $_GET['delete']);
    header("Location: admin_products.php");
    exit;
}

$products = Product::findAll($pdo);
$successMsg = isset($_GET['success']) ? "ดำเนินการสำเร็จ" : "";
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - TechStock Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .product-table th,
        .product-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .product-table th {
            color: var(--primary-color);
            background: rgba(255, 255, 255, 0.02);
        }

        .btn-add {
            background: var(--primary-color);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-edit {
            color: #3b82f6;
            margin-right: 1rem;
            text-decoration: none;
        }

        .btn-delete {
            color: #ef4444;
            text-decoration: none;
        }

        .stock-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .stock-low {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .stock-ok {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <a href="admin_dashboard.php"
                    style="color: var(--text-muted); text-decoration: none; display: block; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-arrow-left"></i> กลับสู่หน้า Dashboard
                </a>
                <h1>จัดการรายการสินค้า</h1>
            </div>
            <a href="admin_edit_product.php" class="btn-add"><i class="fa-solid fa-plus"></i> เพิ่มสินค้าใหม่</a>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>รูปภาพ</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>สต็อก</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <?php echo $p->id; ?>
                        </td>
                        <td>
                            <img src="<?php echo $p->image_url; ?>" alt=""
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td>
                            <?php echo htmlspecialchars($p->name); ?>
                        </td>
                        <td>฿
                            <?php echo number_format($p->price); ?>
                        </td>
                        <td>
                            <span class="stock-badge <?php echo $p->stock < 5 ? 'stock-low' : 'stock-ok'; ?>">
                                <?php echo $p->stock; ?> ชิ้น
                            </span>
                        </td>
                        <td>
                            <a href="admin_edit_product.php?id=<?php echo $p->id; ?>" class="btn-edit"><i
                                    class="fa-solid fa-pen-to-square"></i> แก้ไข</a>
                            <a href="?delete=<?php echo $p->id; ?>" class="btn-delete"
                                onclick="return confirm('ยืนยันการลบสินค้า?')">
                                <i class="fa-solid fa-trash"></i> ลบ
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>