<?php
session_start();
require_once '../../config/database.php';

// 🛠️ 1. AJAX STATUS UPDATE
if(isset($_POST['ajax_update_status'])) {
    header('Content-Type: application/json');
    $success = $pdo->prepare("UPDATE all_orders_list SET order_status=? WHERE id=?")
                    ->execute([trim($_POST['status']), intval($_POST['order_id'])]);
    echo json_encode(['success' => $success]);
    exit;
}

// 🛠️ 2. FETCH ORDERS WITH PRODUCTS & IMAGES (Optimized Single Query - All Technical Details Added)
$query = "
    SELECT 
        o.id,
        o.customer_name,
        o.customer_phone,
        o.total_amount,
        o.payment_method,
        o.order_status,
        o.created_at,
        COALESCE(u.name, o.customer_name, 'Guest Customer') as customer_real_name,
        oi.size as ordered_size,
        oi.quantity as ordered_qty,
        p.name as product_name,
        p.images as product_images,
        p.brand as product_brand,
        p.color as product_color,
        p.material as product_material,
        p.description as product_desc,
        p.model_name as product_model
    FROM all_orders_list o 
    LEFT JOIN users u ON o.user_id = u.id 
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN all_products_list p ON oi.product_id = p.id
    GROUP BY o.id
    ORDER BY o.id DESC
";

$orders = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$status_map = [
    'pending'    => 'text-warning border-warning',
    'processing' => 'text-info border-info',
    'completed'  => 'text-success border-success',
    'cancelled'  => 'text-danger border-danger'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELDURATO - Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        .product-clickable {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .product-clickable:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        @media (max-width: 768px) {
            .table-responsive table {
                min-width: 850px;
            }
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid container-md py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-3 rounded-3 text-white mb-4 gap-3 bg-gradient" style="background-color: #0d6efd; background-image: linear-gradient(135deg, #0d6efd 0%, #198754 100%);">
        <h5 class="fw-bold m-0 text-center text-md-start"><i class="ri-shield-flash-line"></i> ELDURATO Fulfillment</h5>
        <input type="text" id="orderSearchInput" class="form-control border-0 mx-md-3" style="max-width: 100%;" placeholder="Search Brand ID, Name, Phone, COD...">
        <span class="badge bg-white text-dark py-2 px-3 rounded-pill fw-bold text-center align-self-center align-self-md-auto">Total: <?= count($orders) ?></span>
    </div>

    <div class="card border-0 shadow-sm p-1 p-md-2 rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark small text-uppercase">
                    <tr>
                        <th>Order ID</th>
                        <th>Product Details (Click to view)</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): foreach($orders as $o): 
                        $curr_status = strtolower($o['order_status'] ?? 'pending');
                        $pay_method = strtoupper($o['payment_method'] ?? 'COD');
                        
                        $suffix = ($pay_method == 'COD') ? 'COD' : 'ONL';
                        $custom_order_id = "ELD-" . $suffix . "-" . $o['id'];

                        $prod_img = 'https://via.placeholder.com/60?text=No+Image';
                        if (!empty($o['product_images'])) {
                            $img_json = json_decode($o['product_images'], true);
                            if (is_array($img_json) && isset($img_json[0]['url'])) {
                                $prod_img = $img_json[0]['url'];
                            } elseif (!is_array($img_json)) {
                                $prod_img = $o['product_images'];
                            }
                        }

                        // JavaScript crash ko rokne ke liye single line stripping aur strict quotes conversion
                        $p_name = htmlspecialchars($o['product_name'] ?: 'Canceled Product', ENT_QUOTES);
                        $p_brand = htmlspecialchars($o['product_brand'] ?? 'N/A', ENT_QUOTES);
                        $p_color = htmlspecialchars($o['product_color'] ?? 'N/A', ENT_QUOTES);
                        $p_material = htmlspecialchars($o['product_material'] ?? 'N/A', ENT_QUOTES);
                        $p_model = htmlspecialchars($o['product_model'] ?? 'N/A', ENT_QUOTES);
                        
                        $clean_desc = !empty($o['product_desc']) ? str_replace(["\r", "\n", "'", '"'], [" ", " ", "\\'", '\\"'], $o['product_desc']) : 'No Description Available.';
                        $p_desc = htmlspecialchars($clean_desc, ENT_QUOTES);
                    ?>
                    <tr class="order-row" data-search="<?= strtolower($custom_order_id . ' ' . htmlspecialchars($o['customer_real_name'] . ' ' . ($o['customer_phone'] ?? '') . ' ' . $pay_method . ' ' . ($o['product_name'] ?? ''))) ?>">
                        
                        <td>
                            <div class="fw-bold text-primary"><?= $custom_order_id ?></div>
                            <small class="text-secondary d-block" style="font-size: 11px;"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></small>
                        </td>
                        
                        <td class="product-clickable" 
                            onclick="showProductDetails('<?= $p_name ?>', '<?= htmlspecialchars($prod_img, ENT_QUOTES) ?>', '<?= htmlspecialchars($o['ordered_size'] ?? 'Free') ?>', '<?= intval($o['ordered_qty'] ?? 1) ?>', '<?= $custom_order_id ?>', '<?= $p_brand ?>', '<?= $p_color ?>', '<?= $p_material ?>', '<?= $p_model ?>', '<?= $p_desc ?>')">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= htmlspecialchars($prod_img) ?>" class="rounded border object-fit-contain bg-white" width="55" height="55" alt="product">
                                <div style="max-width: 220px;">
                                    <div class="fw-semibold text-dark text-truncate small mb-0" title="<?= htmlspecialchars($o['product_name'] ?? 'N/A') ?>">
                                        <?= htmlspecialchars($o['product_name'] ?: 'Canceled Product') ?>
                                    </div>
                                    <div class="d-flex gap-2 mt-1" style="font-size: 11px;">
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Size: <?= htmlspecialchars($o['ordered_size'] ?? 'Free') ?></span>
                                        <span class="badge bg-light text-dark border">Qty: <?= intval($o['ordered_qty'] ?? 1) ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <div class="fw-bold text-dark small"><?= htmlspecialchars($o['customer_real_name']) ?></div>
                            <small class="text-muted" style="font-size: 12px;"><i class="ri-phone-line"></i> <?= htmlspecialchars($o['customer_phone'] ?? '') ?></small>
                        </td>
                        
                        <td class="fw-bold text-dark">₹<?= number_format($o['total_amount']) ?></td>
                        
                        <td>
                            <span class="badge rounded-pill px-2 py-1 fw-bold border" style="font-size: 11px; <?= ($pay_method == 'COD') ? 'background-color: #f8f9fa; color: #333;' : 'background-color: #e3f2fd; color: #0d6efd; border-color: #0d6efd !important;' ?>">
                                <?= $pay_method ?>
                            </span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm fw-bold <?= $status_map[$curr_status] ?? 'text-secondary' ?>" onchange="updateOrderStatus(<?= $o['id'] ?>, this)">
                                <?php foreach($status_map as $key => $val): ?>
                                    <option value="<?= $key ?>" <?= $curr_status == $key ? 'selected' : '' ?> class="text-dark bg-white">
                                        <?= ucfirst($key) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        
                        <td class="text-center">
                            <a href="invoice.php?id=<?= $o['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary p-2 rounded-2" title="Print Invoice">
                                <i class="ri-printer-line"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white p-3">
                <h6 class="modal-title fw-bold" id="productDetailsModalLabel"><i class="ri-box-3-line me-1"></i> Product Specifications</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="text-center mb-3">
                    <img id="modalProductImg" src="" class="img-fluid rounded border shadow-sm bg-white object-fit-contain" style="max-height: 180px; min-width: 130px;" alt="Product View">
                    <h5 class="fw-bold text-dark mt-2 mb-1" id="modalProductName">Product Name</h5>
                    <p class="text-muted small mb-2">Order Ref: <span id="modalOrderRef" class="text-primary fw-semibold"></span></p>
                </div>
                
                <div class="row g-2 mb-3 text-center">
                    <div class="col-6">
                        <div class="bg-light p-2 rounded border">
                            <small class="text-muted d-block" style="font-size: 11px;">Ordered Size</small>
                            <span class="fw-bold text-dark" id="modalProductSize">-</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-2 rounded border">
                            <small class="text-muted d-block" style="font-size: 11px;">Quantity</small>
                            <span class="fw-bold text-dark" id="modalProductQty">-</span>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-1 mb-2" style="font-size:13px;">Full Specifications</h6>
                <table class="table table-sm table-bordered mb-0" style="font-size: 12px; border-color: #eee !important;">
                    <tbody>
                        <tr><td class="text-muted py-1.5 px-2" style="width:35%;">Brand</td><td class="text-dark fw-semibold py-1.5 px-2" id="modalProductBrand">-</td></tr>
                        <tr><td class="text-muted py-1.5 px-2">Color</td><td class="text-dark py-1.5 px-2" id="modalProductColor">-</td></tr>
                        <tr><td class="text-muted py-1.5 px-2">Material</td><td class="text-dark py-1.5 px-2" id="modalProductMaterial">-</td></tr>
                        <tr><td class="text-muted py-1.5 px-2">Model Name</td><td class="text-dark py-1.5 px-2" id="modalProductModel">-</td></tr>
                        <tr><td class="text-muted py-1.5 px-2">Description</td><td class="text-dark py-1.5 px-2" style="line-height:1.4;" id="modalProductDesc">-</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light p-2">
                <button type="button" class="btn Pattern btn-secondary w-100 py-2 fw-semibold" data-bs-dismiss="modal">Close Window</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ⚡ लाइव सर्च
    document.getElementById('orderSearchInput').addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.order-row').forEach(r => {
            const text = r.dataset.search || '';
            r.classList.toggle('d-none', !text.includes(q));
        });
    });

    // 🔄 AJAX स्टेटस अपडेट
    function updateOrderStatus(id, el) {
        const formData = new FormData();
        formData.append('ajax_update_status', '1');
        formData.append('order_id', id);
        formData.append('status', el.value);

        fetch('', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Failed to update status!');
            }
        }).catch(() => alert('Network Error!'));
    }

    // 📦 Dynamic Specifications Mapping inside Modal on Click
    function showProductDetails(name, img, size, qty, orderId, brand, color, material, model, desc) {
        document.getElementById('modalProductName').innerText = name;
        document.getElementById('modalProductImg').src = img;
        document.getElementById('modalProductSize').innerText = size;
        document.getElementById('modalProductQty').innerText = qty;
        document.getElementById('modalOrderRef').innerText = orderId;
        
        document.getElementById('modalProductBrand').innerText = brand;
        document.getElementById('modalProductColor').innerText = color;
        document.getElementById('modalProductMaterial').innerText = material;
        document.getElementById('modalProductModel').innerText = model;
        document.getElementById('modalProductDesc').innerHTML = desc;
        
        var myModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
        myModal.show();
    }
</script>
</body>
</html>