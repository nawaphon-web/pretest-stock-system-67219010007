<?php
class StockService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get true available stock (Total - Active Reservations)
     */
    public function getAvailableStock($productId)
    {
        // 1. Clear expired reservations first
        $this->clearExpiredReservations();

        // 2. Get total stock from products table
        $stmt = $this->pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $totalStock = $stmt->fetchColumn() ?: 0;

        // 3. Get active reservations
        $stmt = $this->pdo->prepare("SELECT SUM(quantity) FROM product_reservations WHERE product_id = ? AND expires_at > NOW()");
        $stmt->execute([$productId]);
        $reserved = $stmt->fetchColumn() ?: 0;

        return max(0, $totalStock - $reserved);
    }

    /**
     * Reserve stock for 15 minutes
     */
    public function reserveStock($productId, $sessionId, $userId = null, $quantity = 1)
    {
        $this->pdo->beginTransaction();
        try {
            $available = $this->getAvailableStock($productId);
            if ($available < $quantity) {
                $this->pdo->rollBack();
                return false;
            }

            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $stmt = $this->pdo->prepare("INSERT INTO product_reservations (product_id, session_id, user_id, quantity, expires_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$productId, $sessionId, $userId, $quantity, $expiresAt]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Confirm sale and remove reservation
     */
    public function finalizeSale($productId, $sessionId, $quantity = 1)
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Remove the reservation
            $stmt = $this->pdo->prepare("DELETE FROM product_reservations WHERE product_id = ? AND session_id = ? LIMIT 1");
            $stmt->execute([$productId, $sessionId]);

            // 2. Deduct from main stock
            $stmt = $this->pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$quantity, $productId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Cleanup expired reservations
     */
    public function clearExpiredReservations()
    {
        return $this->pdo->query("DELETE FROM product_reservations WHERE expires_at < NOW()");
    }
}
