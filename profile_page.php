<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: signin_signup.html");
    exit;
}

$user_id = $_SESSION["user_id"];

// Get user info
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get book stats (count of books)
$bookCountStmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE user_id = ?");
$bookCountStmt->execute([$user_id]);
$bookCount = $bookCountStmt->fetchColumn();

$fullName = $user["first_name"] . " " . $user["last_name"];
$username = explode('@', $user["email"])[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alex Johnson | BookShare Profile</title>
    <link rel="stylesheet" href="landing_page.css">
    <style>
        /* Profile Page Specific Styles */
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            gap: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 2.5rem;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .profile-username {
            color: #7f8c8d;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .profile-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 15px;
            border-right: 1px solid #e0e0e0;
        }

        .stat:last-child {
            border-right: none;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .profile-bio {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }

        .profile-actions {
            display: flex;
            gap: 10px;
        }

        .tabs {
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
        }

        .tab {
            padding: 15px 25px;
            cursor: pointer;
            color: #7f8c8d;
            font-weight: 500;
            position: relative;
        }

        .tab.active {
            color: #2c3e50;
        }

        .tab.active:after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #3498db;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Bookshelf styles */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
        }

        .book-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .book-cover {
            height: 250px;
            background-color: #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-status {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .book-info {
            padding: 15px;
        }

        .book-title {
            font-weight: bold;
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: #2c3e50;
        }

        .book-author {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .book-rating {
            color: #f39c12;
            margin-bottom: 8px;
        }

        /* Activity styles */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .activity-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .activity-content {
            flex: 1;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .activity-user {
            font-weight: bold;
            color: #2c3e50;
        }

        .activity-time {
            color: #95a5a6;
            font-size: 0.9rem;
        }

        .activity-text {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .activity-book {
            display: flex;
            gap: 15px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            align-items: center;
        }

        .activity-book-cover {
            width: 60px;
            height: 90px;
            object-fit: cover;
        }

        .activity-book-info h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
        }

        .activity-book-info p {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Reviews styles */
        .review-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .review-item {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .review-book {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .review-book-cover {
            width: 80px;
            height: 120px;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .review-book-info {
            flex: 1;
        }

        .review-book-title {
            font-size: 1.2rem;
            margin: 0 0 5px 0;
            color: #2c3e50;
        }

        .review-book-author {
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .review-rating {
            color: #f39c12;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .review-date {
            color: #95a5a6;
            font-size: 0.9rem;
        }

        .review-text {
            line-height: 1.6;
            color: #444;
        }

        .review-actions {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .review-action {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #7f8c8d;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .review-action:hover {
            color: #3498db;
        }

        /* Friends styles */
        .friends-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .friend-card {
            display: flex;
            gap: 15px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            align-items: center;
        }

        .friend-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .friend-info {
            flex: 1;
        }

        .friend-name {
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .friend-stats {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .friend-action {
            font-size: 0.9rem;
            color: #3498db;
            cursor: pointer;
        }

        /* Reading Goals section */
        .reading-goals {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .reading-goals h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .goal-item {
            margin-bottom: 20px;
        }

        .goal-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .goal-label {
            font-weight: 500;
        }

        .goal-progress {
            background-color: #eaeaea;
            height: 8px;
            border-radius: 4px;
            margin-bottom: 5px;
            position: relative;
            overflow: hidden;
        }

        .goal-bar {
            position: absolute;
            height: 100%;
            background-color: #3498db;
            border-radius: 4px;
            left: 0;
            top: 0;
        }

        .goal-detail {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        /* Empty state styles */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }

        .empty-state p {
            margin-bottom: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-stats {
                justify-content: center;
            }

            .profile-actions {
                justify-content: center;
            }

            .book-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .friends-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .tab {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
<header>
    <nav>
        <div class="logo">
            <a href="home.php">
                <span class="logo-icon">📚</span>
                Book<span>Share</span>
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="home.html">Home</a></li>
            <li><a href="contactus.html">contact</a></li>
            <li><a href="category.html">Discover</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main class="profile-container">
    <section class="profile-header">
        <img src="/api/placeholder/150/150" alt="<?php echo htmlspecialchars($fullName); ?>" class="profile-avatar">

        <div class="profile-info">
            <h1 class="profile-name"><?php echo htmlspecialchars($fullName); ?></h1>
            <p class="profile-username">@<?php echo htmlspecialchars($username); ?></p>

            <div class="profile-stats">
                <div class="stat">
                    <span class="stat-value"><?php echo $bookCount; ?></span>
                    <span class="stat-label">Books</span>
                </div>
                <div class="stat">
                    <span class="stat-value">0</span>
                    <span class="stat-label">Currently Reading</span>
                </div>
                <div class="stat">
                    <span class="stat-value">0</span>
                    <span class="stat-label">Friends</span>
                </div>
                <div class="stat">
                    <span class="stat-value">0</span>
                    <span class="stat-label">Shared</span>
                </div>
            </div>

            <p class="profile-bio">Welcome to your profile page, <?php echo htmlspecialchars($user['first_name']); ?>! Start adding books, writing reviews, and connecting with friends.</p>

           
        </div>
    </section>
</main>

    
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
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Follow Us</h3>
                <ul class="social-links">
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; 2025 BookShare. All rights reserved.
        </div>
    </footer>

    