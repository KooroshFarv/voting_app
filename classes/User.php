<?php
class User {
    private $db;

    public function __construct(VotesDatabase $database) {
        $this->db = $database->pdo;
    }

    public function register($username, $password, $email) {
        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO users (username, password_hash, email) VALUES (:username, :password, :email)");
            $stmt->execute([':username' => $username, ':password' => $passwordHash, ':email' => $email]);
            return $this->db->lastInsertId(); 
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, password_hash FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            if ($user) {
                echo "Found user: " . json_encode($user);
                if (password_verify($password, $user['password_hash'])) {
                    echo "Password matches!";
                    return $user['id'];
                } else {
                    echo "Password mismatch!";
                }
            } else {
                echo "User not found!";
            }
            return false; 
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
