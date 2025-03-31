<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php

// Start the session to track user data and handle cart
session_start();

// Set the default cart count to 0 if it's not set
$cart_count = 0;

// Check if the cart session exists and count the items
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

// Include the database connection file
require_once "includes/db.php";

// Default theme setting
$current_theme = 'default.css';
$is_admin = false;

// Check if the user is logged in and fetch the user's theme and admin status
if (isset($_SESSION['user_id'])) {
    // Prepare SQL query to get the user's theme and admin status
    $stmt = $conn->prepare("SELECT theme, is_active, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_active, $is_admin);

    if ($stmt->fetch()) {
        // If the user's account is inactive, log them out
        if ($is_active == 0) {
            $stmt->close();
            session_unset();
            session_destroy();
            header("Location: account-disabled.php");
            exit();
        }
        // Set the theme if it's available
        if (!empty($theme)) {
            $current_theme = $theme;
        }
    }
    $stmt->close();
}

// Check if the user is an admin, if not redirect them to the home page
if (!$is_admin) {
    header("Location: index.php");
    exit();
}

// Handle product deletion if 'delete' query parameter is set
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    // Prepare SQL query to delete the product by ID
    $del_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del_stmt->bind_param("i", $delete_id);
    
    // Execute the deletion query and set session message
    if ($del_stmt->execute()) {
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting product.";
    }
    $del_stmt->close();

    // Redirect to the manage products page after deletion
    header("Location: manage-products.php");
    exit();
}

// Fetch all products to display on the page
$products = [];
$result = $conn->query("SELECT id, name, price, image FROM products");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
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

<!-- Main content to manage products -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Products</h2>
        <a href="edit-product.php" class="btn btn-success">+ Add New Product</a>
    </div>

    <!-- Display session message after product action (add/edit/delete) -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Check if there are any products to display -->
    <?php if (count($products) > 0): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="manage-products.php?delete=<?php echo $product['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No products found.</div>
    <?php endif; ?>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
