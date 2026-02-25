<?php
require_once "connection.php";
session_start();

$book_id = $_GET['book_id'] ?? null;

if (!$book_id) {
    die("❌ No book selected.");
}

$stmt = $conn->prepare("SELECT title, file_url, description FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("❌ Book not found.");
}

// Extract file ID from Google Drive link
if (preg_match("/\/d\/(.*?)\//", $book['file_url'], $matches)) {
    $file_id = $matches[1];
    $embed_link = "https://drive.google.com/file/d/$file_id/preview";
} else {
    die("❌ Invalid Drive link.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($book['title']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 30px; }
        iframe { border: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($book['title']) ?></h1>
    <p><?= htmlspecialchars($book['description']) ?></p>
    <iframe src="<?= $embed_link ?>" width="80%" height="600" allow="autoplay"></iframe>
</body>
</html>
