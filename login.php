<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to track user state
session_start();

// Include database connection
require_once "includes/db.php";

// Set default theme to 'default.css' and check for user-specific theme
$current_theme = "default.css";
if (isset($_SESSION['user_id'])) {
    // Prepare a query to fetch user-specific theme from the database
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme);

    // If a theme is set for the user, use it
    if ($stmt->fetch() && !empty($theme)) {
        $current_theme = $theme;
    }
    $stmt->close();
}

// Variable to store error message if login fails
$error = "";

// Handle the login process when the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the submitted form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare a query to check if the email exists in the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If the user is found in the database, verify the password
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify the provided password matches the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // On successful login, set session variable and redirect to home page
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            // If password is incorrect, show error message
            $error = "Invalid email or password.";
        }
    } else {
        // If email is not found, show error message
        $error = "Invalid email or password.";
    }

    // Close the prepared statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Favicon for the page -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- User-specific theme CSS -->
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
    <!-- Custom CSS for styles -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<!-- Navigation bar with brand link -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Florist</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Login form container -->
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    
    <!-- Display error message if login fails -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"> <?php echo $error; ?> </div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST" class="mx-auto" style="max-width: 400px;">
        <!-- Email input field -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <!-- Password input field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary w-100">Login</button>

        <!-- Link to register page if the user doesn't have an account -->
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</div>

<!-- Bootstrap JS bundle for responsive components -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
