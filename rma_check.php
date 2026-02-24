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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Warranty Check - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="display: flex; flex-direction: column; align-items: center; padding: 4rem;">
    <div class="login-container" style="max-width: 600px;">
        <i class="fa-solid fa-shield-halved logo-icon"></i>
        <h2>Warranty & Support</h2>
        <p class="subtitle">Check your product warranty status using Serial Number (S/N)</p>

        <form action="" method="GET" style="margin-bottom: 2rem;">
            <div class="form-group">
                <label>Serial Number (S/N)</label>
                <input type="text" name="sn" placeholder="e.g. SN-GPU-4070-001"
                    value="<?php echo htmlspecialchars($_GET['sn'] ?? ''); ?>" required>
            </div>
            <button type="submit">Check Status</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($warrantyInfo): ?>
            <div class="warranty-result"
                style="text-align: left; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 0.5rem; border: 1px solid var(--primary-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Product Found</h3>
                <p><strong>Product:</strong>
                    <?php echo $warrantyInfo['product_name']; ?>
                </p>
                <p><strong>S/N:</strong>
                    <?php echo $warrantyInfo['serial_number']; ?>
                </p>
                <p><strong>Status:</strong> <span style="text-transform: uppercase; font-weight: bold;">
                        <?php echo $warrantyInfo['status']; ?>
                    </span></p>

                <?php if ($warrantyInfo['sale_date']): ?>
                    <p><strong>Purchase Date:</strong>
                        <?php echo date('d M Y', strtotime($warrantyInfo['sale_date'])); ?>
                    </p>
                    <p><strong>Warranty Expired:</strong>
                        <?php echo date('d M Y', strtotime($warrantyInfo['sale_date'] . ' + 3 years')); ?>
                    </p>
                    <div
                        style="margin-top: 1rem; padding: 0.5rem; background: rgba(34, 197, 94, 0.2); color: #86efac; border-radius: 4px; text-align: center;">
                        <i class="fa-solid fa-circle-check"></i> In Warranty
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-muted); margin-top: 1rem;">This item is in stock and has not been sold yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="footer-link">
            <a href="index.php"><i class="fa-solid fa-house"></i> Back to Home</a>
        </div>
    </div>
</body>

</html>