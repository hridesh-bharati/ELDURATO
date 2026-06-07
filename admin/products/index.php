<?php
require_once '../../config/database.php';
require_once '../../config/cloudinary.php'; 

// हेडर और नेवबार को शामिल करें
include '../../includes/header.php';
include '../../includes/navbar.php';

// Delete Logic
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // ✅ टेबल नाम फ़िक्स: 'products' की जगह 'all_products_list' किया गया
    $stmt = $pdo->prepare("DELETE FROM all_products_list WHERE id=?");
    $stmt->execute([$id]);
    
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// ✅ टेबल नाम फ़िक्स: 'products' की जगह 'all_products_list' से सभी प्रोडक्ट्स निकालना
$products = $pdo->query("SELECT * FROM all_products_list ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <a href="../index.php" class="btn btn-sm btn-outline-secondary me-2 rounded-1">
                <i class="bi bi-arrow-left"></i>
            </a> 
            Products Catalog
        </h3>
        <a href="add.php" class="btn btn-success rounded-1 fw-semibold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add New Product
        </a>
    </div>

    <div class="card border-0 shadow-sm p-3 bg-white rounded-1">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Brand</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-end" style="width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $p): 
                            $rawImages = !empty($p['images']) ? $p['images'] : (!empty($p['image']) ? $p['image'] : '');
                            
                            $imagesArray = json_decode($rawImages, true);
                            
                            if (json_last_error() === JSON_ERROR_NONE && is_array($imagesArray)) {
                                if (isset($imagesArray[0]['url'])) {
                                    $displayImage = $imagesArray[0]['url'];
                                } else {
                                    $displayImage = !empty($imagesArray) ? $imagesArray[0] : 'https://via.placeholder.com/50x50?text=No+Img';
                                }
                            } else {
                                $displayImage = !empty($rawImages) ? $rawImages : 'https://via.placeholder.com/50x50?text=No+Img';
                            }
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $displayImage ?>" width="55" height="55" class="rounded border p-1 bg-white" style="object-fit: contain;" alt="product_img" onerror="this.src='https://via.placeholder.com/50x50?text=No+Img';">
                            </td>
                            <td><span class="badge bg-secondary text-uppercase rounded-1" style="font-size: 0.7rem;"><?=htmlspecialchars($p['brand'] ?? 'ELDURATO') ?></span></td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 280px; font-size: 0.9rem;">
                                    <?= htmlspecialchars($p['name']) ?>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Color: <?= htmlspecialchars($p['color'] ?? 'Black') ?></small>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">₹<?= number_format($p['price']) ?></span>
                                <?php if(($p['old_price'] ?? 0) > $p['price']): ?>
                                    <div class="text-muted text-decoration-line-through small" style="font-size: 0.75rem;">₹<?= number_format($p['old_price']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(isset($p['stock']) && $p['stock'] > 0): ?>
                                    <span class="text-success fw-medium" style="font-size: 0.85rem;"><i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i> <?= $p['stock'] ?> pcs</span>
                                <?php else: ?>
                                    <span class="text-danger fw-bold" style="font-size: 0.85rem;"><i class="bi bi-x-circle-fill" style="font-size: 0.75rem;"></i> Out of Stock</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary rounded-1 me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="index.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger rounded-1" onclick="return confirm('Pakka delete karna hai?')" title="Delete">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">
                                <i class="bi bi-folder-x d-block mb-2 text-secondary" style="font-size: 1.8rem;"></i> No products found in your database.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>