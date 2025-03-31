<!--
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
-->

<?php
// Starting the session to use session variables
session_start();

// Initialize the cart count if the cart is set in session
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Include the database connection file
require_once "includes/db.php";

// Default theme if no theme is set
$current_theme = 'default.css';
$is_admin = false;

// Check if the user is logged in and retrieve theme and status
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT theme, is_active, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($theme, $is_active, $is_admin);

    // Fetch user details
    if ($stmt->fetch()) {
        // Check if the account is disabled
        if ($is_active == 0) {
            // If disabled, log the user out and redirect
            $stmt->close();
            session_unset();
            session_destroy();
            header("Location: account-disabled.php");
            exit();
        }

        // If theme exists, use it
        if (!empty($theme)) {
            $current_theme = $theme;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character set and meta tags for responsiveness -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Favicon for the website -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS for layout and styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Dynamic theme stylesheet -->
    <link rel="stylesheet" href="themes/<?php echo htmlspecialchars($current_theme); ?>">
    <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Styles for images in the About Us section */
        .about-section img {
            max-width: 100%;
            border-radius: 10px;
        }
        /* Footer styling */
        footer {
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }

        /* Flower Picker styling */
        .flower-picker {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .flower {
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .flower:hover {
            transform: scale(1.2);
        }

        .flower-emoji {
            font-size: 50px;
            transition: transform 0.3s ease;
        }

        /* Styling for flower info display */
        #flower-info {
            text-align: center;
            display: none;
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f3f3f3;
        }

        #flower-info.show {
            display: block;
        }

        /* Styling for video section */
        .video-section {
            margin-top: 30px;
            text-align: center;
        }

        .video-section video {
            width: 100%;
            max-width: 800px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<!-- Navbar with links to other pages -->
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

<!-- Simple Video Section -->
<div class="video-section">
    <video autoplay muted loop>
        <source src="videos/about-us-video.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
</div>

<!-- Business Case Section -->
<div class="container my-5">
    <h2 class="text-center mb-5">About Blooming Blossoms</h2>
    <p>Blooming Blossoms is a florist shop dedicated to bringing the beauty of fresh flowers to every occasion. From vibrant, colorful bouquets for birthdays to elegant arrangements for weddings and anniversaries, we offer a wide range of floral designs to meet every need. Our flowers are carefully selected and sourced from local growers, ensuring each bouquet is of the highest quality. Beyond just providing flowers, our mission is to help you create memories that bloom for a lifetime. Whether you're celebrating a special moment or simply brightening up your day, Blooming Blossoms is here to bring joy through the language of flowers. We're passionate about sustainability and are committed to offering eco-friendly options wherever possible. Join us in celebrating life's beautiful moments, one petal at a time.</p>
</div>

<!-- Interactive Flower Picker Section -->
<div class="container my-5 text-center">
    <h2 class="mb-4">Pick Your Favorite Flower!</h2>
    <div class="flower-picker">
        <div class="flower" id="flower-rose" onclick="flowerClicked('Rose')">
            <span class="flower-emoji" style="font-size: 50px;">🌹</span>
        </div>
        <div class="flower" id="flower-lily" onclick="flowerClicked('Lily')">
            <span class="flower-emoji" style="font-size: 50px;">🌸</span>
        </div>
        <div class="flower" id="flower-tulip" onclick="flowerClicked('Tulip')">
            <span class="flower-emoji" style="font-size: 50px;">🌷</span>
        </div>
    </div>
    <div id="flower-info" class="mt-4 p-3 border rounded"></div>
</div>

<!-- Passion for Flowers Section -->
<div class="container my-5">
    <h2 class="text-center mb-5">Why We Love Flowers</h2>
    <p>Flowers have the incredible ability to brighten up any room, lift spirits, and create lasting memories. At Blooming Blossoms, we are passionate about flowers because of their versatility and beauty. Each bloom tells a unique story, from the first bloom of spring to the gentle petals of a rose. Flowers are more than just decoration; they represent emotions, connections, and moments in time. We believe that through flowers, we can touch lives, create meaningful experiences, and celebrate all the little joys that life brings.</p>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; 2025 Blooming Blossoms | <i class="fas fa-phone"></i> (123) 456-7891 | <i class="fas fa-envelope"></i> bost@uwindsor.ca</p>
    </div>
</footer>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Interactive Flower Picker Script -->
<script>
function flowerClicked(flower) {
    let flowerInfo = document.getElementById("flower-info");
    let description = "";

    // Add flower description based on the clicked flower
    switch(flower) {
        case 'Rose':
            description = "<h4>Rose</h4><p>Roses are a symbol of love, beauty, and passion. A timeless flower perfect for any occasion!</p>";
            break;
        case 'Lily':
            description = "<h4>Lily</h4><p>Lilies are known for their elegance and purity. They’re often associated with renewal and positivity.</p>";
            break;
        case 'Tulip':
            description = "<h4>Tulip</h4><p>Tulips represent perfect love and are available in a variety of vibrant colors, bringing joy to every occasion.</p>";
            break;
    }

    flowerInfo.innerHTML = description;
    flowerInfo.classList.add("show");
}
</script>

</body>
</html>
