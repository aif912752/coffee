
<?php
// Include the database connection
require_once('db.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $productType = $_POST['product_type'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];

    // Upload image
    $targetDir = 'product_images/'; // Specify your upload directory
    $productImage = $_FILES['product_img']['name'];
    $targetFile = $targetDir . $productImage;
    move_uploaded_file($_FILES['product_img']['tmp_name'], $targetFile);

    // Prepare SQL statement
    $sql = "INSERT INTO product (id_type, product_name, product_price, product_img) 
            VALUES (:id_type, :product_name, :product_price, :product_img)";

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_type', $productType);
    $stmt->bindParam(':product_name', $productName);
    $stmt->bindParam(':product_price', $productPrice);
    $stmt->bindParam(':product_img', $targetFile);

    // Execute the statement
    try {
        $stmt->execute();
        // ส่วนที่เพิ่มขึ้นมา: แสดง Popup บันทึกสำเร็จ
        echo "<script>";
        echo "alert('บันทึกสำเร็จ');";
        echo "window.location.href = 'product.php';";
        echo "</script>";
    } catch (PDOException $e) {
        echo "Error adding product: " . $e->getMessage();
    }
}
?>
