<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "includes/db.php";

$user_theme = "themes/default.css";
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($theme);
        if ($stmt->fetch() && !empty($theme)) {
            $user_theme = "themes/" . htmlspecialchars($theme);
        }
        $stmt->close();
    }
}

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Theme Settings</title>
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $user_theme; ?>">
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

<div class="container py-5">
  <h1 class="text-center mb-4">🎨 Customizing Your Theme</h1>
  <p class="lead text-center">You can personalize your browsing experience by choosing a theme!</p>

  <ul class="fs-5">
    <li><strong>Where to change it:</strong> Visit your <a href="profile.php">Profile</a> page.</li>
    <li><strong>Available themes:</strong> Choose between Default, Floral, or Dark themes.</li>
    <li><strong>Theme preview:</strong> After selecting a theme, the site will immediately reflect your choice.</li>
    <li><strong>Theme persistence:</strong> Your choice is saved and automatically applied each time you log in!</li>
  </ul>

  <div class="text-center mt-5">
    <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>