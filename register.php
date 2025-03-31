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

// Include database connection
require_once "includes/db.php";

// Set the default theme
$current_theme = "default.css";

// Check if the user is logged in and get their theme
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme);
    if ($stmt->fetch() && !empty($theme)) {
        // Use the user's theme if it is set
        $current_theme = $theme;
    }
    $stmt->close();
}

// Initialize error and success messages
$error = $_SESSION['register_error'] ?? "";
$success = $_SESSION['register_success'] ?? "";
unset($_SESSION['register_error'], $_SESSION['register_success']);

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($name)) {
        $_SESSION['register_error'] = "Name is required.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email is already registered
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$check_stmt) {
            $_SESSION['register_error'] = "Database error: " . $conn->error;
        } else {
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            // If the email exists, show an error message
            if ($check_stmt->num_rows > 0) {
                $_SESSION['register_error'] = "An account with that email already exists.";
                $check_stmt->close();
            } else {
                // Insert the new user into the database
                $check_stmt->close();
                $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, theme) VALUES (?, ?, ?, 'default.css')");
                if ($insert_stmt) {
                    $insert_stmt->bind_param("sss", $name, $email, $hashed_password);
                    if ($insert_stmt->execute()) {
                        $_SESSION['register_success'] = "Registration successful! You can now <a href='login.php'>log in</a>.";
                    } else {
                        $_SESSION['register_error'] = "Registration failed. Please try again.";
                    }
                    $insert_stmt->close();
                } else {
                    $_SESSION['register_error'] = "Database error: " . $conn->error;
                }
            }
        }
    }

    // Redirect to the registration page with a message
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Favicon for the site -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- User-specific theme CSS -->
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
    <!-- Custom CSS (optional) -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Florist</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Registration Form Section -->
<div class="container mt-5">
    <h2 class="text-center">Register</h2>

    <!-- Display error or success messages -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"> <?php echo $error; ?> </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"> <?php echo $success; ?> </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="POST" class="mx-auto" style="max-width: 400px;">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<!-- Bootstrap JS for functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
