<?php
class Bundle
{
    public $id;
    public $name;
    public $description;
    public $total_price;
    public $discount_price;
    public $icon;
    public $is_hot;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->total_price = $data['total_price'];
        $this->discount_price = $data['discount_price'];
        $this->icon = $data['icon'] ?? 'fa-box-open';
        $this->is_hot = (bool) ($data['is_hot'] ?? false);
    }

    public static function findAll($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM bundles ORDER BY is_hot DESC, id ASC");
        $bundles = [];
        while ($row = $stmt->fetch()) {
            $bundles[] = new Bundle($row);
        }
        return $bundles;
    }

    public static function findById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM bundles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new Bundle($row) : null;
    }

    public function getItems($pdo)
    {
        $stmt = $pdo->prepare("
            SELECT p.* FROM products p
            JOIN bundle_items bi ON p.id = bi.product_id
            WHERE bi.bundle_id = ?
        ");
        $stmt->execute([$this->id]);
        $products = [];
        require_once 'Product.php';
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }
}
