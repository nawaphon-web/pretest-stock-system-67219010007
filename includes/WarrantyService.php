<?php
class WarrantyService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Check warranty status by Serial Number
     */
    public function checkWarranty($serialNumber)
    {
        $stmt = $this->pdo->prepare("
            SELECT i.*, p.name as product_name, o.created_at as sale_date
            FROM inventory i
            JOIN products p ON i.product_id = p.id
            LEFT JOIN order_items oi ON i.id = oi.inventory_id
            LEFT JOIN orders o ON oi.order_id = o.id
            WHERE i.serial_number = ?
        ");
        $stmt->execute([$serialNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create an RMA request
     */
    public function createRma($userId, $inventoryId, $reason)
    {
        $stmt = $this->pdo->prepare("INSERT INTO rma_requests (user_id, inventory_id, reason, status) VALUES (?, ?, ?, 'received')");
        $stmt->execute([$userId, $inventoryId, $reason]);

        // Update inventory status
        $stmtUpdate = $this->pdo->prepare("UPDATE inventory SET status = 'rma' WHERE id = ?");
        $stmtUpdate->execute([$inventoryId]);

        return $this->pdo->lastInsertId();
    }

    /**
     * Update RMA status (Back Office)
     */
    public function updateRmaStatus($rmaId, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE rma_requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $rmaId]);

        if ($status === 'done') {
            // Logic for returned or replaced item could go here
        }
        return true;
    }
}
?>