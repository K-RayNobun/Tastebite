<?php
/**
 * API: Delete Recipe (Issue #2)
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
$success = $db->deleteRecipe($recipe_id, $user_id);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete recipe. You might not be the owner.']);
}
