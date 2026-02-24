<?php
// Basic security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Configuration for Tastebite Platform

define('BASE_PATH', __DIR__ . '/../');
define('ASSETS_URL', '/assets/');

// Helper to get image paths based on current location
function get_image_path($relative_path) {
    return $relative_path;
}

// Site settings
$site_name = "Tastebite";
$copyright = "© " . date('Y') . " Tastebite - All rights reserved";

// Load Database & Auth
require_once 'Database.php';
require_once 'Auth.php';
require_once 'security.php';

$db = new Database();
$auth = Auth::getInstance();

// Start session for auth (Already handled in Auth constructor, but kept for clarity)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
