<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require 'db_connection.php';
require 'classes/Topic.php';

$database = new VotesDatabase();
$topic = new Topic($database);

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];
$message = '';

// Fetch Topics Created by the User
$userTopics = $topic->getTopicsByUser($userId);

// Fetch All Topics
$allTopics = $topic->getAllTopics();

// Display a success message if redirected after topic creation
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Topic created successfully!";
}

// Statistics
$totalUserTopics = count($userTopics);
$totalVotes = 5; // Placeholder for votes made by the user (you can fetch this dynamically)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="header-content">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        </div>
    </header>



    
    <nav class="dashboard-nav">
        <ul>
            <li><a href="create_topic.php"><i class="fas fa-plus"></i> Create a New Topic</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li>
            <li><a href="vote.php"><i class="fas fa-vote-yea"></i> Vote on Topics</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="dashboard-content">
        <?php if (!empty($message)): ?>
            <div class="message-box">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <!-- Statistics Section -->
        <section class="stats">
            <h2>Reports</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Topics Created</h3>
                    <p><?php echo $totalUserTopics; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Votes Submitted</h3>
                    <p><?php echo $totalVotes; ?></p>
                </div>
            </div>
        </section>
        <section class="user-topics">
            <h2>Recently Created Topic</h2>
            <?php if (!empty($userTopics)): ?>
                <ul class="topics-list">
                    <?php foreach ($userTopics as $topic): ?>
                        <li class="topic-card">
                            <h3><?php echo htmlspecialchars($topic['title']); ?></h3>
                            <p><?php echo htmlspecialchars($topic['description']); ?></p>
                            <a href="vote.php?topicId=<?php echo htmlspecialchars($topic['id']); ?>">View Topic</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You haven't created any topics. <a href="create_topic.php">Create a topic!</a></p>
            <?php endif; ?>
        </section>
        <section class="all-topics">
            <h2> All Topics</h2>
            <?php if (!empty($allTopics)): ?>
                <ul class="topics-list">
                    <?php foreach ($allTopics as $topic): ?>
                        <li class="topic-card">
                            <h3><?php echo htmlspecialchars($topic['title']); ?></h3>
                            <p><?php echo htmlspecialchars($topic['description']); ?></p>
                            <a href="vote.php?topicId=<?php echo htmlspecialchars($topic['id']); ?>">Vote</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No topics available. <a href="create_topic.php">Create one now!</a></p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="dashboard-footer">
        <p>&copy; 2024 Voting App. Koorosh Farvardin</p>
    </footer>
</body>
</html>
