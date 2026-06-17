<?php
// Basic security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Configuration for Tastebite Platform

define('BASE_PATH', __DIR__ . '/../');
define('ASSETS_URL', '/assets/');

// Helper to get image paths safely (Issue #4)
function get_recipe_image($path) {
    if (empty($path)) {
        return 'assets/images/hero/Image.png'; // Site placeholder
    }
    
    // If it's a full URL, return it
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Check if local file exists
    if (!file_exists(BASE_PATH . $path)) {
        // Return a category-specific placeholder if possible or generic
        return 'assets/images/hero/Image.png'; 
    }
    
    return $path;
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
