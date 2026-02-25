<?php
session_start();
include "connection.php";

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);
            $success = "Thank you! Your message has been received.";
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again later.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - BookShare</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .contact-section {
            max-width: 800px;
            margin: 60px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-section h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-form button {
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #2980b9;
        }

        .contact-info {
            text-align: center;
            margin-top: 40px;
            color: #555;
        }
    </style>
</head>
<body>

<header>
    <nav>
    <div class="auth-buttons">
    <div class="logo">
                <a href="home.php">
                    <span class="logo-icon">📚</span>
                    Book<span>Share</span>
                </a>
            </div>
            </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            
            <li><a href="upload_book.html">Upload</a></li>
            <li><a href="how_it_works.html">How It Works</a></li>
            <li><a href="contactus.php">Contact</a></li>
        </ul>
        <div class="auth-buttons">
                <a href="profile_page.php" class="profile-btn">🥷</a>
            </div>
    </nav>
</header>

<section class="contact-section">
    <h2>Contact Us</h2>

    <?php if ($success): ?>
        <p style="color: green; text-align: center;"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="contact.php" method="post" class="contact-form">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="subject" placeholder="Subject (e.g., Suggestion, Complaint)">
        <textarea name="message" placeholder="Your message..." required></textarea>
        <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <p>📧 Email: support@bookshare.com</p>
        <p>📍 Location: Online Everywhere</p>
        <p>💬 We usually respond within 24–48 hours.</p>
    </div>
</section>

<footer>
    <div class="footer-content">
        <div class="footer-column">
            <h3>About BookShare</h3>
            <p>BookShare is a free platform for sharing and discovering books contributed by the community.</p>
        </div>
        <div class="footer-column">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="upload_book.html">Upload</a></li>
                <li><a href="how_it_workks.html">How It Works</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Contact</h3>
            <ul>
                <li><a href="#">Email Support</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Use</a></li>
            </ul>
        </div>
    </div>
    <div class="copyright">
        &copy; 2025 BookShare. All rights reserved.
    </div>
</footer>

</body>
</html>
