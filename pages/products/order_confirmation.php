<!-- pages\products\order_confirmation.php -->
<?php
session_start();
require_once '../../config/database.php';

if (!isset($_POST['place_order_btn']) || empty($_SESSION['cart'])) {
    header("Location: /belt/pages/products/cart.php");
    exit;
}

$customer_name    = trim($_POST['customer_name']);
$customer_phone   = trim($_POST['customer_phone']);
$shipping_address = trim($_POST['shipping_address']);
$city             = trim($_POST['city']);
$pincode          = trim($_POST['pincode']);
$payment_method   = trim($_POST['payment_method']);

$product_ids = array_unique(array_column($_SESSION['cart'], 'product_id'));
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM all_products_list WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$db_products = [];
foreach ($all_products as $row) {
    $db_products[$row['id']] = $row;
}

$grandTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $p_id = $item['product_id'];
    if (isset($db_products[$p_id])) {
        $grandTotal += (float)$db_products[$p_id]['price'] * $item['quantity'];
    }
}

try {
    $pdo->beginTransaction();

    $order_query = "INSERT INTO all_orders_list (customer_name, customer_phone, shipping_address, city, pincode, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $order_stmt = $pdo->prepare($order_query);
    $order_stmt->execute([$customer_name, $customer_phone, $shipping_address, $city, $pincode, $grandTotal, $payment_method]);
    
    $order_id = $pdo->lastInsertId();

    $item_query = "INSERT INTO order_items (order_id, product_id, quantity, size, price) VALUES (?, ?, ?, ?, ?)";
    $item_stmt = $pdo->prepare($item_query);

    foreach ($_SESSION['cart'] as $item) {
        $p_id = $item['product_id'];
        if (isset($db_products[$p_id])) {
            $item_stmt->execute([
                $order_id,
                $p_id,
                $item['quantity'],
                $item['size'],
                $db_products[$p_id]['price']
            ]);
        }
    }

    $pdo->commit();
    unset($_SESSION['cart']);

} catch (Exception $e) {
    $pdo->rollBack();
    die("Order processing failed: " . $e->getMessage());
}

include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .shopsy-green { color: #008c45; }
    .bg-shopsy-light { background-color: #f0f9f4; }
    .pulse-animation { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }
</style>

<div class="container py-4 products-container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden text-center p-4 bg-white mb-3">
                
                <div class="my-3">
                    <div class="bg-shopsy-light d-inline-flex align-items-center justify-content-center rounded-circle p-3 pulse-animation" style="width: 90px; height: 90px;">
                        <i class="ri-checkbox-circle-fill shopsy-green" style="font-size: 3.5rem;"></i>
                    </div>
                </div>

                <h3 class="fw-bold text-dark mb-1">Order Confirmed!</h3>
                <p class="text-success small fw-semibold mb-3">YAY! Your order has been placed successfully.</p>
                
                <hr class="text-muted opacity-25 my-3">

                <div class="text-start bg-light p-3 rounded-3 mb-4" style="font-size: 13px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Order ID</span>
                        <span class="fw-bold text-dark">#ORD-<?php echo $order_id; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Delivery to</span>
                        <span class="fw-medium text-dark text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($customer_name); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Payment Method</span>
                        <span class="fw-medium text-dark text-uppercase"><?php echo htmlspecialchars($payment_method); ?></span>
                    </div>
                    <hr class="my-2 opacity-25">
                    <div class="d-flex justify-content-between align-items-center pt-1">
                        <span class="fw-bold text-secondary">Total Amount</span>
                        <span class="fw-bold text-dark fs-5">₹<?php echo number_format($grandTotal); ?></span>
                    </div>
                </div>
                

                <div class="d-grid">
                    <a href="/belt/pages/products/products.php" class="btn btn-lg py-2.5 fw-bold text-white shadow-sm border-0" style="background: linear-gradient(90deg, #ff4500, #ff007f); font-size: 14px; border-radius: 8px; letter-spacing: 0.5px;">
                        CONTINUE SHOPPING
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-3 rounded-3 text-center bg-shopsy-light border-start border-success border-3">
                <p class="mb-0 text-dark fw-medium" style="font-size: 12px;">
                    <i class="ri-truck-line me-1 shopsy-green"></i> Delivery updates will be sent to <strong><?php echo htmlspecialchars($customer_phone); ?></strong>
                </p>
            </div>
            
        </div>
    </div>
</div>

<script>
    // लाइव कन्फेट्टी सेलिब्रेशन इफ़ेक्ट
    document.addEventListener("DOMContentLoaded", () => {
        var duration = 2 * 1000;
        var end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 4,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#008c45', '#ff4500', '#ff007f', '#ffc107']
            });
            confetti({
                particleCount: 4,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#008c45', '#ff4500', '#ff007f', '#ffc107']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    });
</script>

<?php include '../../includes/footer.php'; ?>