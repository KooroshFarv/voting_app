<?php

class Vote {
    private $db;

    public function __construct(VotesDatabase $database) {
        $this->db = $database->pdo;
    }

    public function hasVoted($topicId, $userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM votes WHERE topic_id = :topicId AND user_id = :userId");
        $stmt->execute([':topicId' => $topicId, ':userId' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function castVote($topicId, $userId, $voteType) {
        // Check if the user already voted
        if ($this->hasVoted($topicId, $userId)) {
            throw new Exception("You have already voted on this topic.");
        }

        try {
            // Insert vote into the database
            $stmt = $this->db->prepare("INSERT INTO votes (topic_id, user_id, vote_type) VALUES (:topicId, :userId, :voteType)");
            $stmt->execute([':topicId' => $topicId, ':userId' => $userId, ':voteType' => $voteType]);

            // Debugging query execution
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] !== "00000") {
                throw new Exception("SQL Error: " . implode(", ", $errorInfo));
            }

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getVotesByTopic($topicId) {
        $stmt = $this->db->prepare("SELECT vote_type, COUNT(*) as count FROM votes WHERE topic_id = :topicId GROUP BY vote_type");
        $stmt->execute([':topicId' => $topicId]);
        return $stmt->fetchAll();
    }

    public function getVotesByUser($userId) {
        $stmt = $this->db->prepare("SELECT v.vote_type, t.title FROM votes v JOIN topics t ON v.topic_id = t.id WHERE v.user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }
}
