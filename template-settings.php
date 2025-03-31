<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start a session to keep track of the user's logged-in status
session_start();

// Include the database connection
include 'includes/db.php';

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Handle the POST request when the user selects a theme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the selected theme from the form
  $theme = $_POST['theme'];

  // Prepare and execute the SQL query to update the user's theme in the database
  $stmt = $conn->prepare("UPDATE users SET theme = ? WHERE id = ?");
  $stmt->bind_param("si", $theme, $_SESSION['user_id']);
  $stmt->execute();

  // Store the selected theme in the session
  $_SESSION['theme'] = $theme;

  // Redirect the user back to the template settings page
  header("Location: template-settings.php");
  exit();
}

// Fetch the current theme for the logged-in user from the database
$stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($theme);  // Store the theme result
$stmt->fetch();  // Retrieve the theme for the logged-in user
$stmt->close();  // Close the prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Template Settings</title>
  <!-- Set the favicon for the site -->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  
  <!-- Include the selected theme's stylesheet -->
  <link rel="stylesheet" href="themes/<?= htmlspecialchars($theme) ?>">
  
  <!-- Include Bootstrap CSS for styling -->
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Include Bootstrap JavaScript for interactivity -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light text-dark">
  <div class="container mt-5">
    <h2 class="mb-4">Choose a Theme</h2>
    
    <!-- Form for selecting a theme -->
    <form method="POST" class="form-select-lg">
      <div class="mb-3">
        <select name="theme" class="form-select">
          <!-- Default (Light) theme option -->
          <option value="default.css" <?= $theme === 'default.css' ? 'selected' : '' ?>>Default (Light)</option>
          
          <!-- Dark Mode theme option -->
          <option value="dark.css" <?= $theme === 'dark.css' ? 'selected' : '' ?>>Dark Mode</option>
          
          <!-- Floral Theme option -->
          <option value="floral.css" <?= $theme === 'floral.css' ? 'selected' : '' ?>>Floral Theme</option>
        </select>
      </div>
      
      <!-- Submit button to save the selected theme -->
      <button type="submit" class="btn btn-success">Save Theme</button>
    </form>
  </div>
</body>
</html>
