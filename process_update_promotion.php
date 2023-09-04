<?php

require_once('db.php');

if (isset($_POST['submit'])) {
    $id_promotion = $_POST['id_promotion'];
    $promotion_name = $_POST['promotion_name'];
    $discount_amount = $_POST['discount_amount'];
    $discount_type = $_POST['discount_type'];
    $promotion_date = $_POST['promotion_date'];
    $promotion_end = $_POST['promotion_end'];
    $participating_products = implode(',', $_POST['participating_products']); // Convert array to comma-separated string

    try {
        $stmt = $conn->prepare("UPDATE promotion SET promotion_name = :promotion_name, discount_amount = :discount_amount, discount_type = :discount_type,    promotion_date = :promotion_date, promotion_end = :promotion_end, participating_products = :participating_products WHERE id_promotion = :id_promotion");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':promotion_name', $promotion_name);
        $stmt->bindParam(':discount_amount', $discount_amount);
        $stmt->bindParam(':discount_type', $discount_type);


        $stmt->bindParam(':promotion_date', $promotion_date);
        $stmt->bindParam(':promotion_end', $promotion_end);
        $stmt->bindParam(':participating_products', $participating_products);
        $stmt->execute();

 // ส่วนที่เพิ่มขึ้นมา: แสดง Popup บันทึกสำเร็จ
 echo "<script>";
 echo "alert('บันทึกสำเร็จ');";
 echo "window.location.href = 'promotion.php';";
 echo "</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
