<?php
session_start();
include "connection.php";

$search = "";
$results = [];

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);

    if (!empty($search)) {
        try {
            $stmt = $conn->prepare("
                SELECT books.*, authors.name AS author_name
                FROM books
                JOIN authors ON books.author_id = authors.author_id
                WHERE books.title LIKE :search OR authors.name LIKE :search
            ");
            $stmt->execute(['search' => "%$search%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Search failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Book<span>Share</span></div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="#">Browse</a></li>
            <li><a href="#">Upload</a></li>
        </ul>
        <div class="profile-btn">U</div>
    </nav>
</header>

<section class="featured-books">
    <h2 class="section-title">Search Results for "<?= htmlspecialchars($search) ?>"</h2>

    <?php if ($results): ?>
        <div class="books-grid">
            <?php foreach ($results as $book): ?>
                <a href="readbook.php?book_id=<?= urlencode($book['book_id']) ?>" class="book-card">
                    <div class="book-cover">
                        <!-- If you add image support later, use: <img src="images/<?= $book['cover'] ?>"> -->
                        <img src="https://via.placeholder.com/200x250?text=No+Cover" alt="Book Cover">
                    </div>
                    <div class="book-info">
                        <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                        <div class="book-author"><?= htmlspecialchars($book['author_name']) ?></div>
                        <div class="book-category"><?= date('F j, Y', strtotime($book['uploaded_at'])) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center; margin-top: 20px;">No results found for "<strong><?= htmlspecialchars($search) ?></strong>"</p>
    <?php endif; ?>
</section>

</body>
</html>
