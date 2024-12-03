<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require 'db_connection.php';
require 'classes/Vote.php';
require 'classes/Topic.php';

// Initialize classes
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$database = new VotesDatabase();
$vote = new Vote($database);
$topic = new Topic($database);

// Fetch data
$voteHistory = $vote->getVotesByUser($userId);
$createdTopics = $topic->getTopicsByUser($userId);

// Statistics
$totalVotes = count($voteHistory);
$totalCreatedTopics = count($createdTopics);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>

        <!-- Statistics Section -->
        <div class="profile-stats">
            <p><strong>Total Topics Created:</strong> <?php echo $totalCreatedTopics; ?></p>
            <p><strong>Total Votes:</strong> <?php echo $totalVotes; ?></p>
        </div>

        <!-- Created Topics -->
        <h2>Created Topics</h2>
        <?php if (!empty($createdTopics)): ?>
            <ul class="topic-list">
                <?php foreach ($createdTopics as $topic): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($topic['title']); ?></strong> - 
                        <?php echo htmlspecialchars($topic['description']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You haven't created any topics.</p>
        <?php endif; ?>

        <!-- Voting History -->
        <h2>Voting History</h2>
        <?php if (!empty($voteHistory)): ?>
            <ul class="vote-history">
                <?php foreach ($voteHistory as $vote): ?>
                    <li>
                        Voted <strong><?php echo htmlspecialchars($vote['vote_type']); ?></strong>
                        on <strong><?php echo htmlspecialchars($vote['title']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You haven't voted.</p>
        <?php endif; ?>

        <p><a href="dashboard.php" class="back-link">Back to Dashboard</a></p>
    </div>
</body>
</html>
