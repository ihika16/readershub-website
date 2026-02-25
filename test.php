<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

try {
    $password = password_hash("123456", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Test', 'User', 'test@demo.com', $password]);
    echo "Insert success!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
