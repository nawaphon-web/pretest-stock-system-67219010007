<?php
session_start();
require 'db.php';
require 'includes/OrderService.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Simulated cart data from session or POST
$build = isset($_POST['build_data']) ? json_decode($_POST['build_data'], true) : [];
$assembly = isset($_POST['assembly']) && $_POST['assembly'] === 'build' ? 500 : 0;

$total = $assembly;
foreach ($build as $part) {
    $total += $part['price'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="display: block; overflow: auto; padding: 2rem;">
    <div class="checkout-container"
        style="max-width: 800px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: 1rem; backdrop-filter: blur(10px);">
        <h2><i class="fa-solid fa-cart-shopping"></i> Complete Your Order</h2>

        <div class="order-summary"
            style="margin: 2rem 0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 2rem;">
            <h3>Order Summary</h3>
            <ul style="list-style: none; margin-top: 1rem;">
                <?php foreach ($build as $cat => $part): ?>
                    <li style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span><strong>
                                <?php echo strtoupper($cat); ?>:
                            </strong>
                            <?php echo $part['name']; ?>
                        </span>
                        <span>฿
                            <?php echo number_format($part['price'], 2); ?>
                        </span>
                    </li>
                <?php
endforeach; ?>
                <?php if ($assembly > 0): ?>
                    <li
                        style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--primary-color);">
                        <span>Professional Assembly</span>
                        <span>฿500.00</span>
                    </li>
                <?php
endif; ?>
                <li
                    style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                    <span>Total</span>
                    <span>฿
                        <?php echo number_format($total, 2); ?>
                    </span>
                </li>
            </ul>
        </div>

        <div class="installment-info"
            style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
            <i class="fa-solid fa-credit-card"></i> <strong>0% Installment:</strong> ฿
            <?php echo number_format(OrderService::calculateInstallments($total), 2); ?> x 10 months
        </div>

        <form action="process_order.php" method="POST">
            <input type="hidden" name="build_data" value='<?php echo json_encode($build); ?>'>
            <input type="hidden" name="assembly" value="<?php echo $assembly > 0 ? 'build' : 'box'; ?>">
            <input type="hidden" name="total" value="<?php echo $total; ?>">

            <h3><i class="fa-solid fa-file-invoice"></i> Tax Invoice Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Full Name / Company Name</label>
                    <input type="text" name="tax_name" placeholder="Name on Invoice">
                </div>
                <div class="form-group">
                    <label>Tax ID</label>
                    <input type="text" name="tax_id" placeholder="13 digits">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Address</label>
                    <textarea name="tax_address"
                        style="width: 100%; border-radius: 0.5rem; background: rgba(15,23,42,0.5); color: white; padding: 0.75rem; border: 1px solid rgba(255,255,255,0.2);"
                        rows="3"></textarea>
                </div>
            </div>

            <div style="margin-top: 1rem;">
                <button type="submit" name="action" value="order" class="btn-checkout">Pre Order</button>
                <button type="button" onclick="generateQuotation()" class="btn-back"
                    style="flex: 1; margin-top: 1rem;">Get Quotation (PDF View)</button>
            </div>

            <script>
                function generateQuotation() {
                    const buildData = '<?php echo json_encode($build); ?>';
                    const assembly = '<?php echo $assembly > 0 ? 'build' : 'box'; ?>';
                    const taxName = document.querySelector('[name="tax_name"]').value;
                    const taxId = document.querySelector('[name="tax_id"]').value;
                    const taxAddress = document.querySelector('[name="tax_address"]').value;

                    const params = new URLSearchParams({
                        build_data: buildData,
                        assembly: assembly,
                        tax_name: taxName,
                        tax_id: taxId,
                        tax_address: taxAddress
                    });

                    window.open('quotation_view.php?' + params.toString(), '_blank');
                }
            </script>
            <a href="builder.php"
                style="display: block; text-align: center; margin-top: 1rem; color: var(--text-muted); text-decoration: none;">Back
                to Builder</a>
        </form>
    </div>
</body>

</html>