<?php
// Include the db.php file to connect to the database
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the productId is set and not empty
    if (isset($_POST["productId"]) && !empty($_POST["productId"])) {
        $productId = $_POST["productId"];

        try {
            // Prepare the SQL query to delete the product from the 'product' table
            $stmt = $conn->prepare("DELETE FROM product WHERE id_product = :productId");

            // Bind the productId parameter to the prepared statement
            $stmt->bindParam(':productId', $productId);

            // Execute the query
            $stmt->execute();

            // Redirect to the same page or any other page after successful deletion
            // For example, you can redirect back to the table_product.php page
            header("Location: table_product.php");
            exit;
        } catch (PDOException $e) {
            // If there is an error in the query, catch it and display the error message
            echo "Error: " . $e->getMessage();
            exit; // Exit the script if there's an error to avoid further execution
        }
    }
}
?>
