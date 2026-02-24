<?php
require '../db.php';
require '../includes/Bundle.php';
require '../includes/Product.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$bundle = Bundle::findById($pdo, $id);

if (!$bundle) {
    echo json_encode(['error' => 'Bundle not found']);
    exit;
}

$items = $bundle->getItems($pdo);
$result = [];
foreach ($items as $p) {
    // Determine category from category_id
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$p->category_id]);
    $catName = $stmt->fetchColumn();

    $result[$catName] = [
        'id' => $p->id,
        'name' => $p->name,
        'price' => $p->price,
        'icon' => $p->icon
    ];
}

echo json_encode([
    'name' => $bundle->name,
    'discount_price' => $bundle->discount_price,
    'items' => $result
]);
