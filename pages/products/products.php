<!-- pages\products\products.php -->
<?php
require_once __DIR__ . '/../../config/database.php';
$isIncluded = defined('INCLUDED_IN_HERO') || basename($_SERVER['SCRIPT_FILENAME']) !== 'products.php';
if (!$isIncluded) {
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar.php';
}
if (!function_exists('url')) {
    function url($path) {
        return '/belt/' . ltrim($path, '/');
    }
}

try {
    $colQuery = $pdo->query("SHOW COLUMNS FROM all_products_list");
    $columns = $colQuery->fetchAll(PDO::FETCH_COLUMN);
    $hasCategory = in_array('category', $columns);
    $hasBrand = in_array('brand', $columns);
    $hasRating = in_array('rating', $columns);
} catch (PDOException $e) {
    $hasCategory = $hasBrand = $hasRating = false;
}

$allCategories = $hasCategory ? $pdo->query("SELECT DISTINCT category FROM all_products_list WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN) : [];
$allBrands = $hasBrand ? $pdo->query("SELECT DISTINCT brand FROM all_products_list WHERE brand IS NOT NULL AND brand != '' ORDER BY brand")->fetchAll(PDO::FETCH_COLUMN) : [];

$stmt = $pdo->query("SELECT * FROM all_products_list ORDER BY id DESC");
$dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$jsProducts = [];
foreach ($dbProducts as $product) {
    $price = (int)$product['price'];
    $oldPrice = isset($product['old_price']) ? (int)$product['old_price'] : 0;
    $discount = ($oldPrice > 0 && $oldPrice > $price) ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
    
    $imagesArray = !empty($product['images']) ? json_decode($product['images'], true) : [];
    $firstImage = 'https://via.placeholder.com/300x300?text=No+Image';
    if (!empty($imagesArray) && isset($imagesArray[0])) {
        $firstImage = is_array($imagesArray[0]) ? ($imagesArray[0]['url'] ?? $firstImage) : $imagesArray[0];
    }

    $jsProducts[] = [
        'id' => (int)$product['id'],
        'name' => trim($product['name']),
        'brand' => isset($product['brand']) ? trim($product['brand']) : 'Premium Collection',
        'category' => isset($product['category']) ? trim($product['category']) : '',
        'price' => $price,
        'old_price' => $oldPrice,
        'discount' => $discount,
        'rating' => isset($product['rating']) ? (float)$product['rating'] : 0,
        'image' => $firstImage,
        'details_url' => url('pages/products/product-details.php?id=' . $product['id'])
    ];
}
?>

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .cursor-pointer { cursor: pointer; }
    .hover-shadow:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important; transition: all 0.2s ease; }
</style>

<div class="<?php echo $isIncluded ? 'p-0' : 'container-fluid py-3 bg-light'; ?>">
    <div class="container-lg">
        <div class="row g-3">
            
            <!-- DESKTOP SIDEBAR FILTERS -->
            <div class="col-lg-3 col-md-4 d-none d-md-block">
                <div class="card border-light shadow-sm p-3 sticky-top" style="top: 20px; z-index: 10; border-radius: 12px;">
                    <div class="fw-bold border-bottom pb-2 mb-3 text-dark d-flex align-items-center gap-2">
                        <i class="ri-filter-3-line"></i> Filters
                    </div>
                    <div id="desktopFilterContainer"></div>
                </div>
            </div>

            <!-- RIGHT SIDE: PRODUCT GRID & HEADINGS -->
            <div class="col-lg-9 col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-2 px-3 rounded-3 shadow-sm border">
                    <div class="text-muted small fw-semibold" id="productCount">
                        Showing 0 products
                    </div>
                    
                    <button class="btn btn-sm btn-light border fw-semibold d-md-none px-3 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                        <i class="ri-filter-3-line text-primary"></i> Filter
                    </button>
                </div>
                <div class="row g-2 g-md-3" id="productGrid"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-bottom h-75 rounded-top-4" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header border-bottom py-2">
        <h6 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ri-filter-3-line"></i> Filters
        </h6>
        <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3" id="mobileFilterContainer"></div>
</div>

<div id="masterFormTemplate" class="d-none">
    <form id="filterForm" onsubmit="event.preventDefault();">
        <?php if ($hasCategory && !empty($allCategories)): ?>
            <div class="border-bottom pb-3 mb-3">
                <div class="fw-bold text-secondary small mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Category</div>
                <div class="overflow-y-auto" style="max-height: 140px;">
                    <?php foreach ($allCategories as $cat): ?>
                        <div class="form-check small mb-1">
                            <input class="form-check-input filter-checkbox" type="checkbox" data-type="category" value="<?php echo htmlspecialchars(trim($cat)); ?>" id="cat_<?php echo md5($cat); ?>">
                            <label class="form-check-label text-dark cursor-pointer w-100" for="cat_<?php echo md5($cat); ?>"><?php echo htmlspecialchars($cat); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($hasBrand && !empty($allBrands)): ?>
            <div class="border-bottom pb-3 mb-3">
                <div class="fw-bold text-secondary small mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Brand</div>
                <div class="overflow-y-auto" style="max-height: 140px;">
                    <?php foreach ($allBrands as $brand): ?>
                        <div class="form-check small mb-1">
                            <input class="form-check-input filter-checkbox" type="checkbox" data-type="brand" value="<?php echo htmlspecialchars(trim($brand)); ?>" id="brand_<?php echo md5($brand); ?>">
                            <label class="form-check-label text-dark cursor-pointer w-100" for="brand_<?php echo md5($brand); ?>"><?php echo htmlspecialchars($brand); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="border-bottom pb-3 mb-3">
            <div class="fw-bold text-secondary small mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Price Range</div>
            <div class="d-flex gap-2 align-items-center">
                <input type="number" class="form-control form-control-sm price-input shadow-none" id="minPrice" placeholder="Min">
                <span class="text-muted">-</span>
                <input type="number" class="form-control form-control-sm price-input shadow-none" id="maxPrice" placeholder="Max">
            </div>
        </div>

        <?php if ($hasRating): ?>
            <div class="border-bottom pb-3 mb-3">
                <div class="fw-bold text-secondary small mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Customer Rating</div>
                <div class="form-check small mb-1">
                    <input class="form-check-input rating-radio" type="radio" name="rating" value="4" id="rating4">
                    <label class="form-check-label text-dark cursor-pointer" for="rating4">4★ & above</label>
                </div>
                <div class="form-check small mb-1">
                    <input class="form-check-input rating-radio" type="radio" name="rating" value="3" id="rating3">
                    <label class="form-check-label text-dark cursor-pointer" for="rating3">3★ & above</label>
                </div>
                <div class="form-check small mb-1">
                    <input class="form-check-input rating-radio" type="radio" name="rating" value="any" id="ratingAny" checked>
                    <label class="form-check-label text-dark cursor-pointer" for="ratingAny">Any rating</label>
                </div>
            </div>
        <?php endif; ?>

        <button type="button" class="btn btn-sm btn-outline-danger w-100 py-2 fw-semibold shadow-none" id="btnClearAll" style="font-size: 12px;">
            <i class="ri-close-circle-line"></i> CLEAR ALL FILTERS
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const allProducts = <?php echo json_encode($jsProducts); ?>;
    const cartActionUrl = "<?php echo url('pages/products/cart.php'); ?>";

    function formatMoney(num) {
        return new Intl.NumberFormat('en-IN').format(num);
    }

    function setupFilterFormLocation() {
        const formTemplate = document.getElementById('filterForm');
        if (window.innerWidth < 768) {
            document.getElementById('mobileFilterContainer').appendChild(formTemplate);
        } else {
            document.getElementById('desktopFilterContainer').appendChild(formTemplate);
        }
    }

    function renderProducts(productsList) {
        const grid = document.getElementById('productGrid');
        if (!grid) return;

        document.getElementById('productCount').innerText = `Showing ${productsList.length} products`;

        if (productsList.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm border">
                    <i class="ri-inbox-line display-4 text-muted"></i>
                    <p class="mt-2 text-muted">No products match your filters.</p>
                </div>`;
            return;
        }

        grid.innerHTML = productsList.map(product => {
            const oldPriceHTML = product.old_price > 0 ? `
                <span class="text-muted text-decoration-line-through text-truncate" style="font-size: 11px;">₹${formatMoney(product.old_price)}</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-1" style="font-size: 10px; padding: 2px 4px;">${product.discount}% off</span>
            ` : '';

            return `
                <div class="col-6 col-md-4 col-lg-3 d-flex">
                    <div class="card w-100 border rounded-3 overflow-hidden position-relative shadow-sm hover-shadow" style="border-radius: 10px;">
                        <div class="position-relative bg-light text-center" style="aspect-ratio: 1/1;">
                            <a href="${product.details_url}">
                                <img class="w-100 h-100 object-fit-cover" src="${product.image}" alt="${product.name}">
                            </a>
                           <div class="position-absolute top-0 end-0 m-2">
    <button type="button"
            class="btn btn-light rounded-circle shadow-sm wishlist-btn"
            data-product-id="${product.id}"
            style="width:35px;height:35px;">
        <i class="ri-heart-line text-danger"></i>
    </button>
</div>
                        </div>
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase fw-bold" style="font-size: 9px; letter-spacing:0.3px;">${product.brand}</div>
                                <h6 class="card-title text-dark mb-1 text-truncate-2" style="font-size: 12px; line-height: 1.3; height: 32px;">${product.name}</h6>
                                <div class="d-flex align-items-center gap-1 flex-wrap mt-1">
                                    <span class="fw-bold text-dark" style="font-size:14px;">₹${formatMoney(product.price)}</span>
                                    ${oldPriceHTML}
                                </div>
                            </div>
                            <form action="${cartActionUrl}" method="POST" class="mt-2">
                                <input type="hidden" name="product_id" value="${product.id}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="size" value="32">
                                <button type="submit" name="add_to_cart" class="btn btn-warning w-100 py-1 fw-bold text-white shadow-none" style="background-color: #fb641b; border:none; font-size: 11px; border-radius: 6px;">
                                    <i class="ri-shopping-cart-2-fill"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>`;
        }).join('');
    }

    function applyFilters() {
        const selectedCategories = Array.from(document.querySelectorAll('.filter-checkbox[data-type="category"]:checked')).map(el => el.value.trim());
        const selectedBrands = Array.from(document.querySelectorAll('.filter-checkbox[data-type="brand"]:checked')).map(el => el.value.trim());
        
        const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
        const maxPrice = parseInt(document.getElementById('maxPrice').value) || Infinity;
        
        const selectedRatingRadio = document.querySelector('.rating-radio:checked');
        const ratingValue = selectedRatingRadio ? selectedRatingRadio.value : 'any';

        let filtered = allProducts.filter(product => {
            if (selectedCategories.length > 0 && !selectedCategories.includes(product.category.trim())) return false;
            if (selectedBrands.length > 0 && !selectedBrands.includes(product.brand.trim())) return false;
            if (product.price < minPrice || product.price > maxPrice) return false;
            if (ratingValue !== 'any' && product.rating < parseInt(ratingValue)) return false;
            return true;
        });

        renderProducts(filtered);
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('filter-checkbox') || e.target.classList.contains('rating-radio')) {
            applyFilters();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('price-input')) {
            applyFilters();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('#btnClearAll')) {
            document.getElementById('filterForm').reset();
            applyFilters();
        }
    });

    

    window.addEventListener('resize', setupFilterFormLocation);

    document.addEventListener("DOMContentLoaded", () => {
        const masterForm = document.getElementById('masterFormTemplate').innerHTML;
        document.getElementById('masterFormTemplate').remove();
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = masterForm;
        document.body.appendChild(tempDiv.firstElementChild);

        setupFilterFormLocation();
        applyFilters();
    });

    document.addEventListener('click', async function(e){

    const btn = e.target.closest('.wishlist-btn');

    if(!btn) return;

    const productId = btn.dataset.productId;

    const formData = new FormData();
    formData.append('product_id', productId);

    try{

        const response = await fetch(
            '/belt/pages/products/wishlist.php',
            {
                method:'POST',
                body:formData
            }
        );

        const data = await response.json();

        if(data.success){

            const icon = btn.querySelector('i');

            if(data.action === 'added'){
                icon.className = 'ri-heart-fill text-danger';
            }else{
                icon.className = 'ri-heart-line text-danger';
            }

            updateWishlistCount();

        }else{
            alert(data.message);
        }

    }catch(error){
        console.log(error);
    }

});

</script>
<?php 
if (!$isIncluded) {
    include __DIR__ . '/../../includes/footer.php'; 
}
?>