<?php
session_start(); // ✅ Step 1: Start the session right at the top
require_once "connection.php";

$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';

if (!$email || !$password) {
    die("Email and password are required.");
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password_hash"])) {
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["first_name"] = $user["first_name"];

    // ✅ Redirect to profile page after successful login
    header("Location: home.php");
    exit();
} else {
    echo "Invalid email or password.";
}
?>
