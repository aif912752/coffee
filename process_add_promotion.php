<?php
require_once('db.php'); // Include the database connection file

try {
   
    // ฟังก์ชันเพื่อดึงชื่อสินค้าจาก ID สินค้า
    function getProductNameById($product_id) {
        global $conn;
        $sql = "SELECT product_name FROM product WHERE id_product = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product['product_name'];
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $promotion_name = $_POST['promotion_name'];
        $promotion_date = $_POST['promotion_date'];
        $promotion_end = $_POST['promotion_end'];
        $participating_products = $_POST['participating_products'];

        $discount_amount = $_POST['discount_amount'];
        $discount_type = $_POST['discount_type'];

        // สร้างอาร์เรย์เก็บชื่อสินค้าที่เลือก
        $selected_products = array();
        foreach ($participating_products as $product_id) {
            $selected_products[] = getProductNameById($product_id); // เรียกใช้ฟังก์ชันเพื่อดึงชื่อสินค้า
        }

        // แปลงอาร์เรย์เป็นข้อความแบบต่อกันด้วยเครื่องหมาย ","
        $participating_products_str = implode(',', $selected_products);

           // สร้างสตริงสำหรับข้อมูลส่วนลด
           $discount_info = json_encode(array(
            'discount' => $discount_amount,
            'type' => $discount_type
        ));

        $sql = "INSERT INTO promotion (promotion_name, promotion_date, promotion_end, participating_products, discount_amount, discount_type) VALUES (:promotion_name, :promotion_date, :promotion_end, :participating_products, :discount_amount, :discount_type)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":promotion_name", $promotion_name, PDO::PARAM_STR);
        $stmt->bindParam(":promotion_date", $promotion_date, PDO::PARAM_STR);
        $stmt->bindParam(":promotion_end", $promotion_end, PDO::PARAM_STR);
        $stmt->bindParam(":participating_products", $participating_products_str, PDO::PARAM_STR);
        $stmt->bindParam(":discount_amount", $discount_amount, PDO::PARAM_STR);
        $stmt->bindParam(":discount_type", $discount_type, PDO::PARAM_STR);
        $stmt->execute();
        

         // Show success message and redirect
         echo '<script>
         alert("บันทึกสำเร็จ");
         window.location.href = "promotion.php";
       </script>';
 exit();
}
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>


