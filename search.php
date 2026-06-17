<?php
require_once 'includes/config.php';

$query = $_GET['q'] ?? '';
$page_title = "Tastebite | Search Results for \"" . h($query) . "\"";
$extra_css = ['assets/css/pages/search.css', 'assets/css/pages/home.css'];

// Get Search Results (Issue #5)
$results = $db->getRecipes(['status' => 'all', 'search' => $query]);
$count = count($results);

// Get suggestions if empty (Wow Factor)
$suggestions = $db->getRecipes(['status' => 'super_delicious', 'limit' => 4]);

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="search-results-main">
    <div class="search-results-hero">
        <div class="container">
            <h1>Search results</h1>
            <div class="search-bar-inline">
                <input type="text" id="inline-search-input" value="<?php echo h($query); ?>" placeholder="Search desserts, pasta, etc...">
                <span class="search-stats">(<?php echo $count; ?> Recipes)</span>
                <button class="close-search-page" onclick="window.location.href='index.php'">&times;</button>
            </div>
        </div>
    </div>

    <section class="recipe-grid-section container">
        <?php if ($count > 0): ?>
            <div class="recipe-grid cards-4">
                <?php foreach ($results as $recipe): ?>
                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                        <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                        <div class="card-content">
                            <h3><?php echo h($recipe['title']); ?></h3>
                            <div class="rating" style="margin-top: 8px;">
                                <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-cloud-showers-heavy"></i>
                <h3>No results for "<?php echo h($query); ?>"</h3>
                <p>Seems like we couldn't find exactly that. Why not try one of our favorites below?</p>
                
                <div class="suggested-recipes-header">
                    <h2>Our Recommendations</h2>
                </div>
                
                <div class="recipe-grid cards-4">
                    <?php foreach ($suggestions as $recipe): ?>
                        <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                            <img src="<?php echo get_recipe_image($recipe['image']); ?>" alt="<?php echo h($recipe['title']); ?>">
                            <div class="card-content">
                                <h3><?php echo h($recipe['title']); ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
    // Inline search redirect
    const inlineSearch = document.getElementById('inline-search-input');
    if (inlineSearch) {
        inlineSearch.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && inlineSearch.value.trim()) {
                window.location.href = `search.php?q=${encodeURIComponent(inlineSearch.value.trim())}`;
            }
        });
    }
</script>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
