<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_lot = $_POST['id_lot'];
    $lot_date = $_POST['lot_date'];
    $lot_number = $_POST['lot_number'];
    $lot_cost = $_POST['lot_cost'];
    $lot_expiration = $_POST['lot_expiration'];

    try {
        $stmt = $conn->prepare("UPDATE lot SET lot_date = :lot_date, lot_number = :lot_number, lot_cost = :lot_cost, lot_expiration = :lot_expiration WHERE id_lot = :id_lot");
        $stmt->bindParam(':lot_date', $lot_date);
        $stmt->bindParam(':lot_number', $lot_number);
        $stmt->bindParam(':lot_cost', $lot_cost);
        $stmt->bindParam(':lot_expiration', $lot_expiration);
        $stmt->bindParam(':id_lot', $id_lot);

        $stmt->execute();

        // ส่วนที่เพิ่มขึ้นมา: แสดง Popup บันทึกสำเร็จ
        echo "<script>";
        echo "alert('บันทึกสำเร็จ');";
        echo "window.location.href = 'stock_product.php';";
        echo "</script>";
    } catch (PDOException $e) {
        echo "Error updating lot data: " . $e->getMessage();
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn = null;
?>

