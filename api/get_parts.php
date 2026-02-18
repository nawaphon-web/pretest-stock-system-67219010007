<?php
require '../db.php';
require '../includes/Product.php';
require '../includes/CompatibilityService.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';
$currentBuild = json_decode($_GET['current_build'] ?? '{}', true);

if (!$category) {
    echo json_encode(['error' => 'Category required']);
    exit;
}

// Fetch all products in category
$products = Product::findByCategory($pdo, $category);
$compatibleProducts = [];

// Load current build parts if they exist
$cpu = isset($currentBuild['cpu']) ? Product::findById($pdo, $currentBuild['cpu']) : null;
$mainboard = isset($currentBuild['mainboard']) ? Product::findById($pdo, $currentBuild['mainboard']) : null;
$case = isset($currentBuild['case']) ? Product::findById($pdo, $currentBuild['case']) : null;

foreach ($products as $product) {
    $isCompatible = true;
    $reason = '';

    // Compatibility Logic based on what we are fetching
    if ($category === 'mainboard' && $cpu) {
        if (!CompatibilityService::checkCpuMainboard($cpu, $product)) {
            $isCompatible = false;
            $reason = "Socket mismatch (CPU: {$cpu->getSpec('socket')} vs MB: {$product->getSpec('socket')})";
        }
    }

    if ($category === 'cpu' && $mainboard) {
        if (!CompatibilityService::checkCpuMainboard($product, $mainboard)) {
            $isCompatible = false;
            $reason = "Socket mismatch";
        }
    }

    if ($category === 'ram' && $mainboard) {
        if (!CompatibilityService::checkRamMainboard($product, $mainboard)) {
            $isCompatible = false;
            $reason = "Memory Type mismatch (Board: {$mainboard->getSpec('memory_type')})";
        }
    }

    if ($category === 'gpu' && $case) {
        if (!CompatibilityService::checkGpuCase($product, $case)) {
            $isCompatible = false;
            $reason = "GPU too long for Case";
        }
    }
    if ($category === 'case' && $product) {
        // If we are picking a case, we might want to check against already picked GPU
        // But usually flow is CPU -> MB -> RAM -> GPU -> Case
        // For now, let's assume if GPU is picked, we filter Case
        $gpu = isset($currentBuild['gpu']) ? Product::findById($pdo, $currentBuild['gpu']) : null;
        if ($gpu && !CompatibilityService::checkGpuCase($gpu, $product)) {
            $isCompatible = false;
            $reason = "Case too small for GPU";
        }
    }


    $compatibleProducts[] = [
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'image_url' => $product->image_url,
        'specs' => $product->specifications,
        'is_compatible' => $isCompatible,
        'incompatibility_reason' => $reason
    ];
}

echo json_encode($compatibleProducts);
?>