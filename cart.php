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

// Initialize the default theme
$current_theme = 'default.css';

// Initialize the admin status
$is_admin = false;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Prepare a query to get the theme and admin status of the logged-in user
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']); // Bind the user ID
    $stmt->execute();
    $stmt->bind_result($theme); // Bind the result for the theme

    // Fetch the theme from the result
    if ($stmt->fetch() && !empty($theme)) {
        $current_theme = $theme; // Set the current theme
    }

    // Close the prepared statement after use
    $stmt->close();
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove']; // Get the index of the item to remove
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove the item from the cart
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the cart
    }
    header("Location: cart.php"); // Redirect to the cart page
    exit();
}

// Simulate checkout action
if (isset($_GET['checkout'])) {
    $_SESSION['cart'] = []; // Clear the cart
    $_SESSION['message'] = "Thank you for your purchase!"; // Set a success message
    header("Location: cart.php"); // Redirect to the cart page
    exit();
}

// Calculate the total price of items in the cart
$cart = $_SESSION['cart'] ?? [];
$cart_count = count($cart); // Update the cart count
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity']; // Calculate the total price
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>"> <!-- Apply user theme -->
    <style>
      footer {
      padding: 30px 0;
      text-align: center;
      margin-top: 60px;
    }
    </style>
</head>
<body>
<!-- Navbar section with links for logged-in users -->
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
                <li class="nav-item"><a class="nav-link" href="help-centre.php">❓ Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main content of the Cart page -->
<div class="container mt-5">
  <h1>Your Shopping Cart</h1>

  <!-- Display success message if there's a session message -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </div>
  <?php endif; ?>

  <!-- If the cart has items, display them in a table -->
  <?php if (count($cart) > 0): ?>
    <table class="table mt-4">
      <thead>
        <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Size</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $index => $item): ?>
          <tr>
            <td><img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 60px;"></td>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo htmlspecialchars($item['size']); ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            <!-- Button to remove item from the cart -->
            <td><a href="cart.php?remove=<?php echo $index; ?>" class="btn btn-sm btn-danger">Remove</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Display the total price and checkout button -->
    <div class="text-end">
      <h4>Total: $<?php echo number_format($total, 2); ?></h4>
      <a href="cart.php?checkout=true" class="btn btn-primary">Checkout</a>
    </div>
  <?php else: ?>
    <!-- If the cart is empty, display this message -->
    <div class="alert alert-info">Your cart is empty.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Footer -->
<footer>
  <div class="container">
    <p>&copy; 2025 Blooming Blossoms | <i class="fas fa-phone"></i> (123) 456-7891 | <i class="fas fa-envelope"></i> bost@uwindsor.ca</p>
  </div>
</footer>

</html>
