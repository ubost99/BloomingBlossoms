<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Start the session to use session variables (cart, user data, etc.)
session_start();

// Include the database connection file
require_once "includes/db.php";

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted with necessary product details
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['variant'], $_POST['price'], $_POST['quantity'])) {
    // Get product details from the POST request
    $product_id = (int) $_POST['product_id']; // Product ID (cast to integer)
    $variant = $_POST['variant'];             // Variant (e.g. size)
    $price = (float) $_POST['price'];         // Product price (cast to float)
    $quantity = (int) $_POST['quantity'];     // Quantity (cast to integer)

    // Prepare and execute a database query to fetch product name and image based on product ID
    $stmt = $conn->prepare("SELECT name, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id); // Bind the product ID to the query
    $stmt->execute();
    $stmt->bind_result($name, $image);   // Bind result columns for product name and image

    // If the product is found, add it to the cart session
    if ($stmt->fetch()) {
        // Create an array to hold cart item details
        $cart_item = [
            'product_id' => $product_id,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'size' => $variant,     // Size variant (e.g. small, large)
            'quantity' => $quantity // Quantity added to the cart
        ];

        // If the cart doesn't exist in the session, initialize it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add the cart item to the session cart array
        $_SESSION['cart'][] = $cart_item;
    }

    // Close the prepared statement
    $stmt->close();
}

// Redirect to the shop page after adding the product to the cart
header("Location: shop.php");
exit();
?>
