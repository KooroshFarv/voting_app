<?php
session_start();




if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>Welcome To The Voting App</title>
    
</head>
<body>
    <h1>Welcome To Voting App</h1>
    

<br/>
    <p>
        <a href="login.php">Login</a> |
        <a href="register.php">Register</a>
    </p>
</body>
<footer>
    <h3>Koorosh Farvardin</h3>
</footer>
</html>
