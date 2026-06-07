<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$wishlistCount = 0;
$user_name = "My Account";
$final_avatar = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png"; // Default Avatar

// 👤 Logged-in User ka Name aur Profile Image fetch karne ka logic
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    
    // Wishlist Count Fetching
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $wishlistCount = $stmt->fetchColumn();

    // User Details Fetching (Name & Pic)
    $user_stmt = $pdo->prepare("SELECT name, profile_pic FROM users WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if($user_data){
        // Agar full name hai toh sirf first name nikalne ke liye (e.g., "Rahul Sharma" -> "Rahul")
        $name_parts = explode(' ', trim($user_data['name']));
        $user_name = htmlspecialchars($name_parts[0]);
        
        if(!empty($user_data['profile_pic'])){
            $final_avatar = $user_data['profile_pic']; // Cloudinary secure URL
        }
    }
}
?>

<!-- TOP NOTIFICATION BAR (Flipkart style slim alert) -->
<div class="py-1 text-white bg-gradient d-none d-lg-flex align-items-center" style="background-color: #6366f1; background-image: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 32px; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);"> 
    <div class="container-fluid px-5 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-4 align-items-center fw-medium">
            <a href="pages/about.php" class="text-white text-decoration-none d-flex align-items-center gap-1 opacity-90 link-light"><i class="ri-information-line"></i> About Us</a>
            <a href="contact.php" class="text-white text-decoration-none d-flex align-items-center gap-1 opacity-90 link-light"><i class="ri-customer-service-2-line"></i> 24/7 Support</a>
            <a href="track.php" class="text-white text-decoration-none d-flex align-items-center gap-1 opacity-90 link-light"><i class="ri-truck-line"></i> Track Order</a>
        </div>
        
        <div class="fw-semibold d-flex align-items-center gap-1">
            <i class="ri-flashlight-line text-warning align-middle fs-6" style="animation: pulse 1.5s infinite;"></i> 
            <span>ELDURATO Sale: Save 20% on Premium Belts! Code: </span>
            <span class="badge bg-warning text-dark ms-1 px-2 py-0.5 shadow-sm font-monospace" style="font-size: 11px;">BELT20</span> 
        </div>
        
        <div class="d-flex gap-3 align-items-center opacity-90 fw-medium">
            <span><i class="ri-map-pin-line text-warning"></i> India</span>
            <span><i class="ri-global-line"></i> EN</span>
        </div>
    </div>
</div>

<!-- MAIN NAVBAR (Flipkart Wide Search Style) -->
<nav class="bg-white sticky-top shadow-sm" style="z-index: 1040; top: 0; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
    
    <!-- DESKTOP HEADER -->
    <div class="d-none d-md-block">
        <div class="container-fluid px-5">
            <div class="row align-items-center flex-nowrap">
                
                <!-- BRAND LOGO -->
                <div class="col-auto col-md-3">
                   <a href="<?php echo SITE_URL; ?>" class="text-decoration-none d-flex align-items-center gap-2" style="letter-spacing: -0.5px;">
                        <i class="ri-shield-flash-fill text-success" style="font-size: 1.6rem;"></i> 
                        <span style="color: #0f172a; font-weight: 800; font-size: 1.35rem;">ELDURATO</span>
                        <span class="badge bg-danger text-uppercase px-1 py-0.5 text-white rounded-1" style="font-weight: 700; font-size: 10px; letter-spacing: 0px;">Store</span>
                    </a>
                </div>

                <!-- Flipkart Style Wide Search Bar -->
                <div class="col">
                    <form action="search.php" method="GET" class="w-100">
                        <div class="input-group rounded-2 overflow-hidden shadow-sm border" style="background: #f0f2f5; border-color: #cbd5e1 !important; height: 40px;">
                            <select class="form-select border-0 bg-transparent text-muted small fw-semibold d-none d-lg-block py-0" style="max-width: 140px; font-size: 13px; outline: none; box-shadow: none;">
                                <option>All Categories</option>
                                <option>Pure Leather</option>
                                <option>Men's Belts</option>
                                <option>Women's Belts</option>
                                <option>Luxury Buckles</option>
                            </select>
                            <span class="d-none d-lg-block border-end my-2 mx-1" style="border-color: #cbd5e1 !important;"></span>
                            <input type="text" name="q" class="form-control border-0 bg-transparent px-3 py-0" style="font-size: 14px; box-shadow: none;" placeholder="Search for genuine leather belts, brands and more...">
                            <button type="submit" class="btn border-0 rounded-0 px-4 text-white bg-gradient d-flex align-items-center justify-content-center" style="background-color: #4f46e5;">
                                <i class="ri-search-2-line fs-5"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Action Buttons -->
                <div class="col-auto col-md-4 d-flex justify-content-end align-items-center gap-4">
                    
                    <!-- Profile / Login Dropdown -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a href="#" class="text-dark fw-bold text-decoration-none d-flex align-items-center gap-2 nav-action-link" data-bs-toggle="dropdown">
                                <!-- 🔴 User Real Profile Image Component -->
                                <img src="<?= $final_avatar ?>" alt="<?= $user_name ?>" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;">
                                <span>Hi, <?= $user_name ?></span>
                                <i class="ri-arrow-down-s-line opacity-50"></i>
                            </a>
                            <ul class="dropdown-menu shadow border-0 mt-2" style="border-radius: 4px; font-size: 14px;">
                                <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/pages/account/dashboard.php"><i class="ri-dashboard-line me-2 text-primary"></i>Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/pages/auth/profile.php"><i class="ri-user-settings-line me-2 text-success"></i>My Profile</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/orders.php"><i class="ri-box-3-line me-2 text-warning"></i>Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?php echo SITE_URL; ?>/logout.php"><i class="ri-logout-box-r-line me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/pages/auth/login.php" class="btn btn-white fw-bold px-4 shadow-sm border border-light text-decoration-none flipkart-login-btn" style="color: #4f46e5; background: #ffffff; border-radius: 2px; height: 36px; line-height: 22px;">
                            Login
                        </a>
                    <?php endif; ?>

                    <!-- Wishlist Link -->
                    <a href="<?php echo SITE_URL; ?>/pages/products/wishlist.php" class="text-dark fw-bold text-decoration-none d-flex align-items-center gap-1 position-relative nav-action-link">
                        <i class="ri-heart-3-line fs-5" style="color: #ec4899;"></i>
                        <span>Wishlist</span>
                        <span id="wishlist-count" class="badge rounded-pill bg-danger ms-1" style="font-size: 10px; padding: 3px 6px;"><?php echo $wishlistCount; ?></span>
                    </a>
                    
                    <!-- Cart Link -->
                     
                    <a href="<?php echo SITE_URL; ?>/pages/products/cart.php" class="text-dark fw-bold text-decoration-none d-flex align-items-center gap-1 position-relative nav-action-link">
                        <i class="ri-shopping-cart-2-line fs-5 text-success"></i>
                        <span>Cart</span>
                        <span id="desktop-cart-badge" class="badge rounded-pill bg-success ms-1" style="font-size: 10px; padding: 3px 6px;"><?php echo $cartCount; ?></span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- MOBILE HEADER (Flipkart App Style) -->
    <div class="px-3 d-block d-md-none">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-3">
                <button class="btn p-0 border-0 text-dark fs-3 lh-1" type="button" id="mobileMenuBtn">
                    <i class="ri-menu-2-line"></i>
                </button>
                <a href="index.php" class="text-decoration-none d-flex flex-column">
                    <span style="color: #0f172a; font-weight: 800; font-size: 1.3rem; letter-spacing: -0.5px; line-height: 1;">ELDURATO</span>
                    <span class="text-muted font-italic" style="font-size: 9px; font-style: italic;">Explore <span style="color:#4f46e5; font-weight:700;">Plus</span></span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- 🔴 Mobile User Avatar (Profile Page Link) -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo SITE_URL; ?>/pages/auth/profile.php" class="text-decoration-none lh-1 align-middle">
                        <img src="<?= $final_avatar ?>" alt="<?= $user_name ?>" class="rounded-circle border" style="width: 26px; height: 26px; object-fit: cover;">
                    </a>
                <?php endif; ?>

                <a href="<?php echo SITE_URL; ?>/wishlist.php" class="text-dark fs-3 text-decoration-none position-relative lh-1">
                    <i class="ri-heart-line"></i>
                    <span id="mobile-wishlist-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px; padding: 2px 4px;"><?php echo $wishlistCount; ?></span>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/products/cart.php" class="text-dark fs-3 text-decoration-none position-relative lh-1">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span id="mobile-cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size:8px; padding:2px 4px;"><?php echo $cartCount; ?></span>
                </a>
            </div>
        </div>
        
        <!-- Mobile Search Bar -->
        <div class="input-group rounded-2 overflow-hidden border shadow-sm" style="background: #f1f5f9; border-color: #e2e8f0 !important; height: 36px;">
            <span class="input-group-text border-0 bg-transparent text-muted pe-1 py-0"><i class="ri-search-2-line" style="font-size: 14px;"></i></span>
            <input type="text" class="form-control border-0 bg-transparent py-0 px-2" style="font-size: 13px;" placeholder="Search belts, brands, buckles...">
        </div>
    </div>
</nav>


<!-- FLIPKART STYLE MULTI-DROP DOWN MEU BAR (White background centered list) -->
<div class="border-bottom d-none d-md-block bg-white shadow-0">
    <div class="container-fluid px-5">
        <div class="d-flex align-items-center justify-content-center">
            <ul class="nav mb-0 list-unstyled d-flex align-items-center gap-2 fw-semibold menu-container" style="font-size: 14px;">
                
                <li class="nav-item-wrapper">
                    <a href="<?php echo SITE_URL; ?>" class="nav-link py-2.5 px-3 text-primary d-flex align-items-center gap-1 active-tab">
                         Home
                    </a>
                </li>        
                
                <li class="nav-item-wrapper has-mega">
                    <a href="#" class="nav-link text-dark py-2.5 px-3 d-flex align-items-center gap-1 flipkart-nav-link">
                        Men's Section <i class="ri-arrow-down-s-line opacity-50 text-muted"></i>
                    </a>
                    <div class="mega-menu-content shadow-lg border p-4">
                        <div class="row g-4 text-start">
                            <div class="col-md-3">
                                <div class="fw-bold text-primary mb-2 text-uppercase small border-bottom pb-1" style="letter-spacing: 0.5px; font-size: 11px;">By Usage</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Formal Office Belts</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Casual Jeans Belts</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Party Wear Editions</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Tactical & Military</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="fw-bold text-primary mb-2 text-uppercase small border-bottom pb-1" style="letter-spacing: 0.5px; font-size: 11px;">Buckle Type</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Automatic Click Buckle</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Classic Pin Buckle</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Reversible (Dual-Sided)</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">D-Ring / Canvas Lock</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="fw-bold text-primary mb-2 text-uppercase small border-bottom pb-1" style="letter-spacing: 0.5px; font-size: 11px;">Premium Brands</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Italian Import Series</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Louis Vintage</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Urban Hide Suede</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">WildHorn Originals</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 rounded text-center shadow-sm h-100 d-flex flex-column justify-content-center" style="background: #fdf2f8; border: 1px dashed #ec4899;">
                                    <span class="fw-bold text-dark d-block mb-1 small">Buy 1 Get 1 Free</span>
                                    <small class="text-muted d-block mb-2" style="font-size:10px;">Valid on All Men's Formals</small>
                                    <a href="#" class="btn btn-sm btn-dark py-1 px-3 rounded-pill fw-bold text-white align-self-center" style="font-size:0.7rem; background:#db2777; border:none;">Explore</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item-wrapper has-mega">
                    <a href="#" class="nav-link text-dark py-2.5 px-3 d-flex align-items-center gap-1 flipkart-nav-link">
                        Women's Section <i class="ri-arrow-down-s-line opacity-50 text-muted"></i>
                    </a>
                    <div class="mega-menu-content shadow-lg border p-4">
                        <div class="row g-4 text-start">
                            <div class="col-md-4">
                                <div class="fw-bold text-pink mb-2 text-uppercase small border-bottom pb-1" style="color:#db2777; letter-spacing: 0.5px; font-size: 11px;">Trending Styles</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Slim Dress Belts</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Wide Waist Corset Belts</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Stretchable Elastic Bands</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Statement Luxury Pieces</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="fw-bold text-pink mb-2 text-uppercase small border-bottom pb-1" style="color:#db2777; letter-spacing: 0.5px; font-size: 11px;">Finishes & Colors</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Gold/Silver Metallic Buckles</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Textured Croco Finish</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Matte Pastel Shades</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Genuine Suede Finish</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="fw-bold text-pink mb-2 text-uppercase small border-bottom pb-1" style="color:#db2777; letter-spacing: 0.5px; font-size: 11px;">Occasion Wear</div>
                                <ul class="list-unstyled d-flex flex-column gap-2 small">
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Saree & Traditional Belts</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Casual Denim Accessories</a></li>
                                    <li><a href="#" class="text-secondary text-decoration-none hover-link">Office Formals Collection</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item-wrapper has-simple">
                    <a href="#" class="nav-link text-dark py-2.5 px-3 d-flex align-items-center gap-1 flipkart-nav-link">
                        Shop By Material <i class="ri-arrow-down-s-line opacity-50 text-muted"></i>
                    </a>
                    <div class="simple-dropdown shadow-lg border">
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">100% Full-Grain Leather</a>
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">Genuine Suede Leather</a>
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">Eco Vegan & Faux Belt</a>
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">Braided Canvas & Elastic</a>
                        <a href="#" class="dropdown-item py-2 px-3">Heavy Nylon Tactical</a>
                    </div>
                </li>

                <li class="nav-item-wrapper has-simple">
                    <a href="#" class="nav-link py-2.5 px-3 text-white bg-gradient d-flex align-items-center gap-1 shadow-sm" style="background-color: #f97316; background-image: linear-gradient(135deg, #f97316 0%, #ea580c 100%); margin-left: 10px; border-radius: 2px;">
                        <i class="ri-percent-fill text-warning"></i> HOT SALES ZONE <i class="ri-arrow-down-s-line opacity-50 text-white"></i>
                    </a>
                    <div class="simple-dropdown shadow-lg border">
                        <a href="#" class="dropdown-item py-2 px-3 text-danger fw-bold border-bottom">Flat 50% Off Counter</a>
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">Under ₹499 Store</a>
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">Combo Gifting Sets (Save 30%)</a>
                        <a href="#" class="dropdown-item py-2 px-3">End of Season Clearance</a>
                    </div>
                </li>

                <li class="nav-item-wrapper">
                    <a href="combos.php" class="nav-link text-dark py-2.5 px-3 flipkart-nav-link"><i class="ri-gift-fill text-danger"></i> Gift Combos</a>
                </li>
                <li class="nav-item-wrapper">
                    <a href="new-arrivals.php" class="nav-link text-dark py-2.5 px-3 flipkart-nav-link"><i class="ri-fire-fill text-warning"></i> New Launches</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- STYLES UPDATE (Flipkart-like minimal Hovers) -->
<style>
    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }
    .menu-container { position: relative; }
    .nav-item-wrapper { position: relative; }
    
    /* Nav Links Hover Effects Like Flipkart */
    .flipkart-nav-link {
        color: #212121 !important;
        transition: color 0.2s ease;
    }
    .flipkart-nav-link:hover {
        color: #4f46e5 !important; /* Aapka brand indigo color */
    }
    .nav-action-link {
        transition: opacity 0.2s;
    }
    .nav-action-link:hover {
        opacity: 0.8;
    }
    .flipkart-login-btn:hover {
        background-color: #f0f2f5 !important;
    }

    /* Dropdown Transitions */
    .simple-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background: #ffffff;
        min-width: 230px;
        display: none;
        z-index: 1050;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15) !important;
    }
    .simple-dropdown .dropdown-item {
        color: #212121;
        font-size: 13px;
        transition: all 0.15s ease-in-out;
    }
    .simple-dropdown .dropdown-item:hover {
        background-color: #f5f7fa;
        color: #4f46e5;
    }
    .has-simple:hover .simple-dropdown { display: block; }

    /* Wide Mega Menu */
    .mega-menu-content {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        width: 900px;
        background: #ffffff;
        display: none;
        z-index: 1050;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15) !important;
    }
    .has-mega:hover .mega-menu-content { display: block; }
    .hover-link { transition: all 0.15s ease-in-out; color: #666 !important; }
    .hover-link:hover { color: #4f46e5 !important; font-weight: 500; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("mobileMenuBtn");
    const closeBtn = document.getElementById("closeMenuBtn");
    const drawer = document.getElementById("mobileMenuDrawer");
    const backdrop = document.getElementById("menuBackdrop");

    function openMenu() {
        if(drawer && backdrop) {
            drawer.style.visibility = "visible";
            drawer.classList.add("show");
            backdrop.classList.remove("d-none");
            document.body.style.overflow = "hidden";
        }
    }

    function closeMenu() {
        if(drawer && backdrop) {
            drawer.classList.remove("show");
            backdrop.classList.add("d-none");
            document.body.style.overflow = "";
            setTimeout(() => {
                if (!drawer.classList.contains("show")) {
                    drawer.style.visibility = "hidden";
                }
            }, 300);
        }
    }

    if(menuBtn) menuBtn.addEventListener("click", openMenu);
    if(closeBtn) closeBtn.addEventListener("click", closeMenu);
    if(backdrop) backdrop.addEventListener("click", closeMenu);
});

function updateWishlistCount() {
    const badge = document.getElementById('wishlist-count');
    const mobileBadge = document.getElementById('mobile-wishlist-count');

    if(badge){
        const filledHeart = document.querySelectorAll('.ri-heart-fill').length;
        badge.innerText = filledHeart;
        if(mobileBadge){
            mobileBadge.innerText = filledHeart;
        }
    }
}
</script>