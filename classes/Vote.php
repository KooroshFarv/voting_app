<?php


class Vote {
    private $db;

    public function hasVoted($topicId, $userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM votes WHERE topic_id = :topicId AND user_id = :userId");
        $stmt->execute([':topicId' => $topicId, ':userId' => $userId]);
        return $stmt->fetchColumn() > 0;
    }
    

    public function __construct(VotesDatabase $database) {
        $this->db = $database->pdo;
    }

    public function castVote($topicId, $userId, $voteType) {
        if ($this->hasVoted($topicId, $userId)) {
            throw new Exception("You have already voted on this topic.");
        }
    
        $stmt = $this->db->prepare("INSERT INTO votes (topic_id, user_id, vote_type) VALUES (:topicId, :userId, :voteType)");
        $stmt->execute([':topicId' => $topicId, ':userId' => $userId, ':voteType' => $voteType]);
        return $this->db->lastInsertId();
    }
    

    public function getVotesByTopic($topicId) {
        $stmt = $this->db->prepare("SELECT vote_type, COUNT(*) as count FROM votes WHERE topic_id = :topicId GROUP BY vote_type");
        $stmt->execute([':topicId' => $topicId]);
        return $stmt->fetchAll();
    }
}
