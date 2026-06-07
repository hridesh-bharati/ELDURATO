<!-- pages\products\wishlist.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please login first'
    ]);
    exit;
}

if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product'
    ]);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$productId = (int)$_POST['product_id'];

try {

    $stmt = $pdo->prepare("
        SELECT id
        FROM wishlist
        WHERE user_id = ? AND product_id = ?
    ");

    $stmt->execute([$userId, $productId]);

    if ($stmt->fetch()) {

        $delete = $pdo->prepare("
            DELETE FROM wishlist
            WHERE user_id = ? AND product_id = ?
        ");

        $delete->execute([$userId, $productId]);

        $action = 'removed';

    } else {

        $insert = $pdo->prepare("
            INSERT INTO wishlist (user_id, product_id)
            VALUES (?, ?)
        ");

        $insert->execute([$userId, $productId]);

        $action = 'added';
    }

    $countStmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM wishlist
        WHERE user_id = ?
    ");

    $countStmt->execute([$userId]);

    $wishlistCount = (int)$countStmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'action' => $action,
        'count' => $wishlistCount
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

exit;
?>