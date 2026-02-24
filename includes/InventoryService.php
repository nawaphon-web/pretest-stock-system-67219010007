<?php
class InventoryService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Add items to inventory with serial numbers
     */
    public function receiveStock($productId, $serialNumbers, $supplierId)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO inventory (product_id, serial_number, supplier_id, status) VALUES (?, ?, ?, 'available')");
            foreach ($serialNumbers as $sn) {
                $stmt->execute([$productId, $sn, $supplierId]);
            }
            // Update the main product stock count
            $stmtUpdate = $this->pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmtUpdate->execute([count($serialNumbers), $productId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Get available S/N for a product
     */
    public function getAvailableSn($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inventory WHERE product_id = ? AND status = 'available'");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Record a sale and mark S/N as sold
     */
    public function markAsSold($inventoryId)
    {
        $stmt = $this->pdo->prepare("UPDATE inventory SET status = 'sold' WHERE id = ?");
        return $stmt->execute([$inventoryId]);
    }
}
?>