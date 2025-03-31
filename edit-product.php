<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$cart_count = 0; // Default to 0 if the cart isn't set

// Check if the cart session exists and count the items
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_theme = "default.css";
$is_admin = false;

$stmt = $conn->prepare("SELECT theme, is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($theme, $is_admin_flag);
if ($stmt->fetch()) {
    if (!empty($theme)) {
        $current_theme = $theme;
    }
    $is_admin = $is_admin_flag;
}
$stmt->close();

if (!$is_admin) {
    header("Location: index.php");
    exit();
}

$product = ["name" => "", "price" => "", "image" => "", "description" => ""];
$is_editing = false;

if (isset($_GET['id'])) {
    $is_editing = true;
    $product_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT name, price, image, description FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product['name'], $product['price'], $product['image'], $product['description']);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $image_path = $product['image'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['image']['name']);
        $target_path = $upload_dir . time() . '_' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }

    if ($is_editing) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $name, $price, $image_path, $description, $product_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Product updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $image_path, $description);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Product added successfully.";
    }
    header("Location: manage-products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_editing ? 'Edit' : 'Add'; ?> Product</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
</head>
<body>
<div class="container mt-5">
    <h2><?php echo $is_editing ? 'Edit' : 'Add'; ?> Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" id="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price" required value="<?php echo htmlspecialchars($product['price']); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <?php if (!empty($product['image'])): ?>
                <div class="mb-2">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width: 200px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" id="image">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $is_editing ? 'Update' : 'Add'; ?> Product</button>
        <a href="manage-products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>