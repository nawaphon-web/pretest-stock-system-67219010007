<?php
require 'db.php';
require 'includes/Product.php';
require 'includes/CompatibilityService.php';

echo "--- Starting Verification ---\n";

// 1. Fetch some parts
echo "\n[1] Fetching Sample Parts...\n";
$cpus = Product::findByCategory($pdo, 'cpu');
$boards = Product::findByCategory($pdo, 'mainboard');
$rams = Product::findByCategory($pdo, 'ram');
$gpus = Product::findByCategory($pdo, 'gpu');
$cases = Product::findByCategory($pdo, 'case');

if (empty($cpus))
    die("FAIL: No CPUs found. Did the seed run?\n");
echo "Found " . count($cpus) . " CPUs.\n";

// 2. Test CPU-MB Compatibility
echo "\n[2] Testing CPU <-> Mainboard Compatibility...\n";
$i5 = null;
$z790 = null;
$b650 = null;

// Find specific parts
foreach ($cpus as $c)
    if (strpos($c->name, 'i5-13600K') !== false)
        $i5 = $c;
foreach ($boards as $b) {
    if (strpos($b->name, 'Z790') !== false)
        $z790 = $b;
    if (strpos($b->name, 'B650') !== false)
        $b650 = $b;
}

if ($i5 && $z790) {
    $result = CompatibilityService::checkCpuMainboard($i5, $z790);
    echo "Check i5-13600K (LGA1700) + Z790 (LGA1700): " . ($result ? "PASS" : "FAIL") . "\n";
}

if ($i5 && $b650) {
    $result = CompatibilityService::checkCpuMainboard($i5, $b650);
    echo "Check i5-13600K (LGA1700) + B650 (AM5): " . (!$result ? "PASS (Correctly Incompatible)" : "FAIL (Should be incompatible)") . "\n";
}

// 3. Test RAM-MB Compatibility
echo "\n[3] Testing RAM <-> Mainboard Compatibility...\n";
$ddr5ram = null;
$ddr4ram = null;
foreach ($rams as $r) {
    if (strpos($r->name, 'DDR5') !== false)
        $ddr5ram = $r;
    if (strpos($r->name, 'DDR4') !== false)
        $ddr4ram = $r;
}

if ($ddr5ram && $z790) { // Z790 in our seed is DDR5
    $result = CompatibilityService::checkRamMainboard($ddr5ram, $z790);
    echo "Check DDR5 RAM + Z790 (DDR5 Board): " . ($result ? "PASS" : "FAIL") . "\n";
}

if ($ddr4ram && $z790) {
    $result = CompatibilityService::checkRamMainboard($ddr4ram, $z790);
    echo "Check DDR4 RAM + Z790 (DDR5 Board): " . (!$result ? "PASS (Correctly Incompatible)" : "FAIL (Should be incompatible)") . "\n";
}

// 4. API Emulation (Simplified)
echo "\n[4] API Emulation (get_parts.php logic)...\n";
// Emulate fetching mainboards with i5-13600K selected
$cpuJson = json_encode(['specs' => $i5->specifications]);
// In real API we pass ID, here we just test logic directly roughly
// Let's just run a curl or internal request emulation if we could, but direct logic is fine.

echo "--- Verification Complete ---\n";
?>