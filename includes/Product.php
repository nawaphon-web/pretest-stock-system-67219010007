<?php
class Product
{
    public $id;
    public $name;
    public $category_id;
    public $price;
    public $stock;
    public $image_url;
    public $specifications; // Array

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->category_id = $data['category_id'];
        $this->price = $data['price'];
        $this->stock = $data['stock'];
        $this->image_url = $data['image_url'];
        // flexible parsing for JSON specs
        $this->specifications = is_string($data['specifications']) ? json_decode($data['specifications'], true) : $data['specifications'];
    }

    public static function findAll($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM products");
        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    public static function findByCategory($pdo, $categoryName)
    {
        // First get category ID
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$categoryName]);
        $cat = $stmt->fetch();

        if (!$cat)
            return [];

        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$cat['id']]);

        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    public static function findById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Product($row);
        }
        return null;
    }

    // Helper to get a specific spec value safely
    public function getSpec($key)
    {
        return isset($this->specifications[$key]) ? $this->specifications[$key] : null;
    }
}
?>