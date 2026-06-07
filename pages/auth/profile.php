<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/cloudinary.php'; // Cloudinary config loaded

// 🔐 SECURITY CHECK: Agar login nahi hai toh login page par bhejo
if (!isset($_SESSION['user_id'])) {
    header("Location: /belt/pages/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = $_SESSION['success_msg'] ?? "";
$error_msg = $_SESSION['error_msg'] ?? "";

// Ek baar alert show hone ke baad session clean karein
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

// 1. Fetch Current User Data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: /belt/pages/auth/logout.php");
        exit;
    }
} catch (PDOException $e) {
    $error_msg = "Database error: " . $e->getMessage();
}

// 2. Handle Profile Info & Cloudinary Image Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $profile_pic = $user['profile_pic'] ?? ''; 

    // Cloudinary Image Upload Logic
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            try {
                // Cloudinary API standard v2 integration
                $uploadResult = $cloudinary->uploadApi()->upload($_FILES['profile_image']['tmp_name'], [
                    'folder' => 'belt_store/users',
                    'transformation' => [
                        ['width' => 250, 'height' => 250, 'crop' => 'fill', 'gravity' => 'face'] // Face auto-crop & resize
                    ]
                ]);
                
                $profile_pic = $uploadResult['secure_url']; // Cloudinary secure URL
            } catch (Exception $e) {
                $error_msg = "Cloudinary Upload Error: " . $e->getMessage();
            }
        } else {
            $error_msg = "Invalid image format! Only JPG, JPEG, PNG, and WEBP allowed.";
        }
    }

    if (empty($error_msg)) {
        if (empty($name) || empty($phone) || empty($email)) {
            $error_msg = "All personal detail fields are required!";
        } else {
            try {
                // Email uniqueness validation
                $email_check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $email_check->execute([$email, $user_id]);
                
                if ($email_check->rowCount() > 0) {
                    $error_msg = "This email is already in use!";
                } else {
                    $update = $pdo->prepare("UPDATE users SET name = ?, phone = ?, email = ?, profile_pic = ? WHERE id = ?");
                    if ($update->execute([$name, $phone, $email, $profile_pic, $user_id])) {
                        $_SESSION['user_name'] = $name;
                        $_SESSION['success_msg'] = "Profile updated successfully via Cloudinary!";
                        header("Location: profile.php");
                        exit;
                    }
                }
            } catch (PDOException $e) {
                $error_msg = "Update failed: " . $e->getMessage();
            }
        }
    }
}

// 3. Handle Password Change Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $error_msg = "All password fields are required!";
    } elseif ($new_pass !== $confirm_pass) {
        $error_msg = "Passwords do not match!";
    } else {
        try {
            if (password_verify($current_pass, $user['password'])) {
                $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $pass_update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                if ($pass_update->execute([$hashed_new_pass, $user_id])) {
                    $_SESSION['success_msg'] = "Password changed successfully!";
                    header("Location: profile.php");
                    exit;
                }
            } else {
                $error_msg = "Incorrect current password!";
            }
        } catch (PDOException $e) {
            $error_msg = "Password update failed!";
        }
    }
}

// Avatar check logic (Cloudinary URL directly loads if exists)
$default_avatar = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
$final_avatar = !empty($user['profile_pic']) ? $user['profile_pic'] : $default_avatar;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BELTSTORE - My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .sidebar { min-height: 100vh; background: #0f172a; position: sticky; top: 0; }
        .sidebar .logo { font-size: 26px; font-weight: 700; color: #14b8a6; margin-bottom: 20px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px; border-radius: 10px; margin-bottom: 6px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #14b8a6; color: #fff; }
        .profile-card { border: none; border-radius: 16px; background: #fff; box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        .btn-custom { background: #14b8a6; color: #fff; border: none; }
        .btn-custom:hover { background: #0f9687; color: #fff; }
        .avatar-preview { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid #14b8a6; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 p-3 sidebar">
            <div class="logo"><i class="ri-handbag-line"></i> BELTSTORE</div>
        <a href="/belt/pages/account/dashboard.php"><i class="ri-dashboard-line me-2"></i>Dashboard</a>
        <a href="../products/cart.php"><i class="ri-shopping-bag-line me-2"></i>My Cart</a>
        <a href="../account/orders.php"><i class="ri-shopping-bag-line me-2"></i>My Orders</a> 
        <a href="../products/wishlist.php"><i class="ri-heart-line me-2"></i>Wishlist</a>
        <a href="../auth/profile.php"><i class="ri-user-line me-2"></i>Profile</a> 
        <a href="../auth/logout.php" class="text-danger mt-4 d-block">
            <i class="ri-logout-circle-line me-2"></i>Logout
        </a>
    </div>
    
        <div class="col-lg-10 p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">My Profile</h3>
                <span class="badge bg-info p-2 fs-6 text-capitalize">Retailer (<?= htmlspecialchars($user['role'] ?? 'user') ?>)</span>
            </div>

            <?php if(!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i> <?= htmlspecialchars($success_msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="ri-error-warning-line me-2"></i> <?= htmlspecialchars($error_msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    
                    <div class="col-md-4 mb-4">
                        <div class="card profile-card p-4 text-center">
                            <h5 class="fw-bold mb-3">Profile Image</h5>
                            
                            <div class="mb-3">
                                <img src="<?= $final_avatar ?>" class="avatar-preview shadow-sm" id="imgPreview" alt="Avatar">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Change Picture</label>
                                <input type="file" name="profile_image" class="form-control form-control-sm" accept="image/*" id="imageInput">
                            </div>
                            <p class="text-muted small">Optimized by Cloudinary CDN</p>
                        </div>
                    </div>

                    <div class="col-md-8 mb-4">
                        <div class="card profile-card p-4">
                            <h5 class="fw-bold mb-4"><i class="ri-user-settings-line me-2 text-primary"></i>Personal Details</h5>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" pattern="[0-9]{10}" required>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-custom px-4">Save Profile Changes</button>
                        </div>
                    </div>

                </div>
            </form>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card profile-card p-4">
                        <h5 class="fw-bold mb-4"><i class="ri-lock-password-line me-2 text-danger"></i>Security & Password</h5>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted small fw-bold">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted small fw-bold">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Min 6 chars" minlength="6" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted small fw-bold">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-danger px-4 mt-2">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Live frontend image preview logic
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files
        if (file) {
            document.getElementById('imgPreview').src = URL.createObjectURL(file)
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>