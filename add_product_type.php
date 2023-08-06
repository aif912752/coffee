<?php
require_once('db.php');

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the selected product types from the form
    $product_types = $_POST["product_type"];

    // Check if any product types were selected
    if (!empty($product_types)) {
        // Loop through the selected product types and insert them into the 'type' table
        foreach ($product_types as $type) {
            $type = htmlspecialchars($type); // Sanitize the input
            $sql = "INSERT INTO type (product_type) VALUES ('$type')";

            try {
                // Execute the SQL query
                $conn->exec($sql);
                echo "ประเภทสินค้า $type เพิ่มเรียบร้อยแล้ว<br>";
            } catch (PDOException $e) {
                // If there is an error in the query, catch it and display the error message
                echo "เกิดข้อผิดพลาดในการเพิ่มประเภทสินค้า: " . $e->getMessage();
            }
        }
    } else {
        echo "กรุณาเลือกประเภทสินค้า";
    }
}
?>
