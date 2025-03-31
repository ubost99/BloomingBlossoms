<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to access session variables
session_start();

// Initialize cart count to 0 if the cart is not set
$cart_count = 0; // Default to 0 if the cart isn't set

// Check if the cart session exists and count the items in the cart
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']); // Count the number of items in the cart
}

// Include the database connection file
require_once "includes/db.php";

// Set the default theme
$current_theme = "default.css";
$is_admin = false; // Default value for admin status

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Prepare a query to get the theme and admin status of the logged-in user
    $stmt = $conn->prepare("SELECT theme, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']); // Bind the user ID
    $stmt->execute();
    $stmt->bind_result($theme, $is_admin_flag); // Bind results for theme and admin status

    // Fetch the data from the query result
    if ($stmt->fetch()) {
        // If the theme is set, use it; otherwise, default to 'default.css'
        if (!empty($theme)) {
            $current_theme = $theme;
        }

        // Set the admin flag based on the database result
        $is_admin = $is_admin_flag;
    }

    // Close the prepared statement after use
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Favicon for the page -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Custom theme for the page -->
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
</head>
<body>
<!-- Navbar section with links for logged-in users and admins -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Navbar brand with the 🌸 emoji as logo -->
        <a class="navbar-brand" href="index.php">🌸 Blooming Blossoms</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- If logged in, display cart, profile, and logout options -->
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart (<?= $cart_count ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- If not logged in, display login and register options -->
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

<!-- Main content of the Admin Dashboard -->
<div class="container mt-5">
    <h1 class="mb-4">Welcome to the Admin Dashboard</h1>

    <!-- Manage Products and Manage Users sections with cards -->
    <div class="row g-4 mb-4">
        <!-- Manage Products Card -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Manage Products</h5>
                    <p class="card-text">Add, edit, or remove flower products from the shop.</p>
                    <a href="manage-products.php" class="btn btn-outline-primary">Go to Products</a>
                </div>
            </div>
        </div>
        <!-- Manage Users Card -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">View user accounts and manage access.</p>
                    <a href="manage-users.php" class="btn btn-outline-primary">Go to Users</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Check Website Monitoring Status section with a card -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Check Website Monitoring Status</h5>
                    <p class="card-text">View the status of critical web components.</p>
                    <a href="monitoring-status.php" class="btn btn-outline-danger">Go to Monitoring Page</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript bundle for responsive components -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
