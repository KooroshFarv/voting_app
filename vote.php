<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require 'db_connection.php';
require 'classes/Vote.php';
require 'classes/Topic.php';

// Initialize classes
$database = new VotesDatabase();
$vote = new Vote($database);
$topic = new Topic($database);

// Initialize variables
$message = '';

// Handle voting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topicId = $_POST['topicId'];
    $voteType = $_POST['voteType'];
    $userId = $_SESSION['user_id'];

    try {
        $vote->castVote($topicId, $userId, $voteType);
        $message = "Congratulations, you just voted!";
    } catch (Exception $e) {
        $message = "Error: " . htmlspecialchars($e->getMessage());
    }
}

// Fetch all topics
try {
    $topics = $topic->getAllTopics();
} catch (Exception $e) {
    $topics = [];
    $message = "Error loading topics: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote on Topics</title>
    <link rel="stylesheet" href="styles/vote.css">
</head>
<body>
    <h1>Vote on Topics</h1>

    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <h3><?php echo htmlspecialchars($topic['title']); ?></h3>
                <p><?php echo htmlspecialchars($topic['description']); ?></p>
                <form method="POST" action="vote.php">
                    <input type="hidden" name="topicId" value="<?php echo htmlspecialchars($topic['id']); ?>">
                    <button type="submit" name="voteType" value="up">Upvote</button>
                    <button type="submit" name="voteType" value="down">Downvote</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>