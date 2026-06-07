<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!function_exists('url')) {
    function url($path) {
        return '/belt/' . ltrim($path, '/');
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM all_products_list WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<div class='container my-5 alert alert-warning rounded-1 border-0 shadow-sm'>The product has been relocated or expired. <a href='".url('pages/products/products.php')."' class='alert-link'>Return to Grid</a></div>";
    include '../../includes/footer.php';
    exit;
}

$imagesData = !empty($product['images']) ? json_decode($product['images'], true) : [];

// पहली इमेज और डिफ़ॉल्ट साइज सेट करना
if (!empty($imagesData) && isset($imagesData[0]['url'])) {
    $firstImgUrl = $imagesData[0]['url'];
    $firstImgSizes = $imagesData[0]['sizes'] ?? '28,30,32,34,36,38,40';
} else {
    $firstImgUrl = !empty($product['images']) && !is_array(json_decode($product['images'])) ? $product['images'] : 'https://via.placeholder.com/500x400?text=No+Image';
    $firstImgSizes = '28,30,32,34,36,38,40';
}

$discount = ($product['old_price'] > 0) ? round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) : 0;
?>

<style>
    body { background-color: #f1f3f6 !important; }
    .app-section-card { background: #fff; padding: 14px; margin-bottom: 8px; border-bottom: 1px solid #eaeaea; }
    .size-chip { display: inline-block; padding: 5px 12px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; font-weight: 600; color: #212121; }
    .size-chip.active-size { border-color: #2874f0 !important; color: #2874f0 !important; background-color: #fff !important; }
    .app-sticky-bottom { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; padding: 8px 12px; box-shadow: 0 -3px 10px rgba(0,0,0,0.08); z-index: 1040; }
    
    /* Carousel Styling */
    .carousel-item img { height: 380px; object-fit: contain; width: 100%; }
    .thumb-box { width: 55px; height: 55px; cursor: pointer; transition: 0.2s; border: 1px solid #ddd; }
    .thumb-box.active-thumb { border-color: #2874f0 !important; border-width: 2px !important; }

    @media (min-width: 768px) {
        .app-details-container { max-width: 1100px; margin: 20px auto; }
        .app-section-card { border: 1px solid #eeeeee; border-radius: 6px; }
        .app-sticky-bottom { position: static !important; padding: 0 !important; box-shadow: none !important; margin-top: 20px; }
    }
</style>

<div class="container-fluid app-details-container p-0 p-md-2">
    <div class="row g-2 g-md-3 flex-nowrap">
        
        <div class="col-5">
            <div class="position-sticky" style="top: 20px; background: #fff; border-radius: 6px; overflow: hidden;">
                
                <div id="productImagesCarousel" class="carousel slide p-2" data-bs-ride="false" data-bs-interval="false">
                    <div class="carousel-inner">
                        <?php if(!empty($imagesData) && is_array($imagesData)): ?>
                            <?php foreach($imagesData as $index => $imgItem): 
                                $imgUrl = isset($imgItem['url']) ? $imgItem['url'] : $imgItem;
                                $imgSizes = isset($imgItem['sizes']) ? $imgItem['sizes'] : '28,30,32,34,36,38,40';
                            ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-imgurl="<?= $imgUrl ?>" data-sizes="<?= htmlspecialchars($imgSizes) ?>">
                                    <img src="<?= $imgUrl; ?>" class="d-block img-fluid w-100" style="object-fit: contain; max-height: 400px;">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active" data-imgurl="<?= $firstImgUrl ?>" data-sizes="<?= $firstImgSizes ?>">
                                <img src="<?= $firstImgUrl; ?>" class="d-block img-fluid w-100" style="object-fit: contain; max-height: 400px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button class="carousel-control-prev" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="prev" style="filter: invert(1);">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="next" style="filter: invert(1);">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                
                <?php if(!empty($imagesData) && is_array($imagesData) && count($imagesData) > 1): ?>
                    <div class="p-2 d-flex justify-content-center gap-2 overflow-auto border-top">
                        <?php foreach($imagesData as $index => $imgItem): 
                            $imgUrl = isset($imgItem['url']) ? $imgItem['url'] : $imgItem;
                        ?>
                            <div class="thumb-box rounded bg-white p-1 <?= $index === 0 ? 'active-thumb' : '' ?>" data-bs-target="#productImagesCarousel" data-bs-slide-to="<?= $index ?>" style="width: 45px; height: 45px; flex-shrink: 0;">
                                <img src="<?= $imgUrl; ?>" class="w-100 h-100" style="object-fit: contain;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-7">
            <div class="app-section-card p-2 p-md-3 bg-white mb-2 rounded shadow-sm">
                <span class="text-uppercase text-muted fw-bold small" style="font-size:0.75rem; display: block;"><?= htmlspecialchars($product['brand']) ?></span>
                <h5 class="fw-normal text-dark mb-2" style="font-size: 1.15rem; line-height: 1.4;"><?= htmlspecialchars($product['name']) ?></h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success py-1 px-1.5 rounded" style="font-size:0.7rem;"><?= $product['rating'] ?? '4.1' ?> ★</span>
                    <span class="text-muted small">(<?= number_format($product['total_reviews'] ?? 142) ?> Ratings)</span>
                </div>
            </div>

            <div class="app-section-card p-2 p-md-3 bg-white mb-2 rounded shadow-sm">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-bold text-dark" style="font-size:1.6rem;">₹<?= number_format($product['price']) ?></span>
                    <?php if ($product['old_price'] > 0): ?>
                        <span class="text-muted text-decoration-line-through small" style="font-size:0.9rem;">₹<?= number_format($product['old_price']) ?></span>
                        <span class="fw-bold px-2 py-0.5 rounded" style="font-size: 0.75rem; color: #148a56; background: linear-gradient(to right, #ffffff, #e6f7ed); border: 1px solid #cceedd;">
                            <?= $discount ?>% Off
                        </span>
                    <?php endif; ?>
                </div>
                <small class="text-muted d-block mt-1" style="font-size:11px;">Inclusive of all taxes</small>
            </div>

            <div class="app-section-card p-2 p-md-3 bg-white mb-2 rounded shadow-sm">
                <span class="fw-bold d-block text-dark small mb-2">Available Waist Sizes:</span>
                <div id="dynamicSizesRow" class="d-flex gap-2 flex-wrap"></div>
            </div>

            <div class="app-section-card p-2 p-md-3 bg-white mb-2 rounded shadow-sm">
                <h6 class="fw-bold text-dark border-bottom pb-1 mb-2" style="font-size:0.9rem;">Product Highlights</h6>
                <div class="row g-2 py-1 text-dark small" style="font-size: 13px;">
                    <div class="col-6"><span class="text-muted d-block" style="font-size:0.75rem;">Color</span><strong><?= htmlspecialchars($product['color'] ?? 'Black') ?></strong></div>
                    <div class="col-6"><span class="text-muted d-block" style="font-size:0.75rem;">Belt Width</span><strong><?= htmlspecialchars($product['belt_width'] ?? '1.5 inches') ?></strong></div>
                    <div class="col-6"><span class="text-muted d-block" style="font-size:0.75rem;">Material</span><strong><?= htmlspecialchars($product['material'] ?? 'Genuine Leather') ?></strong></div>
                    <div class="col-6"><span class="text-muted d-block" style="font-size:0.75rem;">Occasion</span><strong>Casual, Formal</strong></div>
                </div>
            </div>

            <div class="app-section-card p-2 p-md-3 bg-white mb-2 rounded shadow-sm">
                <h6 class="fw-bold text-dark border-bottom pb-1 mb-2" style="font-size:0.9rem;">All Details</h6>
                <table class="table table-sm table-bordered small mb-0 mt-2" style="font-size: 12px; border-color: #eee !important;">
                    <tbody>
                        <tr><td class="text-muted py-2 px-2" style="width:35%;">Model Name</td><td class="text-dark py-2 px-2"><?= htmlspecialchars($product['model_name'] ?? 'Men Genuine Leather Belt') ?></td></tr>
                        <tr><td class="text-muted py-2 px-2">Weight</td><td class="text-dark py-2 px-2"><?= htmlspecialchars($product['weight'] ?? '300 g') ?></td></tr>
                        <tr><td class="text-muted py-2 px-2">Warranty</td><td class="text-dark py-2 px-2"><?= htmlspecialchars($product['warranty'] ?? '6 Months') ?></td></tr>
                        <tr><td class="text-muted py-2 px-2">Description</td><td class="text-dark py-2 px-2" style="line-height:1.4; white-space: normal; text-overflow: clip;"><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="app-sticky-bottom" style="position: sticky; bottom: 0; background: #fff; padding: 10px 0; z-index: 10;">
                <form action="<?php echo url('pages/products/cart.php'); ?>" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="selected_image" id="cart_selected_image" value="<?php echo $firstImgUrl; ?>">
                    <input type="hidden" name="size" id="cart_selected_size" value="">

                    <button type="submit" name="add_to_cart" class="btn btn-lg w-50 py-2.5 text-white border-0 text-uppercase" style="background:#ff9f00; font-size:14px; font-weight:700; border-radius:6px;">Add to Cart</button>
                    <button type="submit" name="buy_now" class="btn btn-lg w-50 py-2.5 text-white border-0 text-uppercase" style="background:#fb641b; font-size:14px; font-weight:700; border-radius:6px;">Buy Now</button>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
let currentSelectedSize = "";

// जब कैरोसेल स्लाइड बदलेगा, तो साइज और सिलेक्टेड इमेज ऑटोमैटिक अपडेट होगी
const carouselEl = document.getElementById('productImagesCarousel');
if (carouselEl) {
    carouselEl.addEventListener('slide.bs.carousel', event => {
        const activeSlide = event.relatedTarget;
        const imgUrl = activeSlide.getAttribute('data-imgurl');
        const sizesString = activeSlide.getAttribute('data-sizes');
        
        // फॉर्म इनपुट अपडेट करें
        document.getElementById('cart_selected_image').value = imgUrl;
        
        // थंबनेल का एक्टिव बॉर्डर अपडेट करें
        document.querySelectorAll('.thumb-box').forEach((thumb, idx) => {
            if(idx === event.to) {
                thumb.classList.add('active-thumb');
            } else {
                thumb.classList.remove('active-thumb');
            }
        });

        // साइज रेंडर करें
        renderSizes(sizesString);
    });
}

function renderSizes(sizesString) {
    const container = document.getElementById('dynamicSizesRow');
    container.innerHTML = ''; 
    
    const sizesArray = sizesString ? sizesString.split(',') : [];
    
    if(sizesArray.length > 0 && sizesArray[0].trim() !== "") {
        sizesArray.forEach((size, index) => {
            const cleanSize = size.trim();
            if(cleanSize) {
                // अगर पुराना सिलेक्टेड साइज इस इमेज में है तो उसे रखें, नहीं तो पहला साइज चुने
                const isSelected = (currentSelectedSize === cleanSize) || (!sizesArray.includes(currentSelectedSize) && index === 0);
                
                if(isSelected) {
                    currentSelectedSize = cleanSize;
                    document.getElementById('cart_selected_size').value = cleanSize;
                }

                const activeClass = isSelected ? 'active-size' : 'bg-f5f5f5 text-dark';
                container.innerHTML += `<div class="size-chip ${activeClass}" style="cursor:pointer; transition: 0.2s;" onclick="selectSize(this, '${cleanSize}')">${cleanSize}</div>`;
            }
        });
    } else {
        container.innerHTML = `<span class="text-secondary small fw-medium">Free Size</span>`;
        document.getElementById('cart_selected_size').value = "Free Size";
    }
}

function selectSize(element, size) {
    document.querySelectorAll('.size-chip').forEach(chip => {
        chip.classList.remove('active-size');
    });
    element.classList.add('active-size');
    currentSelectedSize = size;
    document.getElementById('cart_selected_size').value = size;
}

// पेज लोड पर इनिशियलाइज करें
document.addEventListener("DOMContentLoaded", function() {
    renderSizes('<?= htmlspecialchars($firstImgSizes) ?>');
});
</script>

<?php include '../../includes/footer.php'; ?>