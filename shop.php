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
require_once 'includes/db.php';

// Set the default theme
$user_theme = "themes/default.css";

// Check if the user is logged in and get their theme from the database
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_admin);
    if ($stmt->fetch() && !empty($theme)) {
        // If a theme is set, use it
        $user_theme = "themes/" . htmlspecialchars($theme);
    }
    $stmt->close();
}

// Initialize products array
$products = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// If a search query is provided, search for products by name or description
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT id, name, price, image, description FROM products WHERE name LIKE ? OR description LIKE ?");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Otherwise, fetch all products
    $result = $conn->query("SELECT id, name, price, image, description FROM products");
}

// Store products in an array
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Count the number of items in the cart
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- User-specific theme CSS -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($user_theme); ?>">
    <style>
        /* Additional styling for form and card elements */
        .search-input::placeholder { color: #888; }
        .form-label { font-size: 0.875rem; }
        select.form-select, input.form-control { font-size: 0.9rem; }
        .card-img-top { width: 100%; height: auto; object-fit: cover; }
        .card-body { display: flex; flex-direction: column; justify-content: space-between; height: 100%; }

        footer {
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }
    </style>
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

<!-- Product Display Section -->
<div class="container mt-5">
  <h1 class="mb-4">Our Products</h1>

  <!-- Search Form -->
  <form method="GET" class="mb-4">
    <div class="input-group">
      <span class="input-group-text">🔍</span>
      <input type="text" name="search" class="form-control search-input" placeholder="Search for flowers..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
  </form>

  <!-- Display Products in a Grid -->
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <?php if (!empty($product['image'])): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title mb-2"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text mb-3"><?= htmlspecialchars($product['description']) ?></p>
            <!-- If user is logged in, show the "Add to Cart" button -->
            <?php if (isset($_SESSION['user_id'])): ?>
              <form method="POST" action="add-to-cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="mb-2">
                  <label class="form-label">Size:</label>
                  <select name="variant" class="form-select" onchange="updatePrice(this)">
                    <option value="Small" data-price="<?= $product['price'] ?>">Small - $<?= number_format($product['price'], 2) ?></option>
                    <option value="Large" data-price="<?= $product['price'] + 10 ?>">Large - $<?= number_format($product['price'] + 10, 2) ?></option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Quantity:</label>
                  <input type="number" name="quantity" value="1" min="1" class="form-control">
                </div>
                <input type="hidden" name="price" value="<?= $product['price'] ?>">
                <button class="btn btn-success w-100" type="submit">Add to Cart</button>
              </form>
            <?php else: ?>
              <!-- If user is not logged in, show the login prompt modal -->
              <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#loginPromptModal">Add to Cart</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Login Prompt Modal for Non-logged-in Users -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="loginPromptLabel">🛒 Login Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You must be logged in to add items to your cart. Please log in or register to continue shopping.
      </div>
      <div class="modal-footer">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-outline-secondary">Register</a>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Script for updating hidden price when size changes -->
<script>
function updatePrice(select) {
  const price = select.options[select.selectedIndex].dataset.price;
  const hiddenPrice = select.closest('form').querySelector('input[name="price"]');
  hiddenPrice.value = price;
}
</script>

<!-- Bootstrap JS for functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Footer -->
<footer>
  <div class="container">
    <p>&copy; 2025 Blooming Blossoms | <i class="fas fa-phone"></i> (123) 456-7891 | <i class="fas fa-envelope"></i> bost@uwindsor.ca</p>
  </div>
</footer>

</html>
