<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to manage user data
session_start();

// Default theme is set to 'default.css' if not set by user
$user_theme = "themes/default.css";

// Check if the user is logged in and fetch the theme from the database
if (isset($_SESSION['user_id'])) {
    require_once 'includes/db.php';
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($theme);
        if ($stmt->fetch() && !empty($theme)) {
            $user_theme = "themes/" . htmlspecialchars($theme);  // Set the user theme if available
        }
        $stmt->close();
    }
}

// Count the number of items in the cart
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Check if the user is an admin (used for visibility of admin features)
$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ</title>
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $user_theme; ?>"> <!-- Apply user theme -->
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

<!-- FAQ Section -->
<div class="container py-5">
  <h1 class="text-center mb-4">❔ Frequently Asked Questions</h1>

  <!-- Accordion component to display FAQs -->
  <div class="accordion" id="faqAccordion">
    <!-- FAQ Item: How do I create an account? -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq1">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
          How do I create an account?
        </button>
      </h2>
      <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Visit the <a href="register.php">Register</a> page, fill in your details, and submit the form to get started.
        </div>
      </div>
    </div>

    <!-- FAQ Item: Can I change my theme? -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq2">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
          Can I change my theme?
        </button>
      </h2>
      <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Yes! Go to your <a href="profile.php">Profile</a> and select the theme that best suits your vibe.
        </div>
      </div>
    </div>

    <!-- FAQ Item: What if I forget my password? -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq3">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
          What if I forget my password?
        </button>
      </h2>
      <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Please contact site support for help resetting your password.
        </div>
      </div>
    </div>

    <!-- FAQ Item: Do you offer delivery? -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq4">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
          Do you offer delivery?
        </button>
      </h2>
      <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Currently, we offer in-store pickup.
        </div>
      </div>
    </div>

    <!-- FAQ Item: How do I view my past orders? -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq5">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
          How do I view my past orders?
        </button>
      </h2>
      <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Order history is not yet available.
        </div>
      </div>
    </div>
  </div>

  <!-- Back to Help Centre Button -->
  <div class="text-center mt-5">
    <a href="help-centre.php" class="btn btn-outline-primary">⬅️ Back to Help Centre</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
