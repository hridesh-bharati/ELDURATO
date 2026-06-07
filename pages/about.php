<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>About Us · Eldurato Premium Belts | Our Story & Craft</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #c9922a;
            --gold-light: #e8b84b;
            --dark: #0b1c33;
            --dark-2: #122540;
            --cream: #faf7f2;
            --muted: #7a8694;
            --eldurato-gold: #c98d4e;
            --eldurato-dark: #1e2b3c;
            --eldurato-soft: #f9f7f4;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: #333;
            background: #fff;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* ========== HEADER / NAVBAR STYLES ========== */
        .navbar {
            background-color: var(--dark) !important;
            border-bottom: 1px solid rgba(201,146,42,0.15);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            letter-spacing: 1px;
        }
        .navbar .nav-link {
            font-size: 0.9rem;
            font-weight: 400;
            letter-spacing: 0.5px;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }
        .navbar .nav-link:hover {
            color: var(--gold-light) !important;
        }
        .navbar .nav-link.active {
            color: var(--gold-light) !important;
            font-weight: 600;
        }
        .btn-outline-gold {
            border: 1.5px solid var(--gold);
            color: var(--gold-light);
            background: transparent;
            transition: all 0.3s;
        }
        .btn-outline-gold:hover {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }

        /* ========== HERO SECTION ========== */
        .about-hero {
            position: relative;
            min-height: 450px;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(11,28,51,0.94) 55%, rgba(201,146,42,0.2) 100%),
                        url('/belt/assets/images/about.png') center/cover no-repeat;
            color: #fff;
            overflow: hidden;
        }
        .about-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 40px,
                rgba(255,255,255,0.012) 40px,
                rgba(255,255,255,0.012) 41px
            );
        }
        .hero-badge {
            display: inline-block;
            border: 1px solid var(--gold);
            color: var(--gold-light);
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 2px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .about-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            letter-spacing: 1.5px;
            line-height: 1.15;
            margin-bottom: 10px;
        }
        .about-hero h1 em {
            font-style: italic;
            color: var(--gold-light);
        }
        .hero-sub {
            color: rgba(255,255,255,.7);
            font-size: 1.05rem;
            font-weight: 300;
            letter-spacing: .8px;
            line-height: 1.6;
        }
        .gold-line {
            width: 60px; 
            height: 3px;
            background: var(--gold);
            margin: 22px 0;
        }

        /* ========== SECTION TITLES ========== */
        .section-eyebrow {
            font-size: 0.7rem;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 700;
            color: var(--dark);
            line-height: 1.3;
            margin-bottom: 20px;
        }

        /* ========== STORY SECTION ========== */
        .story-section {
            padding: 80px 0;
        }
        .story-body {
            color: #555;
            line-height: 1.85;
            font-size: 0.98rem;
        }
        .story-img-wrap {
            position: relative;
            display: inline-block;
        }
        .story-img-wrap img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        .story-img-wrap::after {
            content: '';
            position: absolute;
            top: 18px; 
            left: 18px;
            right: -18px; 
            bottom: -18px;
            border: 2px solid var(--gold);
            border-radius: 8px;
            z-index: -1;
        }
        .highlight-stat {
            border-left: 3px solid var(--gold);
            padding-left: 18px;
        }
        .highlight-stat .num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }
        .highlight-stat .label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ========== TRUST BADGES ========== */
        .trust-section {
            background: var(--cream);
            padding: 64px 0;
        }
        .trust-card {
            background: #fff;
            border: 1px solid #ece9e3;
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            transition: all .3s ease;
            height: 100%;
        }
        .trust-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,.08);
            border-color: var(--gold);
        }
        .trust-card .icon-wrap {
            width: 56px; 
            height: 56px;
            border-radius: 50%;
            background: rgba(201,146,42,.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .trust-card i { 
            color: var(--gold); 
            font-size: 1.4rem; 
        }
        .trust-card h5 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .trust-card p {
            font-size: 0.83rem;
            color: var(--muted);
            margin: 0;
        }

        /* ========== MISSION CARDS ========== */
        .mission-section {
            padding: 80px 0;
            background: #fff;
        }
        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.06);
            background: #fff;
            border-radius: 12px;
            padding: 35px 25px;
            text-align: center;
            height: 100%;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.15);
            border-color: var(--gold);
        }
        .feature-icon {
            font-size: 2.2rem;
            color: var(--gold);
            margin-bottom: 18px;
        }

        /* ========== QUOTE SECTION ========== */
        .quote-section {
            padding: 80px 0;
            background: var(--dark);
            position: relative;
            overflow: hidden;
        }
        .quote-section::before {
            content: '"';
            position: absolute;
            font-family: 'Playfair Display', serif;
            font-size: 28rem;
            color: rgba(255,255,255,.025);
            top: -80px; 
            left: -30px;
            line-height: 1;
            pointer-events: none;
        }
        .quote-text {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(1.3rem, 2.8vw, 1.9rem);
            color: #fff;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }
        .quote-author {
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold-light);
            margin-top: 22px;
            position: relative;
            z-index: 1;
        }
        .quote-divider {
            width: 35px; 
            height: 2px;
            background: var(--gold);
            display: inline-block;
            vertical-align: middle;
            margin: 0 12px;
        }

        /* ========== VALUES SECTION ========== */
        .values-section {
            padding: 80px 0;
            background: #fff;
        }
        .value-item {
            display: flex;
            gap: 22px;
            margin-bottom: 38px;
            align-items: flex-start;
        }
        .value-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: rgba(201,146,42,.18);
            font-weight: 700;
            line-height: 1;
            min-width: 60px;
        }
        .value-content h5 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 1.05rem;
        }
        .value-content p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.75;
            margin: 0;
        }

        /* ========== FOUNDER SECTION ========== */
        .founder-section {
            padding: 80px 0;
            background: var(--cream);
        }

        /* ========== TRUST BAR (dark) ========== */
        .trust-bar {
            background: var(--dark);
            padding: 50px 0;
        }

        /* ========== FOOTER ========== */
        footer {
            background: var(--dark);
            color: #8a9ab0;
            font-size: .88rem;
            padding: 56px 0 0;
        }
        footer h5 {
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 22px;
        }
        footer a { 
            color: #8a9ab0; 
            text-decoration: none; 
            transition: color .2s; 
        }
        footer a:hover { 
            color: var(--gold-light) !important; 
        }
        footer .list-unstyled li { 
            margin-bottom: 10px; 
        }
        .social-icon {
            width: 38px; 
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            margin-right: 10px;
            transition: all .3s;
            font-size: .9rem;
        }
        .social-icon:hover { 
            background: var(--gold) !important; 
            color: #fff !important;
            transform: translateY(-2px);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 20px 0;
            margin-top: 50px;
            font-size: .8rem;
            color: #5a6878;
        }
        .brand-logo-footer {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #fff;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .brand-logo-footer span { 
            color: var(--gold); 
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .story-section,
            .mission-section,
            .values-section,
            .founder-section {
                padding: 50px 0;
            }
            .story-img-wrap::after {
                display: none;
            }
            .story-img-wrap img {
                height: 300px;
            }
            .about-hero {
                min-height: 350px;
            }
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand fw-bold" href="../../index.php">
                    <i class="fa-solid fa-crown text-warning me-2"></i>ELDURATO
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                        aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="about.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="collections.php">Collections</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-gold btn-sm rounded-pill px-3" href="cart.php">
                                <i class="fa-solid fa-bag-shopping me-1"></i> Cart (0)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="about-hero">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-badge">Est. 2024 &nbsp;·&nbsp; Premium Leather</div>
                    <h1>The Art of the<br><em>Perfect Belt.</em></h1>
                    <div class="gold-line"></div>
                    <p class="hero-sub">Crafting statements of character, confidence, and timeless excellence — where heritage meets modern luxury.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="story-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <div class="story-img-wrap">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800&auto=format&fit=crop" 
                             alt="Eldurato Luxury Craftsmanship">
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <p class="section-eyebrow">Our Story</p>
                    <h2 class="section-title">Welcome to <span style="color:var(--gold)">Eldurato</span></h2>
                    <p class="story-body mb-3">
                        Eldurato is a premium luxury belt brand inspired by timeless craftsmanship and old-school elegance. We create high-quality belts for individuals who appreciate sophistication, durability, and refined style.
                    </p>
                    <p class="story-body mb-4">
                        Every Eldurato belt is designed with meticulous attention to detail, combining classic aesthetics with modern comfort. Our commitment is to deliver products that represent confidence, heritage, and luxury — making every belt more than an accessory. It is a statement of character and excellence.
                    </p>
                    <div class="row g-4 mt-2">
                        <div class="col-4">
                            <div class="highlight-stat">
                                <div class="num">500+</div>
                                <div class="label">Happy Customers</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="highlight-stat">
                                <div class="num">100%</div>
                                <div class="label">Genuine Leather</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="highlight-stat">
                                <div class="num">7-Day</div>
                                <div class="label">Easy Return</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-eyebrow">Why Choose Us</p>
                <h2 class="section-title">The Eldurato Promise</h2>
            </div>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="trust-card">
                        <div class="icon-wrap"><i class="fa-solid fa-truck-fast"></i></div>
                        <h5>Free Shipping</h5>
                        <p>On all premium orders across India</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-card">
                        <div class="icon-wrap"><i class="fa-solid fa-rotate-left"></i></div>
                        <h5>7 Days Replacement</h5>
                        <p>No questions asked return policy</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-card">
                        <div class="icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
                        <h5>100% Original Leather</h5>
                        <p>Certified pure heritage materials</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-card">
                        <div class="icon-wrap"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                        <h5>Cash on Delivery</h5>
                        <p>COD available pan-India</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-eyebrow">What Sets Us Apart</p>
                <h2 class="section-title">The Eldurato <span style="color:var(--gold)">Difference</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Four pillars that define every belt we craft — from leather selection to final packaging.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card-hover">
                        <div class="feature-icon"><i class="fa-solid fa-gem"></i></div>
                        <h5 class="fw-bold mb-2">Premium Materials</h5>
                        <p class="text-secondary small mb-0">Full‑grain vegetable‑tanned leather from Tuscany, chosen for patina and strength.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card-hover">
                        <div class="feature-icon"><i class="fa-solid fa-hand-peace"></i></div>
                        <h5 class="fw-bold mb-2">Handcrafted</h5>
                        <p class="text-secondary small mb-0">Each stitch is done by skilled artisans with over 20 years of leather mastery.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card-hover">
                        <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
                        <h5 class="fw-bold mb-2">Lifetime Buckle</h5>
                        <p class="text-secondary small mb-0">Solid brass & stainless steel with anti‑scratch PVD coating. Guaranteed never to fade.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card-hover">
                        <div class="feature-icon"><i class="fa-solid fa-recycle"></i></div>
                        <h5 class="fw-bold mb-2">Sustainable Luxury</h5>
                        <p class="text-secondary small mb-0">Zero‑waste cutting, vegetable dyes, and plastic‑free packaging for a greener planet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="quote-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <i class="fa-solid fa-quote-left fa-2x mb-4" style="color:var(--gold); opacity:.7;"></i>
                    <p class="quote-text">
                        "A belt isn't just to hold your trousers up — it defines the waistline of your absolute confidence."
                    </p>
                    <p class="quote-author">
                        <span class="quote-divider"></span>
                        Eldurato Elite Philosophy
                        <span class="quote-divider"></span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="values-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <p class="section-eyebrow">What Drives Us</p>
                    <h2 class="section-title mb-4">Our Core Values</h2>
                    <p class="story-body">
                        At Eldurato, every stitch reflects a philosophy. These are the principles that guide every belt we craft and every interaction we have with our customers.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="value-item">
                        <div class="value-num">01</div>
                        <div class="value-content">
                            <h5>Uncompromising Quality</h5>
                            <p>We source only the finest full-grain leather and hardware. Every belt passes rigorous quality checks before reaching you.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-num">02</div>
                        <div class="value-content">
                            <h5>Timeless Craftsmanship</h5>
                            <p>Inspired by old-school heritage techniques, each piece is crafted to last for years — not seasons.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-num">03</div>
                        <div class="value-content">
                            <h5>Customer First, Always</h5>
                            <p>From seamless ordering to hassle-free returns, our focus is entirely on making your experience exceptional.</p>
                        </div>
                    </div>
                    <div class="value-item mb-0">
                        <div class="value-num">04</div>
                        <div class="value-content">
                            <h5>Honest Pricing</h5>
                            <p>Luxury doesn't have to break the bank. We offer genuine premium products at fair, transparent prices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="founder-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800&auto=format&fit=crop" 
                         alt="Founder & Master Craftsman" 
                         class="img-fluid rounded-4 shadow" 
                         style="object-fit: cover; width: 100%; max-height: 450px;">
                </div>
                <div class="col-lg-7">
                    <p class="section-eyebrow">Meet the founder</p>
                    <h2 class="section-title">Alessandro Rinaldi</h2>
                    <p class="text-secondary fst-italic fs-5">"A belt should never be an afterthought — it's the bridge between your posture and your presence."</p>
                    <p class="text-secondary mt-3">Alessandro learned leathercraft in Florence and founded Eldurato to bring Italian elegance to everyday wear. His obsession with detail drives every collection, ensuring each belt feels as luxurious as it looks.</p>
                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <span class="badge bg-light text-dark p-2 px-3"><i class="fa-solid fa-award text-warning me-1"></i> Master Leathersmith</span>
                        <span class="badge bg-light text-dark p-2 px-3"><i class="fa-solid fa-globe text-warning me-1"></i> Florence · Milan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-bar text-white">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <i class="fa-solid fa-truck-fast fs-2 text-warning mb-2"></i>
                        <h6 class="fw-bold text-uppercase small mb-1">Free Shipping</h6>
                        <p class="small opacity-75 mb-0">On all premium orders</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <i class="fa-solid fa-rotate-left fs-2 text-warning mb-2"></i>
                        <h6 class="fw-bold text-uppercase small mb-1">7 Days Replacement</h6>
                        <p class="small opacity-75 mb-0">No questions asked policy</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <i class="fa-solid fa-shield-halved fs-2 text-warning mb-2"></i>
                        <h6 class="fw-bold text-uppercase small mb-1">100% Original Leather</h6>
                        <p class="small opacity-75 mb-0">Certified pure heritage materials</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <i class="fa-solid fa-indian-rupee-sign fs-2 text-warning mb-2"></i>
                        <h6 class="fw-bold text-uppercase small mb-1">Cash on Delivery (COD)</h6>
                        <p class="small opacity-75 mb-0">Available across India</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>