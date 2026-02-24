<?php
require_once 'includes/config.php';

$query = $_GET['q'] ?? '';
$page_title = "Tastebite | Search Results";

$results = $db->searchRecipes($query);
$count = count($results);

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="search-results-main">
    <div class="search-results-hero">
        <div class="container">
            <h1>Search results</h1>
            <div class="search-bar-inline">
                <input type="text" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search desserts, pasta, etc...">
                <span class="search-stats">(<?php echo $count; ?> Recipes)</span>
                <button class="close-search-page">&times;</button>
            </div>
        </div>
    </div>

    <section class="recipe-grid-section container">
        <div class="recipe-grid">
            <?php if ($count > 0): ?>
                <?php foreach ($results as $recipe): ?>
                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                        <div class="recipe-image">
                            <img src="<?php echo $recipe['image']; ?>" alt="<?php echo $recipe['title']; ?>">
                        </div>
                        <div class="recipe-info">
                            <h3><?php echo $recipe['title']; ?></h3>
                            <div class="rating">
                                <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No recipes found for "<?php echo htmlspecialchars($query); ?>". Try a different search term.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($count > 20): ?>
            <div class="load-more">
                <button class="btn-secondary">Load More</button>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
