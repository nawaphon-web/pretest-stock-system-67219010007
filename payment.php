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

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

if ($order['status'] !== 'pending') {
    header("Location: order_success.php?order_id=" . $orderId);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
        }
        .info-frame {
            width: 100%;
            height: 500px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            margin-bottom: 2rem;
            background: white;
        }
        .confirm-section {
            text-align: center;
            padding: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .total-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body style="padding: 1rem;">
    <div class="payment-container">
        <h1 style="text-align: center; margin-bottom: 1rem;"><i class="fa-solid fa-qrcode"></i> QR Code Payment Info</h1>
        
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">
            Please review the QR Code payment guide below before confirming your payment for Order #<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?>.
        </p>

        <!-- Showing the requested URL content -->
        <iframe src="https://packtica.co.th/what-is-qr-code-and-how-many-types/" class="info-frame"></iframe>

        <div class="confirm-section">
            <div style="margin-bottom: 1.5rem;">
                <span style="color: var(--text-muted);">Total Amount to Pay:</span>
                <div class="total-amount">฿<?php echo number_format($order['total_amount'], 2); ?></div>
            </div>

            <form action="confirm_payment.php" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
                <input type="hidden" name="payment_method" value="QR Code (Guided)">
                <button type="submit" class="cyber-btn" style="width: 100%; max-width: 400px; padding: 1.25rem; font-size: 1.25rem;">
                    <i class="fa-solid fa-check-circle"></i> ยืนยันจ่ายเงิน
                </button>
            </form>
            
            <p style="margin-top: 1.5rem; font-size: 0.8rem; color: var(--text-muted);">
                By clicking confirm, you acknowledge that you have completed the payment process.
            </p>
        </div>
    </div>
</body>
</html>
