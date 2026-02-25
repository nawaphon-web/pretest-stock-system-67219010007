<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header("Location: user_dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Complete - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .success-card {
            max-width: 600px;
            margin: 4rem auto;
            text-align: center;
            background: var(--card-bg);
            padding: 3rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
            backdrop-filter: blur(20px);
        }
        .success-icon {
            font-size: 5rem;
            color: #10b981;
            margin-bottom: 2rem;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h1 style="font-size: 3rem;">รายการเสร็จสิ้น</h1>
        <p style="color: var(--text-muted); margin: 1rem 0 2rem; font-size: 1.2rem;">
            Order #<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?> ได้รับการยืนยันเรียบร้อยแล้ว
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="user_dashboard.php" class="btn-checkout" style="text-decoration: none; padding: 1rem 2rem;">กลับสู่หน้าแรก</a>
            <a href="quotation_view.php?order_id=<?php echo $orderId; ?>" class="btn-back" style="text-decoration: none; padding: 1rem 2rem;">ดูรายละเอียด</a>
        </div>
    </div>
</body>
</html>
