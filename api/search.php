<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$results = $db->searchRecipes($query);

// Return only necessary data for live search
$formatted = [];
foreach ($results as $r) {
    $formatted[] = [
        'id' => $r['id'],
        'title' => $r['title'],
        'image' => $r['image'],
        'category' => $r['category']
    ];
}

echo json_encode(array_slice($formatted, 0, 5));
