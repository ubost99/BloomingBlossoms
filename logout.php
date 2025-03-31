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

// Set the theme to the user's selected theme, or use the default if not set
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'default.css';

// Clear all session data to log the user out
session_unset();

// Destroy the session to fully log the user out
session_destroy();

// Redirect the user to the home page (index.php)
header("Location: index.php");

// Ensure that no further code is executed after the redirect
exit();
