<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

 = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
 = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
 = isset($_GET['category']) ? sanitize($_GET['category']) : null;

$options = [
    'limit' => $limit,
    'offset' => $offset
];

if ($category) $options['category'] = $category;

$recipes = $db->getRecipes($options);

echo json_encode($recipes);
