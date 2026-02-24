<?php
require 'db.php';
require 'includes/WarrantyService.php';

$warrantyInfo = null;
$error = null;

if (isset($_GET['sn'])) {
    $sn = trim($_GET['sn']);
    if (!empty($sn)) {
        $ws = new WarrantyService($pdo);
        $warrantyInfo = $ws->checkWarranty($sn);
        if (!$warrantyInfo) {
            $error = "Serial Number not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ตรวจสอบประกันและสถานะเคลม - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-tracker {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            position: relative;
            padding-bottom: 2rem;
        }

        .status-step {
            flex: 1;
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            position: relative;
        }

        .status-step::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #334155;
            border-radius: 50%;
            display: block;
            margin: 0 auto 8px;
            position: relative;
            z-index: 2;
        }

        .status-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 5px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #334155;
            z-index: 1;
        }

        .status-step.active {
            color: var(--primary-color);
            font-weight: bold;
        }

        .status-step.active::before {
            background: var(--primary-color);
            box-shadow: 0 0 10px var(--primary-color);
        }

        .status-step.completed::before {
            background: #22c55e;
        }

        .status-step.completed::after {
            background: #22c55e;
        }
    </style>
</head>

<body style="display: flex; flex-direction: column; align-items: center; padding: 4rem;">
    <div class="login-container" style="max-width: 650px; width: 100%;">
        <i class="fa-solid fa-shield-halved logo-icon"></i>
        <h2>ตรวจสอบประกันและสถานะการเคลม</h2>
        <p class="subtitle">เช็คสถานะการรับประกันสินค้าของคุณด้วยหมายเลข Serial Number (S/N)</p>

        <form action="" method="GET" style="margin-bottom: 2rem;">
            <div class="form-group">
                <label>หมายเลข Serial Number (S/N)</label>
                <input type="text" name="sn" placeholder="ตัวอย่าง: SN-GPU-4070-001"
                    value="<?php echo htmlspecialchars($_GET['sn'] ?? ''); ?>" required>
            </div>
            <button type="submit">ตรวจสอบสถานะ</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                ไม่พบหมายเลข Serial Number นี้ในระบบ
            </div>
        <?php endif; ?>

        <?php if ($warrantyInfo): ?>
            <div class="warranty-result"
                style="text-align: left; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 1rem; border: 1px solid var(--primary-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;"><i class="fa-solid fa-circle-info"></i>
                    ข้อมูลสินค้า</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <p><strong>สินค้า:</strong> <?php echo $warrantyInfo['product_name']; ?></p>
                    <p><strong>S/N:</strong> <?php echo $warrantyInfo['serial_number']; ?></p>
                    <p><strong>ผู้จัดจำหน่าย (Vendor):</strong> <?php echo $warrantyInfo['supplier_name'] ?: 'N/A'; ?></p>
                    <p><strong>สถานะปัจจุบัน:</strong>
                        <span style="text-transform: uppercase; font-weight: bold; color: var(--primary-color);">
                            <?php
                            $statusMap = [
                                'available' => 'พร้อมจำหน่าย (ยังไม่ขาย)',
                                'sold' => 'ขายแล้ว (อยู่ในประกัน)',
                                'rma' => 'อยู่ระหว่างการเคลม',
                                'returned' => 'คืนสินค้าแล้ว'
                            ];
                            echo $statusMap[$warrantyInfo['status']] ?? $warrantyInfo['status'];
                            ?>
                        </span>
                    </p>
                </div>

                <?php if ($warrantyInfo['sale_date']): ?>
                    <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">
                    <div style="display: flex; justify-content: space-between;">
                        <p><strong>วันที่ซื้อ:</strong> <?php echo date('d/m/Y', strtotime($warrantyInfo['sale_date'])); ?></p>
                        <p><strong>วันหมดประกัน:</strong>
                            <?php echo date('d/m/Y', strtotime($warrantyInfo['sale_date'] . ' + 3 years')); ?></p>
                    </div>

                    <?php if (strtotime($warrantyInfo['sale_date'] . ' + 3 years') > time()): ?>
                        <div
                            style="margin-top: 1rem; padding: 0.75rem; background: rgba(34, 197, 94, 0.2); color: #86efac; border-radius: 8px; text-align: center;">
                            <i class="fa-solid fa-circle-check"></i> สินค้าอยู่ภายใต้การรับประกัน
                        </div>
                    <?php else: ?>
                        <div
                            style="margin-top: 1rem; padding: 0.75rem; background: rgba(239, 68, 68, 0.2); color: #fca5a5; border-radius: 8px; text-align: center;">
                            <i class="fa-solid fa-circle-xmark"></i> สินค้าหมดระยะรับประกันแล้ว
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                // Check if there's an active RMA for this S/N
                $stmtRma = $pdo->prepare("SELECT * FROM rma_requests WHERE inventory_id = ? ORDER BY created_at DESC LIMIT 1");
                $stmtRma->execute([$warrantyInfo['id']]);
                $rma = $stmtRma->fetch();

                if ($rma):
                    $steps = ['received', 'checking', 'vendor_claim', 'returning', 'done'];
                    $stepLabels = ['รับเรื่องแล้ว', 'กำลังตรวจสอบ', 'ส่งเคลม Vendor', 'กำลังส่งคืน', 'เสร็จสิ้น'];
                    $currentIndex = array_search($rma['status'], $steps);
                    ?>
                    <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">
                    <h4 style="margin-bottom: 1rem;"><i class="fa-solid fa-truck-fast"></i> สถานะการเคลม (RMA)</h4>
                    <div class="status-tracker">
                        <?php foreach ($steps as $index => $step): ?>
                            <div
                                class="status-step <?php echo ($index <= $currentIndex) ? 'active' : ''; ?> <?php echo ($index < $currentIndex) ? 'completed' : ''; ?>">
                                <?php echo $stepLabels[$index]; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem;">
                        <strong>เหตุผลการเคลม:</strong> <?php echo htmlspecialchars($rma['reason']); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="footer-link">
            <a href="index.php" style="color: var(--text-muted);"><i class="fa-solid fa-house"></i> กลับสู่หน้าหลัก</a>
        </div>
    </div>
</body>

</html>