<!-- BANNER CAROUSEL (Flipkart/Meesho Style - Full Width & Slick) -->
<section class="hero-carousel-section mb-4">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <a href="<?php echo url('pages/products/products.php'); ?>">
                    <picture>
                        <img src="/belt/assets/images/hero-slide-1.png" class="d-block w-100" alt="Premium Leather Belts">
                    </picture>
                </a>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <a href="<?php echo url('pages/products/products.php'); ?>">
                    <picture>
                        <img src="/belt/assets/images/hero-slide-2.webp" class="d-block w-100" alt="Modern Luxury Class">
                    </picture>
                </a>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <a href="<?php echo url('pages/products/products.php'); ?>">
                    <picture>
                        <img src="/belt/assets/images/hero-slide-3.png" class="d-block w-100" alt="Mega Sale Banner">
                    </picture>
                </a>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); padding: 20px; border-radius: 50%;"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); padding: 20px; border-radius: 50%;"></span>
        </button>
    </div>
</section>

<!-- MAIN BODY CONTAINER -->
<main class="container-fluid homeBar px-md-5">

    <!-- 1. QUICK CATEGORIES (Meesho/Flipkart Circular Style) -->
     
   <!-- Fully Uniform, Clean & DRY Styled E-commerce Categories -->
<style>
    /* Smooth Custom Scrollbar */
    .csv-scroll::-webkit-scrollbar { height: 4px; }
    .csv-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* Main Container */
    .quick-cat-section {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        padding: 20px 10px;
    }

    /* DRY Base class for items */
    .cat-item {
        min-width: 110px;
        position: relative;
    }

    /* Uniform Circle Wrapper - Everyone gets the same beautiful double-ring gradient */
    .circle-wrapper {
        position: relative;
        width: 86px;
        height: 86px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        /* Outer Ring/Shadow simulation like the picture */
        background: radial-gradient(circle, #ffffff 62%, var(--bg-shade) 100%);
        border: 2px solid var(--bg-shade);
    }

    /* Core Image Style with Solid Vibrant Borders */
    .circle-wrapper img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        z-index: 2;
        border: 3px solid var(--theme-color);
    }

    /* --- Clean Vector Accent Overlays (Always Visible) --- */
    .cat-item::after {
        position: absolute;
        top: 2px;
        right: 18px;
        font-size: 14px;
        z-index: 3;
    }
    
    /* Unique subtle icons for each, matching the illustration theme */
    .cat-item[data-cat="formal"]::after { content: '✨'; opacity: 0.9; }
    .cat-item[data-cat="casual"]::after { content: '⚡'; opacity: 0.9; }
    .cat-item[data-cat="luxury"]::after { content: '✨'; }
    .cat-item[data-cat="leather"]::after { content: '🍃'; }
    .cat-item[data-cat="trending"]::after { content: '↗'; font-size: 16px; font-weight: bold; color: var(--theme-color); top: 0px; }

    /* Unified Styled Text directly using Theme Colors */
    .cat-title {
        font-size: 14px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--theme-color);
    }
</style>

<section class="my-4 quick-cat-section text-center">
    <div class="d-flex justify-content-around flex-nowrap csv-scroll" style="overflow-x: auto; white-space: nowrap; padding-bottom: 5px;">
        
        <!-- 1. Formal Belts -->
        <div class="p-2 d-inline-block cat-item" data-cat="formal" style="--theme-color: #0284c7; --bg-shade: #e0f2fe;">
            <div class="circle-wrapper">
                <img src="https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=120&h=120&fit=crop" alt="Formal">
            </div>
            <p class="cat-title">Formal<br>Belts</p>
        </div>

        <!-- 2. Casual Belts -->
        <div class="p-2 d-inline-block cat-item" data-cat="casual" style="--theme-color: #ea580c; --bg-shade: #ffedd5;">
            <div class="circle-wrapper">
                <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=120&h=120&fit=crop" alt="Casual">
            </div>
            <p class="cat-title">Casual<br>Belts</p>
        </div>

        <!-- 3. Premium Luxury -->
        <div class="p-2 d-inline-block cat-item" data-cat="luxury" style="--theme-color: #7c3aed; --bg-shade: #f3e8ff;">
            <div class="circle-wrapper">
                <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=120&h=120&fit=crop" alt="Luxury">
            </div>
            <p class="cat-title">Premium<br>Luxury</p>
        </div>

        <!-- 4. 100% Pure Leather -->
        <div class="p-2 d-inline-block cat-item" data-cat="leather" style="--theme-color: #16a34a; --bg-shade: #dcfce7;">
            <div class="circle-wrapper">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=120&h=120&fit=crop" alt="Leather">
            </div>
            <p class="cat-title">100% Pure<br>Leather</p>
        </div>

        <!-- 5. Trending Now -->
        <div class="p-2 d-inline-block cat-item" data-cat="trending" style="--theme-color: #ca8a04; --bg-shade: #fef9c3;">
            <div class="circle-wrapper">
                <img src="https://images.unsplash.com/photo-1517462964-21fdcec3f25b?w=120&h=120&fit=crop" alt="Trending">
            </div>
            <p class="cat-title">Trending<br>Now</p>
        </div>

    </div>
</section>

    <!-- 2. TRUST BADGES (Flipkart Style) -->
    <div class="row text-center bg-white py-3 my-4 mx-0 rounded shadow-sm border-bottom border-warning border-3">
        <div class="col-6 col-md-3 border-end mb-2 mb-md-0">
            <i class="bi bi-truck text-warning fs-3"></i>
            <p class="mb-0 small fw-bold">Free Shipping</p>
        </div>
        <div class="col-6 col-md-3 border-md-end mb-2 mb-md-0">
            <i class="bi bi-arrow-counterclockwise text-warning fs-3"></i>
            <p class="mb-0 small fw-bold">7 Days Replacement</p>
        </div>
        <div class="col-6 col-md-3 border-end">
            <i class="bi bi-shield-check text-warning fs-3"></i>
            <p class="mb-0 small fw-bold">100% Original Leather</p>
        </div>
        <div class="col-6 col-md-3">
            <i class="bi bi-currency-rupee text-warning fs-3"></i>
            <p class="mb-0 small fw-bold">Cash on Delivery (COD)</p>
        </div>
    </div>

    <!-- 3. DEAL OF THE DAY (Flipkart Grid Style) -->
    <section class="bg-white p-3 rounded shadow-sm my-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h4 class="mb-0 fw-bold text-dark d-inline-block me-2">Deals Of The Day</h4>
                <span class="badge bg-danger animate-pulse">Limited Time</span>
            </div>
            <a href="<?php echo url('pages/products/products.php'); ?>" class="btn btn-primary btn-sm fw-semibold">View All</a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
            <!-- Product Card 1 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">60% OFF</span>
                    <img src="https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Classic Tan Leather Belt</h6>
                        <p class="mb-1 text-success small fw-semibold">Min. 60% Off</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹499</span>
                            <span class="text-muted text-decoration-line-through small">₹1,249</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">55% OFF</span>
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Formal Black Elite Belt</h6>
                        <p class="mb-1 text-success small fw-semibold">Grab or Gone</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹549</span>
                            <span class="text-muted text-decoration-line-through small">₹1,199</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">70% OFF</span>
                    <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Vintage Distressed Brown</h6>
                        <p class="mb-1 text-success small fw-semibold">Top Selling</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹399</span>
                            <span class="text-muted text-decoration-line-through small">₹1,330</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">50% OFF</span>
                    <img src="https://images.unsplash.com/photo-1517462964-21fdcec3f25b?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Italian Auto-Lock Buckle</h6>
                        <p class="mb-1 text-success small fw-semibold">Hot Deal</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹699</span>
                            <span class="text-muted text-decoration-line-through small">₹1,399</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">45% OFF</span>
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Casual Braided Leather</h6>
                        <p class="mb-1 text-success small fw-semibold">Trending Price</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹449</span>
                            <span class="text-muted text-decoration-line-through small">₹899</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-zoom position-relative">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">65% OFF</span>
                    <img src="https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=300&h=300&fit=crop" class="card-img-top p-2 rounded" alt="Belt">
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title text-truncate mb-1 fw-bold">Premium Matte Reversible</h6>
                        <p class="mb-1 text-success small fw-semibold">Special Edition</p>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <span class="fw-bold text-dark">₹599</span>
                            <span class="text-muted text-decoration-line-through small">₹1,699</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. TWO-COLUMNS BANNER GRID (Meesho Style Combo Deals) -->
    <div class="row my-4 g-3">
        <div class="col-md-6">
            <div class="position-relative overflow-hidden rounded bg-dark text-white shadow" style="height: 200px;">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&fit=crop" class="w-100 h-100 object-fit-cover opacity-50" alt="Combo">
                <div class="position-absolute top-50 start-0 translate-middle-y ps-4">
                    <h3 class="fw-bold mb-1">Buy 1 Get 1 Free</h3>
                    <p class="mb-2 text-warning fw-semibold">On Casual Belt Collections</p>
                    <a href="<?php echo url('pages/products/products.php'); ?>" class="btn btn-warning btn-sm fw-bold px-3">Unlock Offer</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative overflow-hidden rounded bg-primary text-white shadow" style="height: 200px;">
                <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&fit=crop" class="w-100 h-100 object-fit-cover opacity-50" alt="Gift Box">
                <div class="position-absolute top-50 start-0 translate-middle-y ps-4">
                    <h3 class="fw-bold mb-1">Luxury Gift Box Packs</h3>
                    <p class="mb-2 text-light fw-semibold">Perfect Gift for Corporate & Grooms</p>
                    <a href="<?php echo url('pages/products/products.php'); ?>" class="btn btn-light btn-sm fw-bold text-primary px-3">Explore Kits</a>
                </div>
            </div>
        </div>
    </div>

 <!-- 5. ALL PRODUCTS GRID WITH FILTERS -->
<section class="p-2 my-3">
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <div>
            <h5 class="fw-bold text-dark mb-0">Products For You</h5>
            <small class="text-muted d-block" style="font-size: 0.75rem;">Latest Premium Collection</small>
        </div>
        <a href="/belt/pages/products/products.php" class="btn btn-sm btn-light border fw-bold text-primary text-uppercase px-3 rounded-1" style="font-size: 0.75rem;">
            View All
        </a>
    </div>

    <?php 
    // एक फ्लैग डिफाइन करें ताकिproducts.php को पता चले कि उसे हेडर/फुटर लोड नहीं करना है
    define('INCLUDED_IN_HERO', true);
    include __DIR__ . '/../pages/products/products.php'; 
    ?>
</section>

    <!-- 6. USER TESTIMONIALS & REVIEWS SECTION -->
    <section class="my-5">
        <h4 class="text-center fw-bold mb-4">What Our Happy Customers Say</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="bg-white p-3 rounded shadow-sm border-start border-primary border-4">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="small text-muted italic">"The leather quality is exactly like high-end brands. Perfect stiffness and smooth texture. Worth every rupee!"</p>
                    <h6 class="fw-bold mb-0 text-dark">- Rajesh K. (Delhi)</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-3 rounded shadow-sm border-start border-success border-4">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i>
                    </div>
                    <p class="small text-muted italic">"Awesome product. I ordered the auto-lock buckle belt and it looks very luxury with formals. Fast delivery by the store."</p>
                    <h6 class="fw-bold mb-0 text-dark">- Amit Verma (Mumbai)</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-3 rounded shadow-sm border-start border-warning border-4">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="small text-muted italic">"ELDURATO like price and Flipkart like delivery speed. 100% original leather checked. Fully satisfied."</p>
                    <h6 class="fw-bold mb-0 text-dark">- Vikram S. (Bangalore)</h6>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- EXTRA CUSTOM CSS FOR FLIPKART/MEESHO STYLE FEEL (Add to your style file or head) -->
<style>
    body {
        background-color: #f1f3f6; /* Flipkart Light Gray Background */
        font-family: Roboto, Arial, sans-serif;
    }
    .carousel-item img {
        object-fit: cover;
        width: 100%;
    }
    .hover-zoom:hover {
        transform: scale(1.03);
        transition: transform 0.2s ease-in-out;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    .product-card:hover {
        border-color: #2874f0 !important; /* Flipkart Blue Accent */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    /* Hide scrollbar for Horizontal category list */
    .csv-scroll::-webkit-scrollbar {
        display: none;
    }
    .csv-scroll {
        -ms-overflow-style: none;  
        scrollbar-width: none;  
    }
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }
    .animate-pulse {
        animation: pulse 1.5s infinite;
    }
</style>

<!-- Bootstrap Icons (If not already included in your project) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">