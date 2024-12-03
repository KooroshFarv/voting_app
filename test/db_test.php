<?php
require '../db_connection.php'; 

try {
    $database = new VotesDatabase();
    echo "db working!";
} catch (PDOException $e) {
    echo "Db N workin: " . $e->getMessage();
}


?>
