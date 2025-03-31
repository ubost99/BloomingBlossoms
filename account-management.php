<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Starting the session to use session variables (cart, user data, etc.)
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
require_once "includes/db.php";

// Default theme for the user (can be updated based on user settings)
$user_theme = "themes/default.css";

// Check if the user is logged in and retrieve their theme and active status
if (isset($_SESSION['user_id'])) {
    require_once 'includes/db.php';

    // Prepare and execute the query to get user theme and active status
    $stmt = $conn->prepare("SELECT theme, is_active FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_active);

    // Fetch user data from the database
    if ($stmt->fetch()) {
        // If the user is disabled, log them out and redirect to the account-disabled page
        if (isset($is_active) && $is_active == 0) {
            $stmt->close();
            session_unset();
            session_destroy();
            header("Location: account-disabled.php");
            exit();
        }

        // Set the user's selected theme if it is available
        if (!empty($theme)) {
            $current_theme = $theme;
        }
    }

    // Close the database statement after fetching data
    $stmt->close();
}

// Get the current cart count (number of items in the cart)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Check if the user is an admin (from session data)
$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags for character set and responsive design -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Management</title>

  <!-- Favicon for the website -->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  
  <!-- Bootstrap CSS for styling -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  
  <!-- Dynamic theme stylesheet (user-specific theme) -->
  <link rel="stylesheet" href="<?php echo $user_theme; ?>">
</head>
<body>
<!-- Navbar section -->
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
                    <!-- If logged in, display cart and profile options -->
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart (<?= $cart_count ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- If not logged in, show login, shop, and register links -->
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
                <!-- Help center link -->
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content: Account Management Instructions -->
<div class="container py-5">
  <h1 class="text-center mb-4">👤 Account Management</h1>
  <p class="lead text-center">Take control of your Blooming Blossoms experience by managing your account:</p>

  <!-- List of account management options -->
  <ul class="fs-5">
    <li><strong>Update Theme:</strong> Visit your <a href="profile.php">Profile</a> page to pick a theme that matches your style.</li>
    <li><strong>Change Password:</strong> Currently not supported. For any password resets, please contact support.</li>
    <li><strong>Logout:</strong> Click the “Logout” button in the navbar when you're done shopping.</li>
  </ul>

  <!-- Button to navigate back to Help Centre -->
  <div class="text-center mt-5">
    <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
  </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
