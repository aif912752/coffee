<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type_name'])) {
    // Get the product type from the POST data
    $productType = $_POST['type_name'];

    try {
        // Prepare the SQL query
        $sql = "INSERT INTO type (type_name) VALUES (:type_name)";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':type_name', $productType);

        // Execute the query
        if ($stmt->execute()) {
            // Successfully inserted the data
            echo "Success";
        } else {
            // Failed to insert the data
            echo "Error inserting data.";
        }
    } catch (PDOException $e) {
        // If there is an error during database operation, catch it and display the error message
        echo "Database error: " . $e->getMessage();
    }
} else {
    // Invalid request method or missing data
    echo "Invalid request!";
}
?>
