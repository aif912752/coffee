<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product details from the POST data
    $productType = $_POST['product_type'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $remaining = $_POST['remaining'];

    // Handle uploaded product image
    $targetDir = 'product_images/';
    $targetFile = $targetDir . basename($_FILES['file-input']['name']);
    move_uploaded_file($_FILES['file-input']['tmp_name'], $targetFile);

    try {
        // Prepare the SQL query to insert product details into the "product" table
        $sql = "INSERT INTO product (id_type, product_name, product_price, product_img, remaining) VALUES (:id_type, :product_name, :product_price, :product_img, :remaining)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':id_type', $productType);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':product_price', $productPrice);
        $stmt->bindParam(':product_img', $targetFile);
        $stmt->bindParam(':remaining', $remaining);

        // Execute the query
        if ($stmt->execute()) {
            // Successfully inserted the data
            echo "<script>alert('เพิ่มสำเร็จ'); window.location.href = 'product.php';</script>";
        } else {
            // Failed to insert the data
            echo "Error inserting product data.";
        }
    } catch (PDOException $e) {
        // If there is an error during database operation, catch it and display the error message
        echo "Database error: " . $e->getMessage();
    }
} else {
    // Invalid request method
    echo "Invalid request!";
}
?>
