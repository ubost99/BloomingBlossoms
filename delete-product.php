<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start a new session or resume the existing session to access session variables
session_start();

// Check if the user has a preferred theme, if not, set default theme
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'default.css';

// Include the database connection file
include 'includes/db.php';

// Ensure the product ID is passed in the URL, if not, stop the execution with a message
if (!isset($_GET['id'])) {
  die("No product ID specified.");
}

// Retrieve the product ID from the URL
$id = $_GET['id'];

// Prepare an SQL query to delete the product with the specified ID
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id); // Bind the product ID to the query
$stmt->execute(); // Execute the delete query

// After successful deletion, redirect the user back to the manage-products page
header("Location: manage-products.php");
exit(); // Terminate the script to prevent further code execution
?>
