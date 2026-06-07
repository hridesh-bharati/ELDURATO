<?php
session_start();
require_once '../../config/database.php';

// अगर कार्ट खाली है तो वापस कार्ट पेज पर भेजें
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$product_ids = array_values(array_unique(array_column($_SESSION['cart'], 'product_id')));
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

include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="container py-5">
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 4px;">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="ri-map-pin-line"></i> Delivery Address & Details</h5>
                    <button type="button" id="btn-location" class="btn btn-light btn-sm fw-bold text-primary d-flex align-items-center gap-1">
                        <i class="ri-navigation-fill text-danger"></i> Use Current Location
                    </button>
                </div>
                <div class="card-body p-4">
                    
                    <div id="location-status" class="alert alert-info py-2 d-none" style="font-size: 0.85rem;">
                        <span class="spinner-border spinner-border-sm me-2"></span> Fetching your live location...
                    </div>

                    <form action="<?php echo url('pages/products/order_confirmation.php'); ?>" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Full Name</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Mobile Number</label>
                                <input type="tel" name="customer_phone" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-secondary">Flat, House no., Apartment Details</label>
                                <input type="text" id="shipping_address" name="shipping_address" class="form-control" placeholder="Address details..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Town/City</label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="e.g. Delhi" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Pincode</label>
                                <input type="text" id="pincode" name="pincode" class="form-control" placeholder="6-digit pincode" pattern="[0-9]{6}" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3 text-dark"><i class="ri-bank-card-line"></i> Payment Method</h5>
                        <div class="form-check p-3 border rounded mb-3 bg-light">
                            <input class="form-check-input ms-0 me-2" type="radio" name="payment_method" id="cod" value="COD" checked>
                            <label class="form-check-label fw-bold" for="cod">Cash on Delivery (COD)</label>
                            <div class="text-muted small ms-4">Pay with cash when your order is delivered.</div>
                        </div>

                        <button type="submit" name="place_order_btn" class="btn btn-warning w-100 py-3 text-uppercase fw-bold text-white shadow-sm mt-3" style="background: #fb641b; border: none; font-size: 1.1rem;">
                            Confirm & Book Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0" style="position: sticky; top: 20px; border-radius: 4px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 text-muted fw-bold text-uppercase">Order Summary</h6>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom bg-light">
                        <?php 
                        foreach($_SESSION['cart'] as $item): 
                            $p_id = $item['product_id'];
                            if(!isset($db_products[$p_id])) continue;
                            $product = $db_products[$p_id];
                        ?>
                            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.9rem;">
                                <span class="text-truncate" style="max-width: 200px;">
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong> 
                                    <span class="text-muted">(Size: <?php echo $item['size']; ?>)</span>
                                </span>
                                <span class="text-secondary">Qty: <?php echo $item['quantity']; ?></span>
                                <span class="fw-bold">₹<?php echo number_format($product['price'] * $item['quantity']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Price Details</span>
                            <span>₹<?php echo number_format($grandTotal); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Delivery Charges</span>
                            <span class="text-success">FREE</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold text-dark fs-5">
                            <span>Total Amount</span>
                            <span>₹<?php echo number_format($grandTotal); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#btn-location').click(function() {
        if (navigator.geolocation) {
            $('#location-status').removeClass('d-none').html('<span class="spinner-border spinner-border-sm me-2"></span> Accessing GPS...');
            navigator.geolocation.getCurrentPosition(showPosition, showError, { enableHighAccuracy: true, timeout: 10000 });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        $('#location-status').html('<span class="spinner-border spinner-border-sm me-2"></span> Resolving Address...');

        $.ajax({
            url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`,
            type: 'GET',
            dataType: 'json',
            headers: { 'Accept-Language': 'en' },
            success: function(data) {
                $('#location-status').addClass('d-none');
                if (data && data.address) {
                    var addr = data.address;
                    var fullAddress = [addr.road || addr.suburb || '', addr.neighbourhood || addr.village || '', addr.county || ''].filter(Boolean).join(', ');
                    var cityName = addr.city || addr.town || addr.state_district || '';
                    var pincode = addr.postcode || '';

                    $('#shipping_address').val(data.display_name || fullAddress);
                    $('#city').val(cityName);
                    if(pincode) $('#pincode').val(pincode.replace(/\s/g, ''));
                }
            },
            error: function() { $('#location-status').addClass('d-none'); }
        });
    }

    function showError(error) { $('#location-status').addClass('d-none'); alert("Location retrieval failed."); }
});
</script>

<?php include '../../includes/footer.php'; ?>