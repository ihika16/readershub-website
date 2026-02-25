<?php
require_once "connection.php";

$stmt = $conn->query("SELECT books.book_id, books.title, books.description, books.file_url, authors.name AS author_name FROM books JOIN authors ON books.author_id = authors.author_id ORDER BY books.book_id DESC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShare - Share and Read Books Online</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="home.php">
                    <span class="logo-icon" >📚</span>
                    Book<span>Share</span>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="category.html">Browse</a></li>
                <li><a href="upload_book.html">Books</a></li>
                <li><a href="aboutus.html">About</a></li>
                <li><a href="contactus.html">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="profile_page.php" class="profile-btn">🥷</a>
            </div>
        </nav>
    </header>

    <section class="hero">
    <h1>Share Knowledge, Spread Wisdom</h1>
    <p>Upload your books or discover thousands of free books shared by our community.</p>
    
    <form action="search.php" method="GET" class="search-bar">
        <input type="text" name="query" placeholder="Search for books, authors or categories..." required>
        
        
        <button type="submit">Search</button>
    </form>
</section>


    <section class="featured-books">
        <h2 class="section-title">Featured Books</h2>
        <div class="book-card-container">
            <?php foreach ($books as $book): ?>
                <a href="read_book.php?book_id=<?= $book['book_id'] ?>" class="book-card">
                    <div class="book-cover">
                        <img src="book-covers/default.jpg" alt="Book Cover">
                    </div>
                    <div class="book-info">
                        <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                        <p class="book-author"><?= htmlspecialchars($book['author_name']) ?></p>
                        <span class="book-category">Category</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="categories">
        <h2 class="section-title">Browse by Category</h2>
        <div class="categories-grid">
            <div class="category-card">Fiction</div>
            <div class="category-card">Non-Fiction</div>
            <div class="category-card">Mystery</div>
            <div class="category-card">Science Fiction</div>
            <div class="category-card">Fantasy</div>
            <div class="category-card">Biography</div>
        </div>
    </section>

    <section class="upload-section">
        <div class="upload-container">
            <h2 class="section-title">Share Your Books</h2>
            <p>Have a book you want to share with the world? Upload it to our platform and help spread knowledge!</p>
            <a href="upload_book.html" class="btn btn-primary" style="text-decoration: none;">Upload a Book</a>

        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>BookShare</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Mission</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Discover</h3>
                <ul>
                    <li><a href="#">Browse Categories</a></li>
                    <li><a href="#">Popular Books</a></li>
                    <li><a href="#">New Additions</a></li>
                    <li><a href="#">Authors</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Community</h3>
                <ul>
                    <li><a href="#">Forum</a></li>
                    <li><a href="#">Book Clubs</a></li>
                    <li><a href="#">Contributors</a></li>
                    <li><a href="#">Volunteer</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Report an Issue</a></li>
                    <li><a href="#">Feedback</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; 2025 BookShare. All rights reserved.
        </div>
    </footer>
</body>
</html>
