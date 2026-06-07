<?php
require_once '../../config/database.php';
require_once '../../config/cloudinary.php'; 

include '../../includes/header.php';
include '../../includes/navbar.php';

$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = !empty($_POST['brand']) ? trim($_POST['brand']) : 'ELDURATO';
    $name = trim($_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $slug .= "-" . time();
    $price = floatval($_POST['price']);
    $old_price = !empty($_POST['old_price']) ? floatval($_POST['old_price']) : 0.00;
    $stock = intval($_POST['stock']);
    $desc = trim($_POST['description']);
    $material = trim($_POST['material']);
    $color = trim($_POST['color']);
    $warranty = trim($_POST['warranty']);
    
    // नए फ्लिपकार्ट स्पेसिफिकेशन इनपुट्स
    $model_name = trim($_POST['model_name']);
    $belt_width = trim($_POST['belt_width']);
    $weight = trim($_POST['weight']);

    $imageData = [];

    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
        try {
            foreach ($_FILES['images']['name'] as $key => $val) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    // Cloudinary पर इमेज अपलोड
                    $uploadResult = $cloudinary->uploadApi()->upload($_FILES['images']['tmp_name'][$key], [
                        'folder' => 'belt_store/products'
                    ]);
                    
                    // इमेज-स्पेसिफिक साइज हैंडलिंग
                    $associatedSizes = !empty($_POST['img_sizes'][$key]) ? trim($_POST['img_sizes'][$key]) : '28,30,32,34,36';
                    
                    $imageData[] = [
                        'url' => $uploadResult['secure_url'],
                        'sizes' => $associatedSizes
                    ];
                }
            }

            $imagesJson = json_encode($imageData);

            // फिक्स: डेटाबेस के नए अल्टर स्ट्रक्चर के साथ 100% मैचिंग प्रिपेयर्ड स्टेटमेंट
            $stmt = $pdo->prepare("INSERT INTO all_products_list (brand, name, slug, price, old_price, stock, description, material, color, warranty, images, model_name, belt_width, weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$brand, $name, $slug, $price, $old_price, $stock, $desc, $material, $color, $warranty, $imagesJson, $model_name, $belt_width, $weight]);

            $successMessage = "Product along with image-specific sizes uploaded successfully!";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger rounded-1 m-3'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<style>
    body {
        background-color: #f1f3f6 !important; /* Flipkart Soft Gray BG */
    }
    .app-card {
        background: #ffffff;
        border-radius: 12px !important;
        border: none !important;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
    }
    .app-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #212121;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }
    .app-section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 16px;
        background: #fb641b;
        margin-right: 8px;
        border-radius: 2px;
    }
    .form-control {
        border: 1px solid #dcdcdc !important;
        border-radius: 8px !important;
        padding: 10px 12px;
        font-size: 14px;
        background-color: #fafafa;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        background-color: #fff;
        border-color: #2874f0 !important; /* Flipkart App Blue Focus */
        box-shadow: none !important;
    }
    .form-label {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #666 !important;
        margin-bottom: 5px;
    }
    .app-image-row {
        border-radius: 8px !important;
        border: 1px solid #e0e0e0 !important;
        background: #f9f9f9 !important;
        padding: 10px;
    }

    /* रिस्पॉन्सिव बिहेवियर लॉजिक (Mobile vs PC View) */
    @media (max-width: 767.98px) {
        /* केवल मोबाइल के लिए सेटिंग्स */
        .app-container {
            padding-bottom: 80px; /* नीचे बटन के लिए गैप */
        }
        .app-footer-action {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 12px 16px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
            z-index: 1050;
        }
        .app-btn-submit {
            background: #fb641b !important;
            border: none !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            padding: 12px !important;
            border-radius: 8px !important;
            width: 100%;
            letter-spacing: 0.5px;
        }
    }

    @media (min-width: 768px) {
        /* पीसी / वाइड मोड के लिए सेटिंग्स */
        .app-container {
            max-width: 1000px !important; /* डेस्कटॉप पर क्लीन वाइड लेआउट */
            padding-top: 30px;
        }
        .pc-submit-wrapper {
            display: block !important;
            text-align: right;
            margin-top: 10px;
        }
        .app-btn-submit {
            background: #fb641b !important;
            border: none !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            padding: 12px 35px !important;
            border-radius: 8px !important;
            display: inline-block !important;
            width: auto !important;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(251,100,27,0.3);
        }
        /* मोबाइल वाला स्टिकी बार पीसी पर छुपाएं */
        .app-footer-action {
            display: none !important;
        }
    }
</style>

<div class="container app-container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <div>
            <span class="text-muted small d-block">Console Admin Panel</span>
            <h5 class="fw-bold text-dark mb-0" style="font-size: 20px;">Add New Product</h5>
        </div>
        <a href="http://localhost/belt/admin/products/index.php" class="btn btn-white btn-sm rounded-pill fw-bold px-3 border bg-white text-dark shadow-sm" style="font-size: 13px;">
            <i class="bi bi-grid-3x3-gap-fill me-1"></i> View All Products
        </a>
    </div>

    <?php if(!empty($successMessage)): ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" style="background-color: #148a56; color: #fff; border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.1rem;"></i>
            <div class="small fw-semibold"><?= $successMessage ?></div>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="row">
            
            <div class="col-md-6">
                <div class="card app-card p-3">
                    <h6 class="app-section-title">General Information</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Brand Name</label>
                            <select name="brand" class="form-control">
                                <option value="ELDURATO">ELDURATO</option>
                                <option value="LEATHER KING">LEATHER KING</option>
                                <option value="PREMIUM BELTS">PREMIUM BELTS</option>
                            </select>                      
                          </div>
                        <div class="col-12">
                            <label class="form-label">Product Title</label>
                            <input type="text" name="name" class="form-control" placeholder="Men Genuine Leather Belt Metal Buckle" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Selling Price (INR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="₹0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">MRP / Old Price</label>
                            <input type="number" step="0.01" name="old_price" class="form-control" placeholder="₹0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" placeholder="Available stock units" required>
                        </div>
                    </div>
                </div>

                <div class="card app-card p-3">
                    <h6 class="app-section-title">Product Images & Sizes</h6>
                    <div class="mb-2">
                        <label class="form-label">Select Multiple Images</label>
                        <input type="file" id="imageSelector" name="images[]" class="form-control" accept="image/*" multiple required style="background: #fff;">
                        
                        <div id="imageSizesContainer" class="row g-2 mt-2"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card app-card p-3">
                    <h6 class="app-section-title">ELDURATO Specifications</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Model Name</label>
<select name="model_name" class="form-control">
    <option value="Classic Leather Belt">Classic Leather Belt</option>
    <option value="Formal Leather Belt">Formal Leather Belt</option>
    <option value="Casual Leather Belt">Casual Leather Belt</option>
    <option value="Automatic Buckle Belt">Automatic Buckle Belt</option>
    <option value="Reversible Belt">Reversible Belt</option>
    <option value="Premium Leather Belt">Premium Leather Belt</option>
</select>                        </div>
                        <div class="col-12">
                            <label class="form-label">Material</label>
                            <select name="material" class="form-control">
                                <option value="Genuine Leather">Genuine Leather</option>
                                <option value="Full Grain Leather">Full Grain Leather</option>
                                <option value="Pull Grain Leather">Pull Grain Leather</option>
                                <option value="Synthetic Leather">Synthetic Leather</option>
                                <option value="PU Leather">PU Leather</option>
                            </select>                        </div>     
                        <div class="col-6">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control" placeholder="e.g. Black">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Belt Width</label>
                            <input type="text" name="belt_width" class="form-control" placeholder="e.g. 1.5 inches">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Weight (g)</label>
                            <input type="text" name="weight" class="form-control" placeholder="e.g. 300 g">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Warranty Details</label>
                            <input type="text" name="warranty" class="form-control" placeholder="e.g. 6 Months Warranty Assurance">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description / Other Details</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Write full description here..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="pc-submit-wrapper d-none">
                    <button type="submit" class="btn app-btn-submit text-uppercase">Publish Product</button>
                </div>
            </div>

        </div>

        <div class="app-footer-action">
            <div class="container" style="max-width: 500px; padding: 0;">
                <button type="submit" class="btn app-btn-submit text-uppercase">Publish Product</button>
            </div>
        </div>
        
    </form>
</div>

<script>
// लाइव क्लाइंट-साइड रेंडरिंग स्क्रिप्ट (बिना किसी लॉजिक बदलाव के)
document.getElementById('imageSelector').addEventListener('change', function(e) {
    const container = document.getElementById('imageSizesContainer');
    container.innerHTML = ''; 
    
    Array.from(this.files).forEach((file, index) => {
        const row = document.createElement('div');
        row.className = 'col-12 d-flex align-items-center gap-3 app-image-row';
        row.innerHTML = `
            <div style="width: 55px; height: 55px; min-width: 55px;" class="bg-white border rounded p-1">
                <img src="${URL.createObjectURL(file)}" class="w-100 h-100" style="object-fit: contain;">
            </div>
            <div class="flex-grow-1">
                <label class="form-label d-block mb-1" style="font-size: 11px !important;">Available Waist Sizes (Img ${index + 1})</label>
                <input type="text" name="img_sizes[]" class="form-control form-control-sm" style="padding: 6px 10px; font-size: 13px;" placeholder="e.g. 28, 30, 32, 34" value="28, 30, 32, 34, 36, 38" required>
            </div>
        `;
        container.appendChild(row);
    });
});
</script>

<?php include '../../includes/footer.php'; ?>