<?php
require_once "connection.php";

$first_name = $_POST["first_name"] ?? '';
$last_name = $_POST["last_name"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';
$confirm_password = $_POST["confirm-password"] ?? '';

if (!$first_name || !$last_name || !$email || !$password || !$confirm_password) {
    die("All fields are required.");
}

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Check if email exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    die("Email already registered.");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
$stmt->execute([$first_name, $last_name, $email, $hashedPassword]);

echo "Account created successfully!";
header("Location: signin_signup.html");
exit();

?>
