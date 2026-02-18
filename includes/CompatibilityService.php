<?php
class CompatibilityService
{

    // Check if CPU and Mainboard have the same socket
    public static function checkCpuMainboard($cpu, $mainboard)
    {
        if (!$cpu || !$mainboard)
            return true; // Can't check if one is missing
        $cpuSocket = $cpu->getSpec('socket');
        $mbSocket = $mainboard->getSpec('socket');
        return $cpuSocket === $mbSocket;
    }

    // Check if RAM matches Mainboard memory type and slots
    public static function checkRamMainboard($ram, $mainboard, $ramCount = 1)
    {
        if (!$ram || !$mainboard)
            return true;

        // Check Type (DDR4 vs DDR5)
        $ramType = $ram->getSpec('memory_type');
        $mbRamType = $mainboard->getSpec('memory_type');

        if ($ramType !== $mbRamType) {
            return false;
        }

        // Check Slots (Simplified: just checking if board has enough slots for total sticks)
        // Ideally we'd need to know how many sticks come in the RAM pack. 
        // For this demo, let's assume 'modules' key in RAM specs tells us count (e.g. 2x16GB -> 2 modules)
        $ramModules = $ram->getSpec('modules') ?? 1;
        $totalModules = $ramModules * $ramCount;
        $mbSlots = $mainboard->getSpec('memory_slots');

        return $totalModules <= $mbSlots;
    }

    // Check if GPU fits in the Case
    public static function checkGpuCase($gpu, $case)
    {
        if (!$gpu || !$case)
            return true;

        $gpuLength = $gpu->getSpec('length_mm');
        $maxGpuLength = $case->getSpec('max_gpu_length');

        if ($gpuLength && $maxGpuLength) {
            return $gpuLength <= $maxGpuLength;
        }
        return true; // Assume true if specs missing
    }

    // Calculate total TDP of the system
    public static function calculateTotalTdp($parts)
    {
        $totalTdp = 0;
        foreach ($parts as $part) {
            if ($part) {
                $tdp = $part->getSpec('tdp');
                if ($tdp) {
                    $totalTdp += $tdp;
                }
            }
        }
        // Base system overhead (fans, ssd, etc.)
        $totalTdp += 50;
        return $totalTdp;
    }

    // Recommendation for PSU wattage
    public static function recommendPsuWattage($totalTdp)
    {
        // Rule of thumb: Load should be around 50-70% of PSU capacity for efficiency
        // Or at least +100-150W headroom.
        return ceil(($totalTdp * 1.5) / 50) * 50; // Round up to nearest 50
    }
}
?>