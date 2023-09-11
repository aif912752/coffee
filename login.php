<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by stevefreecoding -->
    <title>steveloginForm Tutorial in html css</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style media="screen">
      *,
*:before,
*:after{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body{
    background-color: #ffffff;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(-50%,-50%);
   
}
.background .shape{
    height: 200px;
    width: 200px;
    position: absolute;
   
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -80px;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -80px;
}
form{
    height: 520px;
    width: 400px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(-50%,-50%);
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 50px 35px;
}
form *{
    font-family: 'Poppins',sans-serif;
    align-items: flex-end;
    color: #080710;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}
form h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 42px;
    text-align: center;
}

label{
    display: block;
    margin-top: 30px;
    font-size: 16px;
    font-weight: 500;
}
input{
    display: block;
    height: 50px;
    width: 100%;
    background-color: transparent;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: #bebebe;
}
button{
    margin-top: 50px;
    width: 100%;
    background-color: #bebebe;
    color: #080710;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
}
.social{
  margin-top: 30px;
  display: flex;
}
.social div{
  background: red;
  width: 150px;
  border-radius: 3px;
  padding: 5px 10px 10px 5px;
  background-color: rgba(255,255,255,0.27);
  color: #eaf0fb;
  text-align: center;
}
.social div:hover{
  background-color: rgba(255,255,255,0.47);
}
.social .fb{
  margin-left: 25px;
}
.social i{
  margin-right: 4px;
}
.container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        height: 100vh;
    }
    .hidden.lg\:flex.lg\:flex-1.justify-center {
        justify-content: flex-start;
    }
    </style>
</head>
<?php
    require('db.php');
    session_start();

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare the SQL statement using placeholders
        $query = "SELECT * FROM admin WHERE username=:username AND password=:password";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the prepared statement
        $stmt->execute();

        // Get the number of rows returned
        $rows = $stmt->rowCount();

        if ($rows == 1) {
            // Fetch the user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $row['username'];
            $_SESSION['id_admin'] = $row['id_admin']; // เพิ่มบรรทัดนี้

            header("Location: shop.php");
            exit();
        } else {
            include('404.html');
            exit(); // Ensure that no more code is executed after including 404.php
        }
    } else {
        // Handle the case when the form is not submitted
        // ...
    }
?>




<body>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

<img src="img/lo.jpg" >
    <form method="post" name="login">
        <h3>Login Here</h3>
        <label for="username">Username</label>
        <input type="text" placeholder="Username" id="username" name="username">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" id="password">
        <button type="submit" name="submit" >Log In</button>
    </form>
</body>

</html>