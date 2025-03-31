<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to manage user data like cart, theme, and login state
session_start();

// Set default cart count to 0 if session doesn't have cart data
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Include the database connection file
require_once "includes/db.php";

// Set default theme to 'default.css' for the user
$current_theme = 'default.css';
$is_admin = false;

// Check if user is logged in, if so, fetch their theme and admin status
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme, is_active, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_active, $is_admin);

    if ($stmt->fetch()) {
        // If the account is inactive, destroy the session and redirect to account-disabled page
        if ($is_active == 0) {
            $stmt->close();
            session_unset();
            session_destroy();
            header("Location: account-disabled.php");
            exit();
        }

        // Set user theme if available
        if (!empty($theme)) {
            $current_theme = $theme;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags for character encoding and responsiveness -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blooming Blossoms</title>
    
    <!-- Favicon for the website -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    
    <!-- Bootstrap CDN for responsive design and components -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Load dynamic theme based on user's session -->
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Custom Styles for Hero Section and About Section -->
    <style>
        /* Hero section with video background */
        .hero-section {
            position: relative;
            height: 70vh;
            overflow: hidden;
        }
        .hero-section video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
        }
        
        /* Styles for About Section */
        .about-section .emoji {
            font-size: 80px;
            text-align: center;
        }
        .about-section p {
            font-size: 18px;
            text-align: center;
        }
        .about-section .section-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        /* Footer styling */
        footer {
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<!-- Navigation bar with responsive design -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            🌸 Blooming Blossoms
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Display cart and profile options for logged-in users -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart (<?= $cart_count ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- Display login and register options for guests -->
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section with video background -->
<div class="hero-section">
  <video autoplay muted loop>
    <source src="videos/flowers.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
  </video>
  <div class="hero-overlay">
    <h1 class="display-4">Bloom With Us</h1>
    <a href="shop.php" class="btn btn-success btn-lg mt-3">🌸 Shop Now</a>
  </div>
</div>

<!-- About Us Section with Emoji Representation -->
<div class="container my-5 about-section">
    <h2 class="text-center mb-5">Our Story</h2>
    <div class="row">
        <!-- Our Roots -->
        <div class="col-md-4">
            <div class="emoji">🌱</div>
            <div class="section-title">Our Roots</div>
            <p>We began as a humble shop, committed to providing fresh, quality flowers to the community. We are passionate about flowers and their ability to bring joy to all occasions.</p>
        </div>
        
        <!-- Growing with You -->
        <div class="col-md-4">
            <div class="emoji">🌸</div>
            <div class="section-title">Growing with You</div>
            <p>As we expanded, we grew alongside our customers—providing flowers for birthdays, weddings, and special events. Our relationships with customers are what keep us going.</p>
        </div>
        
        <!-- Blossoming Ahead -->
        <div class="col-md-4">
            <div class="emoji">🌿</div>
            <div class="section-title">Blossoming Ahead</div>
            <p>We are now looking toward a future of sustainability, offering eco-friendly options and continuing to bring beauty to the world through our flowers.</p>
        </div>
    </div>
</div>

<!-- Intrigued Section with Call to Action -->
<div class="container my-5 text-center learn-more-section">
  <h2 class="mb-4">Intrigued by our story?</h2>
  <p>Click the button below to learn more about our journey, values, and mission.</p>
  <a href="about-us.php" class="btn btn-success btn-lg">Learn More</a>
</div>

<!-- Google Maps Section -->
<div class="container mb-5">
  <h2 class="text-center mb-4">Visit Us</h2>
  <div class="ratio ratio-16x9">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3052.8584093347214!2d-83.06411392342688!3d42.30528227119886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883b2d82a3d5be87%3A0xd4c7a2b176d34809!2sUniversity%20of%20Windsor!5e0!3m2!1sen!2sca!4v1711727839447!5m2!1sen!2sca" 
      width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</div>

<!-- Contact Form Section -->
<div class="container mb-5">
  <h2 class="text-center mb-4">Reach Out to Us</h2>
  <form class="mx-auto" style="max-width: 600px;">
    <div class="mb-3">
      <label for="name" class="form-label">Your Name</label>
      <input type="text" class="form-control" id="name" placeholder="Enter your name">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Your Email</label>
      <input type="email" class="form-control" id="email" placeholder="Enter your email">
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" id="message" rows="4" placeholder="What's on your mind?"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Send Message</button>
  </form>
</div>

<!-- Return to Top Button -->
<div class="text-center mb-5">
  <a href="#" class="btn btn-outline-secondary">⬆️ Back to Top</a>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <p>&copy; 2025 Blooming Blossoms | <i class="fas fa-phone"></i> (123) 456-7891 | <i class="fas fa-envelope"></i> bost@uwindsor.ca</p>
  </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
