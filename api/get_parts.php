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
            $reason = "GPU too long for Case (GPU: {$product->getSpec('length_mm')}mm vs Case: {$case->getSpec('max_gpu_length')}mm)";
        }
    }

    if ($category === 'case' && $product) {
        $gpu = isset($currentBuild['gpu']) ? Product::findById($pdo, $currentBuild['gpu']['id']) : null;
        if ($gpu && !CompatibilityService::checkGpuCase($gpu, $product)) {
            $isCompatible = false;
            $reason = "Case too small for GPU (GPU: {$gpu->getSpec('length_mm')}mm vs Case: {$product->getSpec('max_gpu_length')}mm)";
        }
    }

    if ($category === 'psu') {
        // Calculate recommended PSU
        $parts = [];
        foreach (['cpu', 'mainboard', 'ram', 'gpu', 'ssd', 'case'] as $catKey) {
            if (isset($currentBuild[$catKey])) {
                $parts[] = Product::findById($pdo, $currentBuild[$catKey]['id']);
            }
        }
        $totalTdp = CompatibilityService::calculateTotalTdp($parts);
        $gpuRecPsu = 0;
        if (isset($currentBuild['gpu'])) {
            $gpu = Product::findById($pdo, $currentBuild['gpu']['id']);
            $gpuRecPsu = $gpu->getSpec('recommended_psu') ?? 0;
        }
        $recommendedWattage = CompatibilityService::recommendPsuWattage($totalTdp, $gpuRecPsu);

        if ($product->getSpec('wattage') < $recommendedWattage) {
            $isCompatible = false;
            $reason = "Recommended PSU wattage is {$recommendedWattage}W (Current build needs ~" . round($totalTdp) . "W TDP)";
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