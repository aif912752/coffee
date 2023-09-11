<?php
// เชื่อมต่อฐานข้อมูล
require_once('db.php');

// ดึงข้อมูลสินค้าที่ใกล้หมดอายุภายในสามวัน
$sql = "SELECT COUNT(*) AS expiring_products, p.product_name
        FROM lot l
        JOIN product p ON l.id_product = p.id_product
        WHERE DATEDIFF(l.lot_expiration, CURDATE()) <= 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ส่งข้อมูลกลับในรูปแบบ JSON
header('Content-Type: application/json');
$response = array("alert" => $result['expiring_products'] > 0, "productName" => $result['product_name']);
echo json_encode($response);
?>
