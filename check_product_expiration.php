<?php
// เชื่อมต่อฐานข้อมูล
require_once('db.php');

// ดึงข้อมูลล็อตสินค้าที่ใกล้หมดอายุภายในสามวัน
$sql = "SELECT COUNT(*) AS expiring_lots
        FROM lot
        WHERE DATEDIFF(lot_expiration, CURDATE()) <= 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ส่งข้อมูลกลับในรูปแบบ JSON
header('Content-Type: application/json');
$response = array("expiring_lots" => $result['expiring_lots']);
echo json_encode($response);
?>
