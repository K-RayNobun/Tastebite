<?php
require_once 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$recipe = $db->getRecipeById($id);

if (!$recipe) {
    $recipe = $db->getRecipeById(1);
}

$page_title = "Tastebite | " . h($recipe['title']);
$extra_css = ['recipe-page/styles.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="recipe-main">
    <!-- Breadcrumbs / Top Stats -->
    <div class="recipe-top-actions">
        <div class="made-this">
            <i class="fa-solid fa-arrow-trend-up"></i>
            <span><strong>85%</strong> would make this again</span>
        </div>
        <div class="action-buttons">
            <button aria-label="Share"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
            <button aria-label="Save"><i class="fa-regular fa-bookmark"></i></button>
        </div>
    </div>

    <h1 class="recipe-title"><?php echo h($recipe['title']); ?></h1>

    <div class="recipe-meta">
        <div class="meta-item author">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="<?php echo h($recipe['author']); ?>">
            <span><?php echo h($recipe['author']); ?></span>
        </div>
        <div class="meta-item date">
            <i class="fa-regular fa-calendar"></i>
            <span>Yesterday</span>
        </div>
        <div class="meta-item comments-count">
            <i class="fa-regular fa-message"></i>
            <span>25</span>
        </div>
        <div class="meta-item stars">
            <?php for($i=0; $i<$recipe['rating']; $i++): ?>
                <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
        </div>
    </div>

    <p class="recipe-description">
        <?php echo h($recipe['description'] ?? 'One thing I learned living in the Canarsie section of Brooklyn, NY was how to cook a good Italian meal. Here is a recipe I created after having this dish in a restaurant. Enjoy!'); ?>
    </p>

    <div class="video-container">
        <img src="<?php echo $recipe['image']; ?>" alt="<?php echo h($recipe['title']); ?>">
        <button class="play-btn"><i class="fa-solid fa-play"></i></button>
    </div>

    <div class="recipe-details-bar">
        <div class="detail-box">
            <span class="label">PREP TIME</span>
            <span class="value">15 MIN</span>
        </div>
        <div class="detail-box">
            <span class="label">BAKE TIME</span>
            <span class="value">45 MIN</span>
        </div>
        <div class="detail-box">
            <span class="label">SERVINGS</span>
            <span class="value">4 PEOPLE <i class="fa-solid fa-user-group"></i></span>
        </div>
        <button class="print-btn"><i class="fa-solid fa-print"></i></button>
    </div>

    <div class="recipe-content-grid">
        <!-- Ingredients Section -->
        <div class="ingredients-section">
            <h2>Ingredients</h2>
            <ul class="ingredient-list">
                <?php if (isset($recipe['ingredients']) && !empty($recipe['ingredients'])): ?>
                    <?php foreach ($recipe['ingredients'] as $ing): ?>
                        <li><label><input type="checkbox"><span class="custom-radio"></span> <?php echo h($ing['amount']); ?> <?php echo h($ing['unit']); ?> <?php echo h($ing['name']); ?></label></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback for seed data -->
                    <li><label><input type="checkbox"><span class="custom-radio"></span> 400g graham crackers</label></li>
                    <li><label><input type="checkbox"><span class="custom-radio"></span> 150g unsalted butters, melted</label></li>
                    <li><label><input type="checkbox"><span class="custom-radio"></span> 50g granulated sugar</label></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Instructions Section -->
        <div class="instructions-section">
            <h2>Instructions</h2>
            <ol class="instruction-list">
                <?php if (isset($recipe['instructions']) && !empty($recipe['instructions'])): ?>
                    <?php foreach ($recipe['instructions'] as $idx => $step): ?>
                        <li>
                            <span class="step-num"><?php echo $idx + 1; ?></span>
                            <p><?php echo h($step); ?></p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback for seed data -->
                    <li>
                        <span class="step-num">1</span>
                        <p>To prepare crust add graham crackers to a food processor and process until you reach fine crumbs. Add melted butter and pulse 3-4 times to coat crumbs with butter.</p>
                    </li>
                    <li>
                        <span class="step-num">2</span>
                        <p>Pour mixture into a 20cm (8") tart tin. Use the back of a spoon to firmly press the mixture out across the bottom and sides of the tart tin. Chill for 30 min.</p>
                    </li>
                    <li>
                        <span class="step-num">3</span>
                        <p>Preheat oven to 180°C (350°F). Bake crust for 10 minutes then let cool completely before adding filling.</p>
                    </li>
                <?php endif; ?>
            </ol>
        </div>
    </div>
</main>

<div class="already-made-section container">
    <h2>Already made this?</h2>
    <button class="feedback-btn" id="share-feedback">Share your feedback</button>
    <div class="divider"></div>
</div>

<div class="comments-section container">
    <h2>Comments <span id="comment-count">(0)</span></h2>
    <p style="color: var(--gray-text); margin: 2rem 0;">No comments yet. Be the first to share your thoughts!</p>
</div>

<?php 
include 'includes/feedback-modal.php';
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
