<?php
require_once 'includes/config.php';

$category_name = isset($_GET['name']) ? sanitize($_GET['name']) : 'Seafood';
$page_title = "Tastebite | " . h($category_name);
$extra_css = ['assets/css/category.css', 'assets/css/pages/home.css'];

// Filter recipes by category using efficient SQL
$filtered_recipes = $db->getRecipes(['category' => $category_name, 'limit' => 24]);

// If no results specifically for this name, use search
if (empty($filtered_recipes)) {
    $filtered_recipes = $db->searchRecipes($category_name);
}

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
    <!-- Dynamic Category Hero -->
    <section class="category-hero">
        <div class="category-hero-content">
            <div class="category-hero-text">
                <h1><?php echo h($category_name); ?></h1>
                <p>Explore our hand-picked selection of the most delicious <?php echo strtolower(h($category_name)); ?> recipes. Every recipe is tested and curated for the best culinary experience.</p>
            </div>
            <div class="category-hero-image">
                <img src="assets/images/category/Header Image.png" alt="<?php echo h($category_name); ?>">
            </div>
        </div>
    </section>

    <!-- Category Results -->
    <section class="category-results section-container">
        <div class="recipe-grid cards-4">
            <?php if (!empty($filtered_recipes)): ?>
                <?php foreach ($filtered_recipes as $recipe): ?>
                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                        <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                        <div class="card-content">
                            <h3><?php echo h($recipe['title']); ?></h3>
                            <div class="rating" style="margin-top: 8px;">
                                <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                                    <i class="fa-solid fa-star" style="color: var(--orange); font-size: 10px;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 0;">
                    <p style="font-size: 1.2rem; color: var(--gray-text);">No recipes found in this category yet.</p>
                    <a href="index.php" class="btn-primary" style="display: inline-block; margin-top: 1rem; text-decoration: none; padding: 0.8rem 2rem; border-radius: 4px;">Return Home</a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (count($filtered_recipes) > 8): ?>
            <div class="load-more">
                <button class="load-more-btn">Load More</button>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
