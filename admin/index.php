<?php
session_start();
require_once '../config/database.php';

$totalSales = $totalOrders = $totalProducts = $totalUsers = 0;
$months = $sales = $recentOrders = $lowStockProducts = [];

// 📆 हर दिन के अलग कलर के लिए नाम और कलर्स का मैप
$dayLabels = [];
$daySales = [];
$dayColors = ['#8b5cf6', '#06b6d4', '#f97316', '#ec4899', '#10b981', '#3b82f6', '#ef4444'];

try {
    // 1. Live Counters Analytics
    $totalSales    = $pdo->query("SELECT SUM(total_amount) FROM all_orders_list")->fetchColumn() ?? 0;
    $totalOrders   = $pdo->query("SELECT COUNT(*) FROM all_orders_list")->fetchColumn() ?? 0;
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM all_products_list")->fetchColumn() ?? 0;
    $totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;

    // 2. Line Chart Engine Data (Monthly Trend)
    $chartData = $pdo->query("SELECT DATE_FORMAT(created_at, '%b') as m, SUM(total_amount) as t FROM all_orders_list GROUP BY MONTH(created_at), m ORDER BY MONTH(created_at) ASC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
    foreach($chartData as $row) {
        $months[] = $row['m'];
        $sales[]  = (float)$row['t'];
    }

    // 3. 📆 Every Day Sales Breakup
    $dailyQuery = $pdo->query("
        SELECT DATE_FORMAT(created_at, '%a') as day_name, SUM(total_amount) as total 
        FROM all_orders_list 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at), DATE_FORMAT(created_at, '%a')
        ORDER BY DATE(created_at) ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach($dailyQuery as $d) {
        $dayLabels[] = $d['day_name'];
        $daySales[]  = (float)$d['total'];
    }

    if(empty($daySales)) {
        $dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $daySales  = [1200, 1900, 800, 2500, 1400, 1800, 2100];
    }

    // 4. Recent Orders List
    $recentOrders = $pdo->query("SELECT o.id, o.total_amount, o.payment_method, o.order_status, COALESCE(u.name, o.customer_name, 'Guest Customer') as customer_real_name FROM all_orders_list o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

    // 5. Low Stock Alert
    $lowStockProducts = $pdo->query("SELECT id, name as product_title, stock FROM all_products_list WHERE stock <= 5 ORDER BY stock ASC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) { 
    $error = $e->getMessage(); 
}

if (empty($months)) { $months = ['Jun']; $sales = [$totalSales]; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELDURATO - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f0f4f9; font-family: 'Inter', system-ui, sans-serif; }
        
        /* 🎨 कलरफुल प्रीमियम ग्रेडिएंट साइडबार */
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #4f46e5 0%, #3730a3 100%); 
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .nav-link-custom { 
            color: rgba(255, 255, 255, 0.75); 
            padding: 12px 18px; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 14.5px; 
            display: flex; 
            align-items: center; 
            text-decoration: none; 
            transition: all 0.2s; 
            margin-bottom: 4px; 
        }
        .nav-link-custom i { font-size: 1.2rem; margin-right: 12px; color: rgba(255, 255, 255, 0.6); }
        
        /* होवर और एक्टिव स्टेट्स */
        .nav-link-custom:hover, .nav-link-custom.active { 
            background: rgba(255, 255, 255, 0.15); 
            color: #ffffff; 
        }
        .nav-link-custom:hover i, .nav-link-custom.active i { color: #fbbf24; }
        
        /* 🎨 कलरफुल ग्रेडिएंट कार्ड्स */
        .header-console { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 12px; }
        .card-custom { border: none; border-radius: 12px; color: white; position: relative; overflow: hidden; min-height: 115px; }
        .card-custom .watermark-icon { position: absolute; right: -10px; bottom: -15px; font-size: 5rem; opacity: 0.15; transform: rotate(-10deg); }
        
        .grad-purple { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }
        .grad-blue { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
        .grad-orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .grad-pink { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
        
        /* 🟢 लाइव पल्स एनीमेशन */
        .pulse-dot { width: 8px; height: 8px; background-color: #fbbf24; border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(0.9); opacity: 0.7; } 50% { transform: scale(1.3); opacity: 1; } 100% { transform: scale(0.9); opacity: 0.7; } }
        
        .chart-header { background: #4f46e5; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 10px 15px; font-weight: 600; }
        .chart-card { border-radius: 12px; border: none; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-2 p-3 sidebar position-fixed d-none d-lg-block h-100" style="z-index: 1000;">
            <div class="fs-4 fw-bold p-2 text-center border-bottom border-white border-opacity-10 mb-4 text-nowrap" style="letter-spacing: -1px; color:#ffffff;">
                <i class="ri-shield-flash-fill text-warning me-1"></i> ELDURATO
            </div>
            <div class="d-flex flex-column">
                <a href="index.php" class="nav-link-custom active"><i class="ri-dashboard-3-line"></i>Dashboard</a>
                <a href="products/index.php" class="nav-link-custom"><i class="ri-handbag-line"></i>Products</a>
                <a href="orders/index.php" class="nav-link-custom"><i class="ri-shopping-bag-line"></i>Orders</a>
                <a href="users/index.php" class="nav-link-custom"><i class="ri-user-settings-line"></i>Users</a>
               
                <hr class="border-white border-opacity-20 my-3">
                <a href="/belt/pages/auth/logout.php" class="nav-link-custom text-white bg-danger"><i class="ri-logout-circle-line me-2"></i>Logout</a>
            </div>
        </div>

        <div class="offcanvas offcanvas-start sidebar" tabindex="-1" id="mobileSidebar" style="width: 260px;">
            <div class="offcanvas-header border-bottom border-white border-opacity-10">
                <h5 class="offcanvas-title fw-bold text-white"><i class="ri-shield-flash-fill text-warning me-2"></i>ELDURATO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-3">
                <div class="d-flex flex-column">
                    <a href="index.php" class="nav-link-custom active"><i class="ri-dashboard-3-line"></i>Dashboard</a>
                    <a href="products/index.php" class="nav-link-custom"><i class="ri-handbag-line"></i>Products</a>
                    <a href="orders/index.php" class="nav-link-custom"><i class="ri-shopping-bag-line"></i>Orders</a>
                    <a href="users/index.php" class="nav-link-custom"><i class="ri-user-settings-line"></i>Users</a>
                    <hr class="border-white border-opacity-20 my-3">
                    <a href="../index.php" class="nav-link-custom text-white bg-white bg-opacity-10"><i class="ri-store-2-line text-white"></i>View Shop</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10 p-2 offset-lg-2">
            
            <div class="header-console p-3 text-white shadow-sm d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-light d-lg-none py-1 px-2 me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="ri-menu-2-line text-dark fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold m-0 d-inline-block align-middle">Admin Console</h5>
                        <div class="opacity-75 fs-7"><?= date('M d, Y') ?></div>
                    </div>
                </div>
                 <a href="../index.php" class="nav-link-custom"><i class="ri-store-2-line text-white"></i>View Shop</a>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold small d-flex align-items-center gap-2 shadow-sm">
                    <span class="pulse-dot"></span> SYSTEM LIVE
                </span>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-custom grad-purple p-3 shadow-sm">
                        <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Total Revenue</h6>
                        <h2 class="fw-bold m-0">₹<?= number_format($totalSales) ?></h2>
                        <i class="ri-wallet-3-fill watermark-icon"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom grad-blue p-3 shadow-sm">
                        <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Orders Placed</h6>
                        <h2 class="fw-bold m-0"><?= $totalOrders ?></h2>
                        <i class="ri-shopping-cart-2-fill watermark-icon"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom grad-orange p-3 shadow-sm">
                        <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Active Belts</h6>
                        <h2 class="fw-bold m-0"><?= $totalProducts ?></h2>
                        <i class="ri-equalizer-fill watermark-icon"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom grad-pink p-3 shadow-sm">
                        <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Registered Users</h6>
                        <h2 class="fw-bold m-0"><?= $totalUsers ?></h2>
                        <i class="ri-group-fill watermark-icon"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4 m-0">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0 text-secondary"><i class="ri-file-list-3-line text-success me-1"></i> Recent Orders Flow</h6>
                            <a href="orders/index.php" class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size:11px;">View All</a>
                        </div>
                        <div class="table-responsive border-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-muted text-uppercase">
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Cash</th>
                                        <th>Gateway</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($recentOrders)): foreach($recentOrders as $order): 
                                        $status = $order['order_status'] ?? 'Pending';
                                        $badge = (strtolower($status) === 'completed' || strtolower($status) === 'delivered') ? 'bg-success text-white' : ((strtolower($status) === 'cancelled') ? 'bg-danger text-white' : 'bg-warning text-dark');
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-primary">#ELD-<?= $order['id'] ?></td>
                                        <td class="fw-medium text-dark"><?= htmlspecialchars($order['customer_real_name']) ?></td>
                                        <td class="fw-bold">₹<?= number_format($order['total_amount']) ?></td>
                                        <td><span class="badge bg-light text-dark border text-uppercase" style="font-size:10px;"><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></span></td>
                                        <td><span class="badge <?= $badge ?> rounded-pill px-2.5 py-1" style="font-size:11px;"><?= ucfirst($status) ?></span></td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No entries found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card chart-card bg-white shadow-sm overflow-hidden h-100">
                        <div class="chart-header" style="background: #0ea5e9;"><i class="ri-pie-chart-2-line me-2"></i>Daily Sales Split (7 Days)</div>
                        <div class="p-3 d-flex justify-content-center align-items-center" style="height: 280px;"><canvas id="dailySalesPieChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-4 p-0 m-0">
                <div class="col-lg-8">
                    <div class="card chart-card bg-white shadow-sm overflow-hidden h-100">
                        <div class="chart-header"><i class="ri-line-chart-line me-2"></i>Monthly Revenue Performance</div>
                        <div class="p-3" style="height: 280px;"><canvas id="salesChart"></canvas></div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3 h-100">
                        <h6 class="fw-bold mb-3 text-danger"><i class="ri-alarm-warning-line me-1"></i> Inventory Alerts</h6>
                        <ul class="list-group list-group-flush">
                            <?php if(!empty($lowStockProducts)): foreach($lowStockProducts as $prod): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2.5 border-light-subtle">
                                    <span class="text-dark fw-medium small text-truncate" style="max-width: 180px;"><?= htmlspecialchars($prod['product_title']) ?></span>
                                    <span class="badge bg-danger text-white rounded-pill" style="font-size:11px;"><?= $prod['stock'] ?> left</span>
                                </li>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted py-5">
                                    <i class="ri-checkbox-circle-fill fs-2 text-success d-block mb-1"></i>
                                    <small class="fw-medium">Stock levels healthy!</small>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// 1. Line Chart
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            data: <?= json_encode($sales) ?>,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.08)',
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#4f46e5'
        }]
    },
    options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false, scales: { y: { grid: { color: '#f1f3f5' } }, x: { grid: { display: false } } } }
});

// 2. Everyday 7 Colors Doughnut Chart
new Chart(document.getElementById('dailySalesPieChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($dayLabels) ?>,
        datasets: [{
            data: <?= json_encode($daySales) ?>,
            backgroundColor: <?= json_encode($dayColors) ?>,
            borderWidth: 2,
            hoverOffset: 6
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 15, font: { size: 11, weight: 'bold' } } } }, cutout: '65%' }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>