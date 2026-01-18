<?php
session_start();

// Site configuration
define('SITE_NAME', 'Gym Coaching Portal');
define('SITE_URL', 'http://localhost/gymcoachingportal');

// Timezone
date_default_timezone_set('America/New_York');

// Include database
require_once __DIR__ . '/database.php';

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to redirect
function redirect($page) {
    header("Location: " . $page);
    exit();
}

// Helper function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
