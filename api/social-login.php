<?php
/**
 * API: Mock Social Login (OAuth Handler)
 * Issue #9 - For production readiness, this handles the social callbacks.
 */
require_once '../includes/config.php';

$provider = $_GET['provider'] ?? '';

// In a real production build, these would be your OAuth Client Keys
// For this prototype, we simulate a successful Google/Facebook response.
$mock_users = [
    'google' => [
        'id' => 'G-123456789',
        'name' => 'Demo User (Google)',
        'email' => 'google-demo@tastebite.com',
        'avatar' => 'https://randomuser.me/api/portraits/lego/1.jpg'
    ],
    'facebook' => [
        'id' => 'FB-987654321',
        'name' => 'Demo User (Facebook)',
        'email' => 'facebook-demo@tastebite.com',
        'avatar' => 'https://randomuser.me/api/portraits/lego/2.jpg'
    ]
];

if (!isset($mock_users[$provider])) {
    die("Invalid provider or missing API secrets.");
}

$social_data = $mock_users[$provider];

// Check if user exists
$user = $db->getUserBySocial($provider, $social_data['id']);

if (!$user) {
    // Check if email already taken by manual user
    $existing = $db->getUser($social_data['email']);
    if ($existing) {
        // Log in as existing user (or link accounts)
        $user = $existing;
    } else {
        // Create new social user
        $user = $db->createUser([
            'name' => $social_data['name'],
            'email' => $social_data['email'],
            'avatar' => $social_data['avatar'],
            'auth_provider' => $provider,
            'social_id' => $social_data['id']
        ]);
    }
}

// Log in
if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    // Redirect to profile or home
    header("Location: ../index.php");
    exit;
}

die("Authentication failed. Please contact support.");
