<?php
// Include the db.php file to connect to the database
require_once('db.php');

// เขียนโค้ดในการดึงชื่อสินค้าจากตาราง product จากฐานข้อมูล
// คุณควรใช้คำสั่ง SQL เพื่อดึงข้อมูลที่ต้องการ
$stmt = $conn->prepare("SELECT id_product, product_name FROM product");
$stmt->execute();

$productData = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $productData[] = array(
        "productId" => $row["id_product"],
        "productName" => $row["product_name"]
    );
}

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($productData);
?>
