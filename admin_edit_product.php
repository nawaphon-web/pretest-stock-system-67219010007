<?php
session_start();
require 'db.php';
require 'includes/Product.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
$product = null;
$error = '';
$success = '';

// Load existing product
if ($id) {
    $product = Product::findById($pdo, $id);
}

// Fetch categories for dropdown
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'category_id' => $_POST['category_id'],
        'price' => $_POST['price'],
        'stock' => $_POST['stock'],
        'image_url' => $_POST['image_url'],
        'specifications' => json_decode($_POST['specifications'], true)
    ];

    if ($data['specifications'] === null && $_POST['specifications'] !== '') {
        $error = "รูปแบบ JSON ใน Specifications ไม่ถูกต้อง";
    } else {
        if ($id) {
            if (Product::update($pdo, $id, $data)) {
                $success = "อัปเดตข้อมูลสินค้าสำเร็จ";
                $product = Product::findById($pdo, $id); // Reload
            } else {
                $error = "ไม่สามารถอัปเดตข้อมูลได้";
            }
        } else {
            if (Product::create($pdo, $data)) {
                $success = "เพิ่มสินค้าใหม่สำเร็จ";
                // If it's a new product, we might want to stay on the page or go back
                header("Location: admin_products.php?success=1");
                exit;
            } else {
                $error = "ไม่สามารถเพิ่มสินค้าได้";
            }
        }
    }
}

// Prepare specs for display in textarea
$specsJson = '';
if ($product && $product->specifications) {
    $specsJson = json_encode($product->specifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} elseif (!$product) {
    // Default template for new item if category is known
    $specsJson = "{}";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $id ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่'; ?> - TechStock Admin
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: white;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-save {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        textarea.form-control {
            font-family: monospace;
            height: 200px;
        }
    </style>
</head>

<body>
    <div class="edit-container">
        <a href="admin_products.php"
            style="color: var(--text-muted); text-decoration: none; display: block; margin-bottom: 1rem;">
            <i class="fa-solid fa-arrow-left"></i> กลับสู่หน้ารายการสินค้า
        </a>
        <h1>
            <?php echo $id ? 'แก้ไขข้อมูลสินค้า' : 'เพิ่มสินค้าใหม่'; ?>
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">
            <?php echo $id ? "รหัสสินค้า: #$id" : "กรอกข้อมูลสินค้าใหม่ด้านล่าง"; ?>
        </p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>ชื่อสินค้า</label>
                <input type="text" name="name" class="form-control" required
                    value="<?php echo $product ? htmlspecialchars($product->name) : ''; ?>">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>หมวดหมู่</label>
                    <select name="category_id" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($product && $product->category_id == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo strtoupper($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>ราคา (บาท)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required
                        value="<?php echo $product ? $product->price : ''; ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>จำนวนสต็อก</label>
                    <input type="number" name="stock" class="form-control" required
                        value="<?php echo $product ? $product->stock : '0'; ?>">
                </div>
                <div class="form-group">
                    <label>URL รูปภาพ</label>
                    <input type="text" name="image_url" class="form-control"
                        value="<?php echo $product ? htmlspecialchars($product->image_url) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>ข้อมูลทางเทคนิค (Specifications) รูปแบบ JSON</label>
                <textarea name="specifications"
                    class="form-control"><?php echo htmlspecialchars($specsJson); ?></textarea>
                <small style="color: var(--text-muted);">ตัวอย่าง: {"socket": "LGA1700", "tdp": 125}</small>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa-solid fa-save"></i>
                <?php echo $id ? 'บันทึกการแก้ไข' : 'เพิ่มสินค้า'; ?>
            </button>
        </form>
    </div>
</body>

</html>