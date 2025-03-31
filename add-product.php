<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to use session variables (theme, user data, etc.)
session_start();

// Set the user's theme (default if not set)
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'default.css';

// Include the database connection file
include 'includes/db.php';

// Check if the form is submitted via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form input values
  $name = $_POST["name"];           // Product name
  $desc = $_POST["description"];    // Product description
  $price = $_POST["price"];         // Product price
  $image = $_POST["image"];         // Image filename (for now, just the filename)

  // Prepare the SQL statement to insert the product into the database
  $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $name, $desc, $price, $image); // Bind parameters to the SQL query

  // Execute the query and check if it was successful
  if ($stmt->execute()) {
    // Redirect to the manage-products page if the product was added successfully
    header("Location: manage-products.php");
    exit();
  } else {
    // Display an error message if there was an issue adding the product
    echo "<p>Error adding product.</p>";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Meta and Bootstrap CDN for responsive design and styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <meta charset="UTF-8">
  <title>Add Product</title>

  <!-- Favicon for the website -->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">

  <!-- Custom styles for the page -->
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class='bg-light text-dark'>
  <!-- Page heading -->
  <h2>Add New Product</h2>

  <!-- Form for adding a new product -->
  <form method="POST">
    <!-- Input fields for product details -->
    <label>Name:</label><input type="text" name="name" required><br>

    <label>Description:</label><textarea name="description"></textarea><br>

    <label>Price:</label><input type="number" step="0.01" name="price" required><br>

    <label>Image Filename:</label><input type="text" name="image" required><br>

    <!-- Submit button -->
    <button type="submit">Add Product</button>
  </form>
</body>
</html>
