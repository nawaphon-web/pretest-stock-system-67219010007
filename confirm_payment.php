<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $paymentMethod = $_POST['payment_method'];

    // Update order status to 'paid'
    $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ? AND user_id = ?");
    $stmt->execute([$orderId, $_SESSION['user_id']]);

    // In a real system, you would also log the payment in a 'payments' table

    header("Location: order_success.php?order_id=" . $orderId);
    exit;
}
else {
    header("Location: user_dashboard.php");
    exit;
}
?>
