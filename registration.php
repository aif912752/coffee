<?php
        require('db.php');

// Process form submission
if (isset($_POST['submit'])) {
    // Get form data
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO member (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $firstname);
    $stmt->bindParam(2, $lastname);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $hash);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

// Close the connection
$conn = null;
