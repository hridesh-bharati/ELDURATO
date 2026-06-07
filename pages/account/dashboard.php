<?php
session_start();

/* 🔐 SECURITY CHECK */
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../../admin/index.php");
    exit;
}

require_once '../../config/database.php';
$user_id = $_SESSION['user_id'];

$orderCount = 0;
$wishlistCount = 0;
$recentOrders = [];

try {
    // ORDERS COUNT
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM all_orders_list WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $orderCount = $stmt->fetchColumn() ?? 0;

    // WISHLIST COUNT
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $wishlistCount = $stmt->fetchColumn() ?? 0;

    // RECENT ORDERS
    $stmt = $pdo->prepare("SELECT id, created_at, total_amount, order_status, payment_method FROM all_orders_list WHERE user_id = ? ORDER BY id DESC LIMIT 5");
    $stmt->execute([$user_id]);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e){
    // silent fail
}

$status_badges = [
    'pending'    => 'bg-warning text-dark',
    'processing' => 'bg-info text-dark',
    'completed'  => 'bg-success text-white',
    'cancelled'  => 'bg-danger text-white'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BELTSTORE - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: system-ui, -apple-system, sans-serif; }
        .sidebar { min-height: 100vh; background: #0f172a; position: sticky; top: 0; }
        .sidebar .logo { font-size: 24px; font-weight: 700; color: #14b8a6; margin-bottom: 25px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px; border-radius: 10px; margin-bottom: 6px; transition: 0.2s ease; }
        .sidebar a:hover, .sidebar a.active { background: #14b8a6; color: #fff; }
        .stat-card { border: none; border-radius: 16px; color: #fff; position: relative; overflow: hidden; }
        .stat-icon { font-size: 40px; position: absolute; right: 15px; bottom: 10px; opacity: 0.25; }
        .top-header { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .dashboard-card { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 p-3 sidebar d-flex flex-column justify-content-between">
            <div>
                <div class="logo class-link pt-2 px-2"><i class="ri-handbag-line"></i> BELTSTORE</div>
                <a href="dashboard.php" class="active"><i class="ri-dashboard-line me-2"></i>Dashboard</a>
                <a href="../products/cart.php"><i class="ri-shopping-cart-2-line me-2"></i>My Cart</a>
                <a href="/belt/pages/products/track_order.php"><i class="ri-shopping-bag-line me-2"></i>Track Orders</a> 
                <a href="../products/wishlist.php"><i class="ri-heart-line me-2"></i>Wishlist</a>
                <a href="../auth/profile.php"><i class="ri-user-line me-2"></i>Profile</a> 
            </div>
            <div>
                <a href="../auth/logout.php" class="text-danger border border-danger border-opacity-25 text-center mt-auto mb-2">
                    <i class="ri-logout-circle-line me-1"></i> Logout
                </a>
            </div>
        </div>

        <div class="col-lg-10 p-4">
            <div class="top-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h3>
                    <p class="text-muted small mb-0">Manage your BELTSTORE account & track orders</p>
                </div>
                <a href="../../index.php" class="btn btn-dark px-4 py-2 rounded-3 fw-semibold">
                   <i class="ri-store-line me-1"></i> Continue Shopping
                </a>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-primary p-4 shadow-sm">
                        <h6 class="text-white-50 small text-uppercase fw-bold">Total Orders</h6>
                        <h2 class="fw-bold m-0"><?= $orderCount ?></h2>
                        <i class="ri-shopping-cart-line stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-danger p-4 shadow-sm">
                        <h6 class="text-white-50 small text-uppercase fw-bold">Wishlist</h6>
                        <h2 class="fw-bold m-0"><?= $wishlistCount ?></h2>
                        <i class="ri-heart-3-line stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-success p-4 shadow-sm">
                        <h6 class="text-white-50 small text-uppercase fw-bold">Reward Points</h6>
                        <h2 class="fw-bold m-0">150</h2>
                        <i class="ri-medal-line stat-icon"></i>
                    </div>
                </div>
            </div>

            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="ri-history-line me-1 text-secondary"></i> Recent Orders</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th class="ps-3">Order ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($recentOrders) > 0): foreach($recentOrders as $order): 
                                    $curr_status = strtolower($order['order_status'] ?? 'pending');
                                    $pay_suffix = (strtoupper($order['payment_method'] ?? 'COD') == 'COD') ? 'COD' : 'ONL';
                                ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-primary">ELD-<?= $pay_suffix ?>-<?= $order['id'] ?></td>
                                        <td class="text-secondary small"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                                        <td class="fw-bold text-dark">₹<?= number_format($order['total_amount']) ?></td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold <?= $status_badges[$curr_status] ?? 'bg-secondary' ?>">
                                                <?= ucfirst($curr_status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="ri-inbox-line fs-3 d-block text-light mb-2"></i> No active orders found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>