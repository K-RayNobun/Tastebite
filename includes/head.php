<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ?? 'Tastebite - Delicious Recipes for Every Occasion'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo h($recipe['description'] ?? 'Find the best recipes on Tastebite. Simple, delicous, and curated for real food lovers.'); ?>">
    
    <!-- Open Graph (Facebook/Social) -->
    <meta property="og:type" content="<?php echo isset($recipe) ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php echo h($page_title ?? 'Tastebite'); ?>">
    <meta property="og:description" content="<?php echo h($recipe['description'] ?? 'Discover thousands of recipes from our community of creators.'); ?>">
    <meta property="og:image" content="<?php echo isset($recipe['image']) ? $recipe['image'] : 'assets/images/hero/Image.png'; ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo h($page_title ?? 'Tastebite'); ?>">
    <meta name="twitter:description" content="<?php echo h($recipe['description'] ?? 'The best flavors in one place.'); ?>">
    <meta name="twitter:image" content="<?php echo isset($recipe['image']) ? $recipe['image'] : 'assets/images/hero/Image.png'; ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Design System -->
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/header.css">
    
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="container">
