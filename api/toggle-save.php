<?php
/**
 * API: Toggle Recipe Save/Unsave (Issue #1)
 */
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Login required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$recipe_id = $data['recipe_id'] ?? null;

if (!$recipe_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Recipe ID required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$status = $db->toggleSaveRecipe($user_id, $recipe_id);

if ($status) {
    echo json_encode(['status' => $status]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to toggle save status']);
}
