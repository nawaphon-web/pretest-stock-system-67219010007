<?php
class OrderService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Create a new order
     */
    public function createOrder($userId, $totalAmount, $items, $assemblyService = false, $taxInfo = null)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_amount, assembly_service, tax_info, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->execute([$userId, $totalAmount, $assemblyService ? 1 : 0, json_encode($taxInfo)]);
            $orderId = $this->pdo->lastInsertId();

            $stmtItem = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)");
            foreach ($items as $item) {
                // In a full implementation, we'd also link an available inventory_id (S/N) here
                $stmtItem->execute([$orderId, $item['product_id'], $item['price']]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Generate Quotation PDF (Placeholder for logic)
     */
    public function generateQuotation($orderId)
    {
        // In a real app, use TCPDF or Dompdf here
        return "QUO-" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . ".pdf";
    }

    /**
     * Calculate Installments
     */
    public static function calculateInstallments($amount, $months = 10)
    {
        $monthly = $amount / $months;
        return round($monthly, 2);
    }
}
?>