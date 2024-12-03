<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

require 'db_connection.php';
require 'classes/Topic.php';

$database = new VotesDatabase();
$topic = new Topic($database);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($title && $description) {
        try {
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            } else {
                $userId = 1; 
            }
            
            $topicId = $topic->create($title, $description, $userId);

            header("Location: dashboard.php?success=1");
            exit();
        } catch (Exception $e) {
            $error = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = "Title and description are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Topic</title>
    <link rel="stylesheet" href="styles/create_topic.css">
</head>
<body>
    <!-- Main Container -->
    <div class="create-topic-container">
        <h1>Create a New Topic</h1>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Form Section -->
        <form method="POST" action="create_topic.php">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter your topic title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Enter your topic description" required></textarea>

            <button type="submit">Create Topic</button>
        </form>

        <!-- Back Link -->
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
