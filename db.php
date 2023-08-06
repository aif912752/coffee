<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "coffee";

try {
    // Connect to the database using PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // You can optionally set character encoding here, if needed
    // For example, if you are using utf8, you can add this line:
    // $conn->exec("SET NAMES 'utf8'");

} catch (PDOException $e) {
    // If there is an error in the connection, catch it and display the error message
    echo "Connection failed: " . $e->getMessage();
    exit; // Exit the script if there's an error to avoid further execution
}
?>
