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
        .payment-card {
            max-width: 600px;
            margin: 4rem auto;
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .method-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .method-btn:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }
        .method-btn i {
            font-size: 2rem;
            color: var(--primary-color);
        }
        .qr-section {
            display: none;
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .qr-placeholder {
            width: 200px;
            height: 200px;
            background: white;
            margin: 0 auto 1.5rem;
            padding: 10px;
            border-radius: 10px;
        }
        .qr-placeholder img {
            width: 100%;
            height: 100%;
        }
        .total-badge {
            background: var(--primary-gradient);
            padding: 1rem 2rem;
            border-radius: 1rem;
            display: inline-block;
            margin: 1rem 0;
            font-weight: 700;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <h1 style="text-align: center;"><i class="fa-solid fa-shield-check" style="color: #10b981;"></i> Secure Payment</h1>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Order #<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?></p>
        
        <div style="text-align: center;">
            <div class="total-badge">
                Total Amount: ฿<?php echo number_format($order['total_amount'], 2); ?>
            </div>
        </div>

        <div class="payment-methods" id="methods">
            <button class="method-btn" onclick="showQR()">
                <i class="fa-solid fa-qrcode"></i>
                <span>Thai QR Payment</span>
            </button>
            <button class="method-btn" onclick="simulatePayment('Credit Card')">
                <i class="fa-solid fa-credit-card"></i>
                <span>Credit / Debit Card</span>
            </button>
            <button class="method-btn" onclick="simulatePayment('Mobile Banking')">
                <i class="fa-solid fa-mobile-screen-button"></i>
                <span>Mobile Banking</span>
            </button>
            <button class="method-btn" onclick="simulatePayment('Installment')">
                <i class="fa-solid fa-calendar-days"></i>
                <span>0% Installment</span>
            </button>
        </div>

        <div class="qr-section" id="qrSection">
            <h3>Scan to Pay</h3>
            <div class="qr-placeholder">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TECHSTOCK_ORDER_<?php echo $orderId; ?>" alt="QR Code">
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Scan with any banking app</p>
            <button class="btn-checkout" onclick="simulatePayment('QR Code')">I've Paid Successfully</button>
            <button class="btn-back" style="margin-top: 1rem;" onclick="hideQR()">Back to Methods</button>
        </div>

        <form id="paymentForm" action="confirm_payment.php" method="POST" style="display: none;">
            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
            <input type="hidden" name="payment_method" id="paymentMethod">
        </form>
    </div>

    <script>
        function showQR() {
            document.getElementById('methods').style.display = 'none';
            document.getElementById('qrSection').style.display = 'block';
        }
        function hideQR() {
            document.getElementById('methods').style.display = 'grid';
            document.getElementById('qrSection').style.display = 'none';
        }
        function simulatePayment(method) {
            document.getElementById('paymentMethod').value = method;
            document.getElementById('paymentForm').submit();
        }
    </script>
</body>
</html>
