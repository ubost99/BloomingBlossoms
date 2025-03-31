<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to manage user data
session_start();

// Set error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
require_once "includes/db.php";

// Default theme is set to 'default.css' if not set by the user
$user_theme = "themes/default.css";

// Check if the user is logged in and fetch the theme from the database
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($theme);
        if ($stmt->fetch() && !empty($theme)) {
            $user_theme = "themes/" . htmlspecialchars($theme); // Set the user theme if available
        }
        $stmt->close();
    }
}

// Count the number of items in the cart
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Check if the user is an admin (used for visibility of admin features)
$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Getting Started</title>
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $user_theme; ?>"> <!-- Apply user theme -->
</head>
<body>
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart (<?= $cart_count ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Getting Started Section -->
<div class="container py-5">
  <h1 class="text-center mb-4">🌸 Getting Started</h1>
  <p class="lead text-center">New to Blooming Blossoms? Here's how to get started:</p>

  <!-- Steps for getting started -->
  <ol class="fs-5">
    <li><strong>Create an account:</strong> <a href="register.php">Register here</a> to start your floral journey.</li>
    <li><strong>Log in:</strong> Head over to the <a href="login.php">Login</a> page if you already have an account.</li>
    <li><strong>Visit your profile:</strong> Set your theme and personalize your shopping experience.</li>
    <li><strong>Browse the shop:</strong> Discover a wide range of beautiful bouquets and gifts on our <a href="shop.php">Shop</a> page.</li>
    <li><strong>Add to cart & checkout:</strong> Select your bouquet size and checkout when you're ready!</li>
  </ol>

  <!-- Back to Help Centre Button -->
  <div class="text-center mt-5">
    <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
