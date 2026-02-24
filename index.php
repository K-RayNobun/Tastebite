<?php
require_once 'includes/config.php';

$page_title = "Tastebite | Home";
$extra_css = ['home-page/styles.css'];

// Get data from DB
$latest_recipes = $db->getLatestRecipes(16); // All 16 latest from original
$super_delicious = $db->getSuperDeliciousRecipes();
$categories = $db->getCategories();

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <span class="trending-badge"><i class="fa-solid fa-arrow-trend-up"></i> 85% would make this again</span>
            <h1>Mighty Super Cheese-cake</h1>
            <p>Look no further for a creamy and ultra smooth classic cheesecake recipe! no one can deny its simple decadence.</p>
        </div>
        <div class="hero-image">
            <img src="home-page/hero/Image.png" alt="Mighty Super Cheesecake">
        </div>
    </section>

    <!-- Super Delicious Section (Dynamic) -->
    <section class="section-container super-delicious">
        <h2>Super Delicious</h2>
        <div class="recipe-grid cards-3">
            <?php foreach ($super_delicious as $recipe): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <img src="<?php echo $recipe['image']; ?>" alt="<?php echo h($recipe['title']); ?>">
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

    <!-- Sweet Tooth Section (Placeholder/Static from Original) -->
    <section class="section-container">
        <h2>Sweet Tooth</h2>
        <div class="recipe-grid cards-3">
            <div class="recipe-card">
                <img src="home-page/sweet-tooth/Collection/Image.png" alt="Caramel Strawberry Milkshake">
                <div class="card-content">
                    <div class="rating"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h3>Caramel Strawberry Milkshake</h3>
                </div>
            </div>
            <div class="recipe-card">
                <img src="home-page/sweet-tooth/Collection/Image-1.png" alt="Chocolate Cashew Nut Doughnut">
                <div class="card-content">
                    <div class="rating"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h3>Chocolate Cashew Nut Doughnut</h3>
                </div>
            </div>
            <div class="recipe-card">
                <img src="home-page/sweet-tooth/Collection/Image-2.png" alt="Sweet Mix Berries Strawberry Splash">
                <div class="card-content">
                    <div class="rating"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h3>Sweet Mix Berries Strawberry Splash</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories (Dynamic) -->
    <section class="section-container" id="popular">
        <h2>Popular Categories</h2>
        <div class="popular-categories">
            <?php foreach ($categories as $cat): ?>
                <a href="search.php?q=<?php echo urlencode($cat['name']); ?>" class="category-item-link" style="text-decoration: none; color: inherit;">
                    <div class="category-item">
                        <img src="<?php echo $cat['image']; ?>" alt="<?php echo h($cat['name']); ?>">
                        <span><?php echo h($cat['name']); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Hand-Picked Collections (Static from Original) -->
    <section class="section-container">
        <h2>Hand-Picked Collections</h2>
        <div class="recipe-grid cards-3">
            <div class="recipe-card">
                <img src="home-page/hand-picked/Collection/Image.png" alt="Vegan friendly">
                <div class="card-content collection-content">
                    <h3>Vegan friendly</h3>
                </div>
            </div>
            <div class="recipe-card">
                <img src="home-page/hand-picked/Collection/Image-1.png" alt="15 minute recipes">
                <div class="card-content collection-content">
                    <h3>15 minute recipes</h3>
                </div>
            </div>
            <div class="recipe-card">
                <img src="home-page/hand-picked/Collection/Image-2.png" alt="Healthy & Diet">
                <div class="card-content collection-content">
                    <h3>Healthy & Diet</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Recipes (Dynamic) -->
    <section class="section-container" id="latest">
        <h2>Latest Recipes</h2>
        <div class="recipe-grid cards-4">
            <?php foreach ($latest_recipes as $recipe): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <img src="<?php echo $recipe['image']; ?>" alt="<?php echo h($recipe['title']); ?>">
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
