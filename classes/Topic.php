<?php
class Topic {
    private $db;

    public function __construct(VotesDatabase $database) {
        $this->db = $database->pdo;
    }

    public function create($title, $description, $userId) {
        $stmt = $this->db->prepare("INSERT INTO topics (title, description, created_by) VALUES (:title, :description, :userId)");
        $stmt->execute([':title' => $title, ':description' => $description, ':userId' => $userId]);
        return $this->db->lastInsertId();
    }

    public function getAllTopics() {
        $stmt = $this->db->query("SELECT * FROM topics");
        return $stmt->fetchAll();
    }

    public function getTopicById($topicId) {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE id = :id");
        $stmt->execute([':id' => $topicId]);
        return $stmt->fetch();
    }
     public function getTopicsByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE created_by = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }
    
}

?>
