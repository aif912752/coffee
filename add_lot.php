<?php
require_once 'db.php';

function calculateRemainingQuantity($conn, $id_product) {

    // ดึงจำนวนทั้งหมดของล็อตที่เพิ่มเข้ามา
    $sql = "SELECT SUM(lot_number) AS total_lot_number FROM lot WHERE id_product = :id_product GROUP BY id_product";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_product', $id_product);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_lot_number = $row['total_lot_number'];

     // ตรวจสอบว่าค่าที่คืนมาเป็น boolean false หรือไม่
     if ($row === false) {
        return false; // คืนค่าเป็น boolean false
    }
    $total_lot_number = $row['total_lot_number'];

    // คำนวณจำนวนคงเหลือใหม่
    return $total_lot_number;

}

function updateRemainingQuantity($conn, $id_product, $remaining_quantity) {
    // $sql = "UPDATE product SET remaining = :remaining WHERE id_product = :id_product";
    // $stmt = $conn->prepare($sql);
    // $stmt->bindParam(':remaining', $remaining_quantity);
    // $stmt->bindParam(':id_product', $id_product);
    // $stmt->execute();
}

// เมื่อผู้ใช้กดปุ่ม Submit
if (isset($_POST['submit'])) {
    // รับค่าจากฟอร์ม
    $id_product = $_POST['product']; // รหัสสินค้า
    $lot_date = $_POST['lot_date']; // วันที่นำเข้าสินค้า
    $lot_number = $_POST['lot_number']; // จำนวนทั้งหมด
    $lot_cost = $_POST['lot_cost']; // ต้นทุนสินค้าที่รับเข้ามา
    $lot_expiration = $_POST['lot_expiration']; // วันหมดอายุสินค้า

    // เพิ่มข้อมูลล็อตสินค้าลงในตาราง lot
    try {
        $sql = "INSERT INTO lot (id_product, lot_date, lot_number, lot_cost, lot_expiration) 
                VALUES (:id_product, :lot_date, :lot_number, :lot_cost, :lot_expiration)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_product', $id_product);
        $stmt->bindParam(':lot_date', $lot_date);
        $stmt->bindParam(':lot_number', $lot_number);
        $stmt->bindParam(':lot_cost', $lot_cost);
        $stmt->bindParam(':lot_expiration', $lot_expiration);
        $stmt->execute();

        // คำนวณและอัพเดตจำนวนคงเหลือใหม่
        $total_lot_number = calculateRemainingQuantity($conn, $id_product);
        updateRemainingQuantity($conn, $id_product, $total_lot_number);

        // แสดงป๊อปอัปเด้ง
        echo "<script>
            alert('เพิ่มล็อตสินค้าเรียบร้อยแล้ว');
            window.location.href = 'stock_product.php';
        </script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

