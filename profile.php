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

// Set a default cart count
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
require_once 'includes/db.php';

// Set default theme
$current_theme = "default.css";
$is_admin = false;
$name = "User";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle theme update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
    // Get the selected theme from the form
    $selected_theme = $_POST['theme'];
    // Update the theme in the database
    $stmt = $conn->prepare("UPDATE users SET theme = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $selected_theme, $user_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Theme updated successfully!";
        // Redirect to the profile page
        header("Location: profile.php");
        exit();
    }
}

// Retrieve user data (name, theme, and admin status) from the database
$stmt = $conn->prepare("SELECT name, theme, is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $theme, $is_admin_flag);
if ($stmt->fetch()) {
    // Set the user's theme if available
    if (!empty($theme)) {
        $current_theme = $theme;
    }
    // Set admin status
    $is_admin = $is_admin_flag;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <!-- Favicon for the site -->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- User-specific theme CSS -->
  <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
</head>
<body>
  <!-- Navigation Bar -->
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

  <!-- Main Profile Content -->
  <div class="container mt-5">
    <h2>Hello, <?php echo htmlspecialchars($name); ?>!</h2>

    <!-- Display success message after theme update -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </div>
    <?php endif; ?>

    <!-- Theme Selection Form -->
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="theme" class="form-label">Choose your theme:</label>
            <select class="form-select" id="theme" name="theme">
                <!-- Option to select Default theme -->
                <option value="default.css" <?php if ($current_theme === 'default.css') echo 'selected'; ?>>Default</option>
                <!-- Option to select Dark theme -->
                <option value="dark.css" <?php if ($current_theme === 'dark.css') echo 'selected'; ?>>Dark</option>
                <!-- Option to select Floral theme -->
                <option value="floral.css" <?php if ($current_theme === 'floral.css') echo 'selected'; ?>>Floral</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Theme</button>
    </form>

    <!-- Admin Tools Section (Visible only for admins) -->
    <?php if ($is_admin): ?>
        <div class="mt-5">
            <hr>
            <h4>Admin Tools</h4>
            <!-- Link to the Admin Dashboard -->
            <a href="admin.php" class="btn btn-outline-danger mt-2">Go to Admin Dashboard</a>
        </div>
    <?php endif; ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
