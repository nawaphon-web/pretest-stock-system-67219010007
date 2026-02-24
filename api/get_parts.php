<?php
require '../db.php';
require '../includes/Product.php';
require '../includes/CompatibilityService.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';
$currentBuild = json_decode($_GET['current_build'] ?? '{}', true);

if (!$category) {
    echo json_encode(['error' => 'จำเป็นต้องระบุหมวดหมู่']);
    exit;
}

// Fetch all products in category
$products = Product::findByCategory($pdo, $category);
$compatibleProducts = [];

// Load current build parts if they exist
$cpu = isset($currentBuild['cpu']) ? Product::findById($pdo, $currentBuild['cpu']['id']) : null;
$mainboard = isset($currentBuild['mainboard']) ? Product::findById($pdo, $currentBuild['mainboard']['id']) : null;
$case = isset($currentBuild['case']) ? Product::findById($pdo, $currentBuild['case']['id']) : null;

foreach ($products as $product) {
    $isCompatible = true;
    $reason = '';

    // Compatibility Logic based on what we are fetching
    if ($category === 'mainboard' && $cpu) {
        if (!CompatibilityService::checkCpuMainboard($cpu, $product)) {
            $isCompatible = false;
            $reason = "ซ็อกเก็ตไม่ตรงกัน (CPU: {$cpu->getSpec('socket')} vs MB: {$product->getSpec('socket')})";
        }
    }

    if ($category === 'cpu' && $mainboard) {
        if (!CompatibilityService::checkCpuMainboard($product, $mainboard)) {
            $isCompatible = false;
            $reason = "ซ็อกเก็ตไม่ตรงกัน";
        }
    }

    if ($category === 'ram' && $mainboard) {
        if (!CompatibilityService::checkRamMainboard($product, $mainboard)) {
            $isCompatible = false;
            $reason = "ประเภทหน่วยความจำไม่ตรงกัน (บอร์ดรองรับ: {$mainboard->getSpec('memory_type')})";
        }
    }

    if ($category === 'cooler') {
        if ($cpu && !CompatibilityService::checkCoolerCpuSocket($product, $cpu)) {
            $isCompatible = false;
            $reason = "ซิงค์ไม่รองรับซ็อกเก็ตนี้ (CPU: {$cpu->getSpec('socket')})";
        }
        if ($case && !CompatibilityService::checkCoolerCase($product, $case)) {
            $isCompatible = false;
            $reason = "ซิงค์สูงเกินไปสำหรับเคสนี้ (ซิงค์: {$product->getSpec('height_mm')}มม. vs เคส: {$case->getSpec('max_cpu_height')}มม.)";
        }
    }

    if ($category === 'gpu' && $case) {
        if (!CompatibilityService::checkGpuCase($product, $case)) {
            $isCompatible = false;
            $reason = "การ์ดจอยาวเกินไปสำหรับเคสนี้ (การ์ดจอ: {$product->getSpec('length_mm')}มม. vs เคส: {$case->getSpec('max_gpu_length')}มม.)";
        }
    }

    if ($category === 'case' && $product) {
        $gpu = isset($currentBuild['gpu']) ? Product::findById($pdo, $currentBuild['gpu']['id']) : null;
        $cooler = isset($currentBuild['cooler']) ? Product::findById($pdo, $currentBuild['cooler']['id']) : null;
        if ($gpu && !CompatibilityService::checkGpuCase($gpu, $product)) {
            $isCompatible = false;
            $reason = "เคสเล็กเกินไปสำหรับการ์ดจอนี้ (การ์ดจอ: {$gpu->getSpec('length_mm')}มม. vs เคส: {$product->getSpec('max_gpu_length')}มม.)";
        }
        if ($cooler && !CompatibilityService::checkCoolerCase($cooler, $product)) {
            $isCompatible = false;
            $reason = "เคสเล็กเกินไปสำหรับซิงค์นี้ (ซิงค์: {$cooler->getSpec('height_mm')}มม. vs เคส: {$product->getSpec('max_cpu_height')}มม.)";
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
            $reason = "กำลังไฟ PSU ที่แนะนำคือ {$recommendedWattage}W (สเปคนี้ต้องการประมาณ " . round($totalTdp) . "W TDP)";
        }
    }

    // Simplified filtering: No more strict hiding.
    // Let the frontend handle the styling of incompatible items.

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