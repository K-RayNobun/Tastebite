<?php
require_once 'includes/config.php';

$page_title = "Tastebite | Home";
$extra_css = ['assets/css/pages/home.css'];

// Get data from DB
$latest_recipes = $db->getLatestRecipes(16); // All 16 latest from original
$super_delicious = $db->getSuperDeliciousRecipes();
$categories = $db->getCategories();

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
    <!-- Hero Section (Dynamic) -->
    <?php 
    $hero_recipe = !empty($super_delicious) ? $super_delicious[array_rand($super_delicious)] : null;
    if ($hero_recipe):
    ?>
    <section class="hero-section">
        <div class="hero-content">
            <span class="trending-badge"><i class="fa-solid fa-arrow-trend-up"></i> 85% would make this again</span>
            <h1><?php echo h($hero_recipe['title']); ?></h1>
            <p>Delicious <?php echo strtolower(h($hero_recipe['category'])); ?> recipe curated by our chefs. Master the art of cooking with Tastebite's easy-to-follow instructions.</p>
            <a href="recipe.php?id=<?php echo $hero_recipe['id']; ?>" class="btn-primary" style="display: inline-block; margin-top: 20px; text-decoration: none; padding: 12px 32px; border-radius: 4px;">View Recipe</a>
        </div>
        <div class="hero-image">
            <img src="assets/images/hero/Image.png" alt="<?php echo h($hero_recipe['title']); ?>">
        </div>
    </section>
    <?php else: ?>
    <section class="hero-section">
        <div class="hero-content">
            <span class="trending-badge"><i class="fa-solid fa-arrow-trend-up"></i> 85% would make this again</span>
            <h1>Mighty Super Cheese-cake</h1>
            <p>Look no further for a creamy and ultra smooth classic cheesecake recipe! no one can deny its simple decadence.</p>
        </div>
        <div class="hero-image">
            <img src="assets/images/hero/Image.png" alt="Mighty Super Cheesecake">
        </div>
    </section>
    <?php endif; ?>

    <!-- Super Delicious Section (Dynamic) -->
    <section class="section-container super-delicious">
        <h2>Super Delicious</h2>
        <div class="recipe-grid cards-3">
            <?php foreach ($super_delicious as $recipe): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                    <div class="card-content">
                        <div class="rating">
                            <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                                <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <h3><?php echo h($recipe['title']); ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Sweet Tooth Section (Dynamic from DB) -->
    <?php 
    $sweet_tooth = $db->getRecipes(['category' => 'Dessert', 'limit' => 3]);
    if (!empty($sweet_tooth)):
    ?>
    <section class="section-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Sweet Tooth</h2>
            <a href="category.php?name=Dessert" style="color: var(--orange); text-decoration: none; font-weight: 600;">See all Desserts</a>
        </div>
        <div class="recipe-grid cards-3">
            <?php foreach (array_slice($sweet_tooth, 0, 3) as $recipe): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                    <div class="card-content">
                        <div class="rating">
                            <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                                <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <h3><?php echo h($recipe['title']); ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Popular Categories (Dynamic Accessibility Overhaul - Issue #11) -->
    <section class="section-container" id="popular">
        <h2>Popular Categories</h2>
        <nav aria-label="Popular Recipe Categories">
            <ul class="popular-categories" role="list" style="list-style: none; padding: 0;">
                <?php foreach ($categories as $cat): ?>
                    <li role="listitem">
                        <a href="category.php?name=<?php echo urlencode($cat['name']); ?>" class="category-item-link" title="View all <?php echo h($cat['name']); ?> recipes">
                            <div class="category-item">
                                <img src="<?php echo get_recipe_image($cat['image']); ?>" alt="">
                                <span><?php echo h($cat['name']); ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </section>

    <!-- Dynamic Collections Section -->
    <section class="section-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Hand-Picked Collections</h2>
            <a href="categories.php" style="color: var(--orange); text-decoration: none; font-weight: 600;">Browse all</a>
        </div>
        <div class="recipe-grid cards-3">
            <?php 
            $collections = array_slice($categories, 2, 3); // Take a different slice for variety
            foreach ($collections as $col): 
            ?>
                <a href="category.php?name=<?php echo urlencode($col['name']); ?>" class="recipe-card">
                    <img src="<?php echo $col['image']; ?>" alt="<?php echo h($col['name']); ?>">
                    <div class="card-content collection-content">
                        <h3><?php echo h($col['name']); ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Latest Recipes (Dynamic) -->
    <section class="section-container" id="latest">
        <h2>Latest Recipes</h2>
        <div class="recipe-grid cards-4">
            <?php foreach ($latest_recipes as $recipe): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                    <div class="card-content">
                        <h3><?php echo h($recipe['title']); ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="load-more">
            <button class="load-more-btn">Load More</button>
        </div>
    </section>
</main>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
