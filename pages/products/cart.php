<?php
session_start();
require_once '../../config/database.php';

// ==========================================
// 🚀 1. डेटा प्रोसेसिंग (POST / AJAX Requests) - बिना किसी HTML आउटपुट के
// ==========================================

// AJAX के जरिए क्वांटिटी अपडेट करने का लॉजिक (Flipkart Style)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_quantity') {
    $cart_key = $_POST['cart_key'];
    $new_qty = intval($_POST['quantity']);

    if (isset($_SESSION['cart'][$cart_key]) && $new_qty > 0) {
        $_SESSION['cart'][$cart_key]['quantity'] = $new_qty;
        
        // नया ग्रैंड टोटल और टोटल आइटम्स काउंट कैलकुलेट करें
        $cart_items = $_SESSION['cart'];
        $product_ids = array_unique(array_column($cart_items, 'product_id'));
        
        $grandTotal = 0;
        $total_items_count = 0;
        $db_prices = [];

        if (!empty($product_ids)) {
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $stmt = $pdo->prepare("SELECT id, price FROM all_products_list WHERE id IN ($placeholders)");
            $stmt->execute($product_ids);
            $db_prices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [id => price] की एरे देगा

            foreach ($cart_items as $item) {
                $p_id = $item['product_id'];
                if (isset($db_prices[$p_id])) {
                    $grandTotal += (float)$db_prices[$p_id] * $item['quantity'];
                }
                $total_items_count += $item['quantity']; // कुल पीस की संख्या
            }
        }

        $current_product_id = $_SESSION['cart'][$cart_key]['product_id'];
        $item_unit_price = isset($db_prices[$current_product_id]) ? (float)$db_prices[$current_product_id] : 0;

        // JSON रिस्पॉन्स भेजें
        header('Content-Type: application/json');
       echo json_encode([
    'status' => 'success',
    'item_total' => '₹' . number_format($item_unit_price * $new_qty),
    'grand_total' => '₹' . number_format($grandTotal),
    'total_unique_items' => count($cart_items)
]);
        exit;
    }
    
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error']);
    exit;
}

// कार्ट में आइटम जोड़ने का लॉजिक
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_to_cart']) || isset($_POST['buy_now']))) {
    $product_id = intval($_POST['product_id']);
    if ($product_id <= 0) {
    die("Invalid Product");
}
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $size = isset($_POST['size']) && !empty($_POST['size']) ? trim($_POST['size']) : '32';
    $custom_image = isset($_POST['selected_image']) ? trim($_POST['selected_image']) : '';

    if ($product_id > 0) {
        $cart_key = $product_id . '_' . str_replace(' ', '_', $size);

        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'size' => $size,
                'custom_image' => $custom_image
            ];
        }
    }

    if (isset($_POST['buy_now'])) {
        header("Location: /belt/pages/products/checkout.php");
    } else {
        header("Location: /belt/pages/products/cart.php");
    }
    exit;
}

// कार्ट से आइटम डिलीट करने का लॉजिक
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $cart_key = $_POST['cart_key'];
    if (isset($_SESSION['cart'][$cart_key])) {
        unset($_SESSION['cart'][$cart_key]);
    }
    header("Location: /belt/pages/products/cart.php");
    exit;
}

$cart_items = $_SESSION['cart'] ?? [];

// ==========================================
// 🎨 2. व्यू/लेआउट लोडिंग (HTML और थीम्स)
// ==========================================
include '../../includes/header.php';
include '../../includes/navbar.php';

// अगर कार्ट खाली है तो सुंदर सा मैसेज दिखाओ
if (empty($cart_items)) {
    ?>
    <div class="container py-5 text-center">
        <div class="card p-5 border-0 shadow-sm bg-white mx-auto" style="max-width: 500px; border-radius: 8px;">
            <i class="ri-shopping-bag-2-line text-muted mb-3" style="font-size: 4rem;"></i>
            <h5 class="fw-bold text-dark">आपका कार्ट खाली है!</h5>
            <p class="text-muted small">ट्रेडिंग लेदर बेल्ट्स देखने के लिए नीचे बटन पर क्लिक करें।</p>
            <a href="<?php echo url('pages/products/products.php'); ?>" class="btn btn-primary px-4 py-2 text-uppercase fw-bold shadow-sm" style="background: #2874f0; border: none; border-radius: 4px; font-size: 0.85rem;">
                Shop Now
            </a>
        </div>
    </div>
    <?php
    include '../../includes/footer.php';
    exit;
}

$product_ids = [];

foreach ($cart_items as $item) {
    if (!empty($item['product_id'])) {
        $product_ids[] = (int)$item['product_id'];
    }
}

$product_ids = array_unique($product_ids);

$all_products = [];

if (!empty($product_ids)) {

    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $pdo->prepare(
        "SELECT * FROM all_products_list WHERE id IN ($placeholders)"
    );

    $stmt->execute(array_values($product_ids));

    $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$db_products = [];
foreach ($all_products as $row) {
    $db_products[$row['id']] = $row;
}

// ग्रैंड टोटल कैलकुलेट करना
$grandTotal = 0;
foreach ($cart_items as $item) {
    $p_id = $item['product_id'];
    if (isset($db_products[$p_id])) {
        $grandTotal += (float)$db_products[$p_id]['price'] * $item['quantity'];
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="container py-4">
    <div class="mb-3 px-2 border-bottom pb-2">
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.2rem; letter-spacing: -0.3px;">
            Shopping Cart (<span id="cart-header-count"><?php echo count($cart_items); ?></span> Items)
        </h5>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 bg-white" style="border-radius: 6px;">
                <div class="card-body p-0">
                    <?php 
                    foreach ($cart_items as $key => $item): 
                        $p_id = $item['product_id'];
                        if (!isset($db_products[$p_id])) continue;
                        $product = $db_products[$p_id];

                        // वेरिएंट इमेज हैंडलिंग
                        $itemImage = !empty($item['custom_image']) ? $item['custom_image'] : '';
                        if (empty($itemImage)) {
                            $imagesArray = !empty($product['images']) ? json_decode($product['images'], true) : [];
                            $itemImage = 'https://via.placeholder.com/150x150?text=No+Image';
                            if (!empty($imagesArray) && isset($imagesArray[0])) {
                                $itemImage = is_array($imagesArray[0]) ? ($imagesArray[0]['url'] ?? $itemImage) : $imagesArray[0];
                            }
                        }
                    ?>
                        <div class="d-flex p-3 border-bottom position-relative align-items-center gap-3 cart-item-row" data-key="<?php echo $key; ?>">
                            <div style="width: 85px; height: 85px; flex-shrink: 0;" class="border rounded bg-light p-1">
                                <img src="<?php echo $itemImage; ?>" class="w-100 h-100 object-fit-contain" alt="Product">
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <span class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;"><?php echo htmlspecialchars($product['brand']); ?></span>
                                <h6 class="text-dark mb-1 text-truncate fw-normal" style="font-size: 0.9rem;"><?php echo htmlspecialchars($product['name']); ?></h6>
                                
                                <div class="d-flex align-items-center gap-2 mb-2" style="font-size: 0.8rem;">
                                    <span class="badge bg-light text-dark border px-2 py-1">Size: <strong><?php echo htmlspecialchars($item['size']); ?></strong></span>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="input-group input-group-sm" style="width: 110px;">
                                        <button class="btn btn-outline-secondary btn-minus p-0 d-flex align-items-center justify-content-center" type="button" style="width: 28px; height: 28px; font-weight: bold;">-</button>
                                        <input type="text" class="form-control text-center qty-input p-0" value="<?php echo $item['quantity']; ?>" readonly style="height: 28px; font-size: 0.85rem; font-weight: 600; background-color: #fff;">
                                        <button class="btn btn-outline-secondary btn-plus p-0 d-flex align-items-center justify-content-center" type="button" style="width: 28px; height: 28px; font-weight: bold;">+</button>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-dark item-total-price" style="font-size: 1.05rem;">₹<?php echo number_format($product['price'] * $item['quantity']); ?></span>
                                    <?php if (($product['old_price'] ?? 0) > 0): ?>
                                        <span class="text-muted text-decoration-line-through small" style="font-size: 0.8rem;">₹<?php echo number_format($product['old_price'] * $item['quantity']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <form action="" method="POST" onsubmit="return confirm('क्या आप इस आइटम को कार्ट से हटाना चाहते हैं?');">
                                    <input type="hidden" name="cart_key" value="<?php echo $key; ?>">
                                    <button type="submit" name="remove_item" class="btn btn-light btn-sm text-danger rounded-circle p-0 d-flex align-items-center justify-content-center border shadow-sm" style="width: 32px; height: 32px;" title="Remove Item">
                                        <i class="ri-delete-bin-6-line" style="font-size: 0.95rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-white" style="position: sticky; top: 20px; border-radius: 4px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Price Details</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Price (<span id="summary-item-count"><?php echo count($cart_items); ?></span> Items)</span>
                        <span class="grand-total-display">₹<?php echo number_format($grandTotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Delivery Charges</span>
                        <span class="text-success fw-medium">FREE</span>
                    </div>
                    <hr class="my-2 border-secondary opacity-20">
                    <div class="d-flex justify-content-between fw-bold text-dark fs-5 mb-3">
                        <span style="font-size: 1rem;">Total Amount</span>
                        <span style="font-size: 1.15rem;" class="grand-total-display">₹<?php echo number_format($grandTotal); ?></span>
                    </div>

                    <a href="<?php echo url('pages/products/checkout.php'); ?>" class="btn btn-warning w-100 py-2.5 text-uppercase fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #fb641b; border: none; font-size: 0.95rem; letter-spacing: 0.3px; border-radius: 4px;">
                        <i class="ri-secure-payment-line" style="font-size: 1.1rem;"></i> Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // प्लस (+) बटन ट्रिगर
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', function() {
            let row = this.closest('.cart-item-row');
            let input = row.querySelector('.qty-input');
            let currentQty = parseInt(input.value);
            
            // अधिकतम सीमा प्रति प्रोडक्ट (उदा. 10 आइटम)
            if (currentQty < 10) { 
                let newQty = currentQty + 1;
                input.value = newQty;
                updateCartQuantity(row.dataset.key, newQty, row);
            }
        });
    });

    // माइनस (-) बटन ट्रिगर
    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', function() {
            let row = this.closest('.cart-item-row');
            let input = row.querySelector('.qty-input');
            let currentQty = parseInt(input.value);
            
            if (currentQty > 1) { // 1 से नीचे नहीं जाने देगा
                let newQty = currentQty - 1;
                input.value = newQty;
                updateCartQuantity(row.dataset.key, newQty, row);
            }
        });
    });

    // बैकएंड फ़ेच एपीआई (Fetch API) फंक्शन
    function updateCartQuantity(cartKey, quantity, row) {
        // डबल क्लिक प्रोटेक्शन (बटन डिसेबल करें)
        row.querySelectorAll('button').forEach(btn => btn.disabled = true);

        let formData = new FormData();
        formData.append('action', 'update_quantity');
        formData.append('cart_key', cartKey);
        formData.append('quantity', quantity);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // 1. रो का टोटल अपडेट करें
                row.querySelector('.item-total-price').innerText = data.item_total;
                
                // 2. कुल ग्रैंड टोटल डिस्प्ले अपडेट करें (सभी जगह)
                document.querySelectorAll('.grand-total-display').forEach(el => {
                    el.innerText = data.grand_total;
                });

                // 3. टॉप हेडर और समरी बॉक्स में रो संख्या बनाए रखें 
                document.getElementById('cart-header-count').innerText = data.total_unique_items;
                document.getElementById('summary-item-count').innerText = data.total_unique_items;

   let navCartBadge = document.getElementById('nav-cart-badge');
if(navCartBadge){
    navCartBadge.innerText = data.total_unique_items;
}

let mobileCartBadge = document.getElementById('mobile-cart-badge');
if(mobileCartBadge){
    mobileCartBadge.innerText = data.total_unique_items;
}
            } else {
                alert('क्वांटिटी अपडेट करने में त्रुटि हुई। कृपया पेज रिफ्रेश करें।');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // प्रोसेस पूरा होने पर बटन री-इनेबल करें
            row.querySelectorAll('button').forEach(btn => btn.disabled = false);
        });
    }
});
</script>