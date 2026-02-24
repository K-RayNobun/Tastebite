<?php
require_once 'includes/config.php';

$page_title = "Tastebite | Categories";
$extra_css = ['categories-home-page/styles.css'];

// Get categories from DB
$all_categories = $db->getCategories();

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
    <section class="categories-section">
        <h1>Categories</h1>
        <div class="category-grid">
            <?php foreach ($all_categories as $cat): ?>
                <a href="search.php?q=<?php echo urlencode($cat['name']); ?>" class="category-item">
                    <img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">
                    <div class="name"><?php echo h($cat['name']); ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php 
include 'includes/footer.php';
include 'includes/foot.php';
?>
