<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
session_start();
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
require_once "includes/db.php";

$current_theme = 'default.css';
$is_admin = false;

// Check if the user is logged in and is an admin
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme, is_active, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_active, $is_admin);
    if ($stmt->fetch()) {
        if ($is_active == 0) {
            $stmt->close();
            session_unset();
            session_destroy();
            header("Location: account-disabled.php");
            exit();
        }
        if (!empty($theme)) {
            $current_theme = $theme;
        }
        $is_admin = ($is_admin == 1); // Check if the user is an admin
    }
    $stmt->close();
}

// If the user is not an admin, show an alert and redirect
if (!$is_admin) {
    echo "<script>alert('You do not have administrator privileges.'); window.location.href = 'index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Help</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        footer {
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }
        .footer-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            color: #2e7d32;
        }
        .help-section {
            padding: 30px 0;
            background-color: #f8f9fa;
        }
        .help-section h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .help-section .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .help-section .section-content {
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .help-section .btn {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">🌸 Blooming Blossoms</a>
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
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container help-section">
    <h2>🛠️ Admin Help: Managing Products and Users</h2>

    <!-- Managing Products Section -->
    <div class="section">
        <h3 class="section-title">Managing Products</h3>
        <p class="section-content">As an admin, you can manage the products displayed in the shop. To manage products, go to the 'Manage Products' section in your dashboard. There, you can add, edit, and remove products, set prices, and update their details like name, description, and image.</p>
        <a href="manage-products.php" class="btn btn-success btn-lg">Go to Manage Products</a>
    </div>

    <!-- Managing Users Section -->
    <div class="section mt-4">
        <h3 class="section-title">Managing Users</h3>
        <p class="section-content">Admins have the ability to manage user accounts. You can promote or demote users to/from admin roles, enable or disable accounts, and view all registered users. To manage users, visit the 'Manage Users' section in your dashboard.</p>
        <a href="manage-users.php" class="btn btn-success btn-lg">Go to Manage Users</a>
    </div>

</div>

    <!-- Button to go back to the Help Centre -->
    <div class="text-center mt-5">
        <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; 2025 Blooming Blossoms | <i class="fas fa-phone"></i> (123) 456-7891 | <i class="fas fa-envelope"></i> bost@uwindsor.ca</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
