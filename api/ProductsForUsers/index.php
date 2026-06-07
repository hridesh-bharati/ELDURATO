<?php
// api\ProductsForUsers\index.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM all_products_list ORDER BY id DESC LIMIT 12");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [];

    foreach($products as $product) {
        $price = isset($product['price']) ? floatval($product['price']) : 0;
        $oldPrice = isset($product['old_price']) ? floatval($product['old_price']) : 0;
        $discount = 0;

        if($oldPrice > $price) {
            $discount = round((($oldPrice - $price) / $oldPrice) * 100);
        }

        // Cloudinary या फॉलबैक इमेज
        $image = !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x300?text=No+Image';

        // साफ़-सुथरा एरे तैयार करें
        $response[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'brand' => $product['brand'] ?? 'ELDURATO',
            'price' => $price,
            'old_price' => $oldPrice,
            'discount' => $discount,
            'image' => $image,
            'description' => $product['description']
        ];
    }

    // 200 OK स्टेटस और डेटा भेजें
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $response
    ]);

} catch(PDOException $e) {
    // एरर आने पर 500 स्टेटस भेजें
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "डेटा फ़ेच करने में दिक्कत हुई: " . $e->getMessage()
    ]);
}
?>