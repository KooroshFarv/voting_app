<?php
session_start();

require 'db_connection.php';
require 'classes/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Initialize database and user class
    $database = new VotesDatabase();
    $user = new User($database);

    // Attempt login
    if ($userId = $user->login($username, $password)) {
        // Login successful
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userId; 
        header('Location: dashboard.php');
        exit();
    } else {
        // Login failed
        $error = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>

        <p><a href="register.php" class="link">Don't have an account? Register</a></p>
    </div>
</body>
</html>
