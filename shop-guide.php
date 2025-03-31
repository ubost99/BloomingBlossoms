<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to track user data
session_start();

// Display errors during development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once "includes/db.php";

// Set the default theme
$user_theme = "themes/default.css";

// Check if the user is logged in and get their theme from the database
if (isset($_SESSION['user_id'])) {
    // Prepare the SQL statement to fetch the user's theme
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($theme);
        if ($stmt->fetch() && !empty($theme)) {
            // If a theme is set, use it
            $user_theme = "themes/" . htmlspecialchars($theme);
        }
        $stmt->close();
    }
}

// Count the items in the cart if the session exists
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Check if the user is an admin
$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Shop</title>
    <!-- Favicon for the site -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- User-specific theme CSS -->
    <link rel="stylesheet" href="<?php echo $user_theme; ?>">
</head>
<body>
<!-- Navbar -->
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
                <!-- If user is logged in, show their profile, cart, and logout options -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart (<?= $cart_count ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- If the user is not logged in, show login, shop, and register options -->
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content Section -->
<div class="container py-5">
    <h1 class="text-center mb-4">🛍️ How to Shop</h1>
    <p class="lead text-center">Here’s everything you need to know to start shopping!</p>

    <!-- Shopping Guide List -->
    <ol class="fs-5">
        <li><strong>Browse Products:</strong> Head to the <a href="shop.php">Shop</a> to view our curated collection of floral arrangements.</li>
        <li><strong>View Details:</strong> Each card shows name, price, size options, and image. Click through for more.</li>
        <li><strong>Select a Size:</strong> Choose between Small and Large sizes (each with unique pricing).</li>
        <li><strong>Add to Cart:</strong> Click “Add to Cart” — your selected item appears in the 🛒 cart.</li>
        <li><strong>Check Your Cart:</strong> Navigate to the <a href="cart.php">Cart</a> page to view all selected items and totals.</li>
        <li><strong>Checkout:</strong> Ready to bloom? Press the checkout button and complete your order!</li>
    </ol>

    <!-- Button to go back to the Help Centre -->
    <div class="text-center mt-5">
        <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
    </div>
</div>

<!-- Bootstrap JS for functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
