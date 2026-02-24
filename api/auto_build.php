<?php
require '../db.php';
require '../includes/Product.php';
require '../includes/CompatibilityService.php';

header('Content-Type: application/json');

$budget = isset($_GET['budget']) ? floatval($_GET['budget']) : 0;

if ($budget < 15000) {
    echo json_encode(['error' => 'งบประมาณขั้นต่ำที่แนะนำคือ 15,000 บาท']);
    exit;
}

// Percentages for components
$allocation = [
    'gpu' => 0.35,
    'cpu' => 0.20,
    'mainboard' => 0.10,
    'ram' => 0.08,
    'ssd' => 0.07,
    'psu' => 0.06,
    'case' => 0.05,
    'cooler' => 0.03,
    'keyboard' => 0.03,
    'mouse' => 0.03
];

$build = [];
$remainingBudget = $budget;

// 1. Pick CPU first (Crucial for socket)
$cpus = Product::findByCategory($pdo, 'cpu');
usort($cpus, fn($a, $b) => $b->price <=> $a->price);
$targetCpuPrice = $budget * $allocation['cpu'];

foreach ($cpus as $p) {
    if ($p->price <= $targetCpuPrice * 1.2) { // Allow slight overhead
        $build['cpu'] = $p;
        $remainingBudget -= $p->price;
        break;
    }
}
if (!isset($build['cpu']))
    $build['cpu'] = end($cpus); // Fallback to cheapest

// 2. Pick Mainboard (Compatible with CPU)
$mbs = Product::findByCategory($pdo, 'mainboard');
usort($mbs, fn($a, $b) => $b->price <=> $a->price);
$targetMbPrice = $budget * $allocation['mainboard'];

foreach ($mbs as $p) {
    if (CompatibilityService::checkCpuMainboard($build['cpu'], $p)) {
        if ($p->price <= $targetMbPrice * 1.3) {
            $build['mainboard'] = $p;
            $remainingBudget -= $p->price;
            break;
        }
    }
}
if (!isset($build['mainboard'])) {
    foreach (array_reverse($mbs) as $p) {
        if (CompatibilityService::checkCpuMainboard($build['cpu'], $p)) {
            $build['mainboard'] = $p;
            $remainingBudget -= $p->price;
            break;
        }
    }
}

// 3. Pick RAM (Compatible with MB)
$rams = Product::findByCategory($pdo, 'ram');
usort($rams, fn($a, $b) => $b->price <=> $a->price);
foreach ($rams as $p) {
    if (CompatibilityService::checkRamMainboard($p, $build['mainboard'])) {
        $build['ram'] = $p;
        $remainingBudget -= $p->price;
        break;
    }
}

// 4. GPU (Largest remaining chunk)
$gpus = Product::findByCategory($pdo, 'gpu');
usort($gpus, fn($a, $b) => $b->price <=> $a->price);
foreach ($gpus as $p) {
    if ($p->price <= $remainingBudget * 0.7) { // Greedy but safe
        $build['gpu'] = $p;
        $remainingBudget -= $p->price;
        break;
    }
}

// 5. Peripherals & Rest (Order by remaining balance)
$categories = ['psu', 'ssd', 'case', 'cooler', 'keyboard', 'mouse'];
foreach ($categories as $cat) {
    $parts = Product::findByCategory($pdo, $cat);
    usort($parts, fn($a, $b) => $b->price <=> $a->price);
    foreach ($parts as $p) {
        if ($p->price <= $remainingBudget / (count($categories) - array_search($cat, $categories))) {
            $build[$cat] = $p;
            $remainingBudget -= $p->price;
            break;
        }
    }
    if (!isset($build[$cat])) {
        $cheapest = end($parts);
        $build[$cat] = $cheapest;
        $remainingBudget -= $cheapest->price;
    }
}

// Final response formatting
$result = [];
$totalPrice = 0;
foreach ($build as $cat => $p) {
    $result[$cat] = [
        'id' => $p->id,
        'name' => $p->name,
        'price' => $p->price,
        'tdp' => $p->getSpec('tdp') ?? $p->getSpec('tdp_rating') ?? 0
    ];
    $totalPrice += $p->price;
}

echo json_encode([
    'build' => $result,
    'total_price' => $totalPrice,
    'remaining' => $remainingBudget
]);
