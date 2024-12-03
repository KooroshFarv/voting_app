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
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        
        <div class="profile-stats">
            <h2>Your Activity</h2>
            <p><strong>Total Topics Created:</strong> <?php echo $totalCreatedTopics; ?></p>
            <p><strong>Total Votes Submitted:</strong> <?php echo $totalVotes; ?></p>
        </div>

        <div class="created-topics">
            <h2>Your Created Topics</h2>
            <?php if (!empty($createdTopics)): ?>
                <ul class="topics-list">
                    <?php foreach ($createdTopics as $topic): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($topic['title']); ?></h3>
                            <p><?php echo htmlspecialchars($topic['description']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You haven't created any topics yet.</p>
            <?php endif; ?>
        </div>

        <div class="vote-history">
            <h2>Your Voting History</h2>
            <?php if (!empty($voteHistory)): ?>
                <ul class="vote-list">
                    <?php foreach ($voteHistory as $vote): ?>
                        <li>
                            <p>You <strong><?php echo htmlspecialchars($vote['vote_type']); ?></strong> on 
                            <strong><?php echo htmlspecialchars($vote['title']); ?></strong></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You haven't voted yet.</p>
            <?php endif; ?>
        </div>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
