<!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<footer class="text-white pt-5 pb-3 mt-5 border-top border-3 border-info shadow" style="background-color: #07111e;">
    <div class="container">
        <div class="row g-4 text-md-start text-center">
            
            <!-- Column 1: Brand Logo & About -->
            <div class="col-md-3 col-sm-6">
                <h5 class="text-uppercase fw-bold mb-3 d-flex align-items-center justify-content-center justify-content-md-start text-white" style="letter-spacing: 0.8px;">
                    <i class="ri-shopping-bag-3-fill me-2 text-info"></i> 
                    <?php echo defined('SITE_NAME') ? SITE_NAME : 'ELDURATO'; ?>
                </h5>
                <p class="small lh-lg text-white-50">Aapki apni trusted shopping destination. Best quality products, premium customer service aur safe delivery ke sath.</p>
                
                <!-- Official Colored Social Icons -->
                <div class="d-flex justify-content-center justify-content-md-start gap-3 mt-3">
                    <a href="#" class="fs-4 text-decoration-none" aria-label="Facebook" style="color: #1877F2;"><i class="ri-facebook-circle-fill"></i></a>
                    <a href="#" class="fs-4 text-decoration-none" aria-label="Instagram" style="color: #E1306C;"><i class="ri-instagram-line"></i></a>
                    <a href="#" class="fs-4 text-decoration-none text-white" aria-label="Twitter/X"><i class="ri-twitter-x-fill"></i></a>
                    <a href="#" class="fs-4 text-decoration-none" aria-label="YouTube" style="color: #FF0000;"><i class="ri-youtube-fill"></i></a>
                </div>
            </div>

            <!-- Column 2: Categories -->
            <div class="col-md-3 col-sm-6">
                <h5 class="text-info text-uppercase fw-semibold mb-3 small d-inline-block pb-2 border-bottom border-info border-2" style="letter-spacing: 1px;">Shop Categories</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="categories.php?type=electronics" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-smartphone-line text-info"></i> Electronics
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="categories.php?type=fashion" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-shirt-line text-info"></i> Men & Women Fashion
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="categories.php?type=home" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-home-gear-line text-info"></i> Home & Kitchen
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="categories.php?type=beauty" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-sparkles-line text-info"></i> Beauty & Care
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Customer Support -->
            <div class="col-md-3 col-sm-6">
                <h5 class="text-info text-uppercase fw-semibold mb-3 small d-inline-block pb-2 border-bottom border-info border-2" style="letter-spacing: 1px;">Customer Support</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="track-order.php" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-truck-line text-info"></i> Track Your Order
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="return-policy.php" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-arrow-go-back-line text-info"></i> 7 Days Return Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="contact.php" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-customer-service-2-line text-info"></i> Help Center / Contact Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="faq.php" class="text-white text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start gap-2 opacity-75 opacity-100-hover transition-all">
                            <i class="ri-questionnaire-line text-info"></i> FAQs
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Contact & Secure Payments -->
            <div class="col-md-3 col-sm-6">
                <h5 class="text-info text-uppercase fw-semibold mb-3 small d-inline-block pb-2 border-bottom border-info border-2" style="letter-spacing: 1px;">Get In Touch</h5>
                <p class="small text-white mb-2 d-flex align-items-center justify-content-center justify-content-md-start gap-2"><i class="ri-map-pin-2-line text-info"></i> 123, E-Commerce Hub, India</p>
                <p class="small text-white mb-2 d-flex align-items-center justify-content-center justify-content-md-start gap-2"><i class="ri-phone-line text-info"></i> +91 98765 43210</p>
                <p class="small text-white mb-4 d-flex align-items-center justify-content-center justify-content-md-start gap-2"><i class="ri-mail-line text-info"></i> support@<?php echo strtolower(defined('SITE_NAME') ? SITE_NAME : 'ELDURATO'); ?>.com</p>
                
                <h6 class="small text-uppercase fw-semibold mb-2 text-white-50">Secure Payment</h6>
                <div class="d-flex justify-content-center justify-content-md-start gap-3 text-white fs-3">
                    <i class="ri-visa-line" title="Visa" style="cursor: pointer;"></i>
                    <i class="ri-mastercard-line" title="Mastercard" style="cursor: pointer;"></i>
                    <i class="ri-bank-card-line" title="RuPay/UPI" style="cursor: pointer;"></i>
                    <i class="ri-hand-coin-line" title="Cash on Delivery" style="cursor: pointer;"></i>
                </div>
            </div>

        </div>

        <!-- Custom Glass/Thin Line Divider -->
        <hr class="text-white opacity-25 my-4">

        <!-- Copyright Info -->
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> <span class="text-white fw-bold"><?php echo defined('SITE_NAME') ? SITE_NAME : 'ELDURATO'; ?></span>. All Rights Reserved. developed by <span class="text-white fw-medium">Hridesh</span></p>
            </div>
        </div>
    </div>
</footer>