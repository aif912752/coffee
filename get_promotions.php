<?php
require_once 'db.php';

try {
    $query = "SELECT * FROM promotion";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($promotions as &$promotion) {
        // ดึงรหัสสินค้าที่ร่วมรายการและแปลงเป็นอาร์เรย์
        $promotion['participating_products'] = explode(',', $promotion['participating_products']);
    }

    $conn = null;

    header('Content-Type: application/json');
    echo json_encode($promotions);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
