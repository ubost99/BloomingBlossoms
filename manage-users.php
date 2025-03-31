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

// Default cart count if not set
$cart_count = 0;

// Check if the cart session exists and count the items
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

// Include the database connection file
require_once 'includes/db.php';

// Default theme setting
$current_theme = "default.css";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Prepare SQL query to get the user's theme and admin status
    $stmt = $conn->prepare("SELECT theme, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_admin);

    // If theme is set, use it
    if ($stmt->fetch()) {
        if (!empty($theme)) {
            $current_theme = $theme;
        }
    }
    $stmt->close();
} else {
    // If the user is not logged in, deny access
    echo "Access denied.";
    exit();
}

// Check if the user has admin privileges
if (!$is_admin) {
    // If not an admin, deny access
    echo "Access denied.";
    exit();
}

// Handle actions (enable, disable, promote, demote users)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the action and user ID from the POST request
    $action = $_POST['action'];
    $user_id = $_POST['user_id'];

    // Perform the action based on the selected option
    switch ($action) {
        case 'disable':
            // Disable the user account
            $conn->query("UPDATE users SET is_active = 0 WHERE id = $user_id");
            break;
        case 'enable':
            // Enable the user account
            $conn->query("UPDATE users SET is_active = 1 WHERE id = $user_id");
            break;
        case 'promote':
            // Promote the user to admin
            $conn->query("UPDATE users SET is_admin = 1 WHERE id = $user_id");
            break;
        case 'demote':
            // Demote the user from admin
            $conn->query("UPDATE users SET is_admin = 0 WHERE id = $user_id");
            break;
    }
}

// Fetch all users from the database
$result = $conn->query("SELECT id, name, email, is_admin, is_active FROM users");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="themes/<?= htmlspecialchars($current_theme) ?>">
</head>
<body>

<!-- Navigation bar -->
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

<!-- Manage Users Content -->
<div class="container py-5">
    <h1 class="mb-4">👥 Manage Users</h1>

    <!-- Display session message after user action (add/edit/delete) -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <?= $user['is_active']
                            ? '<span class="badge bg-success">Active</span>'
                            : '<span class="badge bg-secondary">Disabled</span>' ?>
                    </td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <?php if ($user['is_active']): ?>
                                <button class="btn btn-sm btn-warning" name="action" value="disable">Disable</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-success" name="action" value="enable">Enable</button>
                            <?php endif; ?>

                            <?php if ($user['is_admin']): ?>
                                <button class="btn btn-sm btn-danger" name="action" value="demote">Demote</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-primary" name="action" value="promote">Promote</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
