<?php
require_once "connection.php"; // This should return a PDO object in $conn
session_start();

// Enable errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION["user_id"] ?? null;

$title = $_POST["title"] ?? '';
$description = $_POST["description"] ?? '';
$book_url = $_POST["file_url"] ?? '';

$author_name = $_POST["author_name"] ?? '';
$author_bio = $_POST["author_bio"] ?? '';
$author_dob = $_POST["author_dob"] ?? '';



    if (!$user_id || !$title || !$author_name || !$book_url) {
        echo "<pre>";
        var_dump([
            'user_id' => $user_id,
            'book_name' => $title,
            'author_name' => $author_name,
            'book_url' => $book_url
        ]);
        echo "</pre>";
        die("❌ Missing required fields.");
    }
    


// 1. Check if author exists
$checkAuthor = $conn->prepare("SELECT author_id FROM authors WHERE name = ?");
$checkAuthor->execute([$author_name]);
$author = $checkAuthor->fetch(PDO::FETCH_ASSOC);

if ($author) {
    $author_id = $author["author_id"];
} else {
    // 2. Insert new author
    $insertAuthor = $conn->prepare("INSERT INTO authors (name, bio, birth_date) VALUES (?, ?, ?)");
    $insertAuthor->execute([$author_name, $author_bio, $author_dob]);
    $author_id = $conn->lastInsertId();
}

// 3. Insert book
$insertBook = $conn->prepare("INSERT INTO books (title, description, user_id, author_id, file_url) VALUES (?, ?, ?, ?, ?)");
$insertBook->execute([$title, $description, $user_id, $author_id, $book_url]);


header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Book uploaded successfully!']);
exit;

?>
