<?php
require_once 'includes/config.php';

// Protect route
$auth->requireLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    
    if (!$auth->validateToken($token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = sanitize($_POST['title'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $image = sanitize($_POST['image_url'] ?? 'home-page/latest-recipes/Recipe/Image.png');
        $rating = (int)($_POST['rating'] ?? 5);

        // Process Ingredients
        $ingredients = [];
        if (isset($_POST['ing_name'])) {
            for ($i = 0; $i < count($_POST['ing_name']); $i++) {
                if (!empty($_POST['ing_name'][$i])) {
                    $ingredients[] = [
                        'name' => sanitize($_POST['ing_name'][$i]),
                        'amount' => sanitize($_POST['ing_quantity'][$i]),
                        'unit' => sanitize($_POST['ing_unit'][$i])
                    ];
                }
            }
        }

        // Process Instructions
        $instructions = [];
        if (isset($_POST['instruction'])) {
            foreach ($_POST['instruction'] as $step) {
                if (!empty($step)) $instructions[] = sanitize($step);
            }
        }

        $recipeData = [
            'title' => $title,
            'category' => $category,
            'image' => $image,
            'author' => $_SESSION['user_name'],
            'author_id' => $_SESSION['user_id'],
            'rating' => $rating,
            'is_latest' => true,
            'is_super_delicious' => false,
            'ingredients' => $ingredients,
            'instructions' => $instructions
        ];

        if ($db->addRecipe($recipeData)) {
            $success = 'Recipe published successfully!';
        } else {
            $error = 'Failed to publish recipe.';
        }
    }
}

$categories = $db->getCategories();
$page_title = "Tastebite | Creator Board";
$extra_css = ['components/modals/styles.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<div class="creator-board-hero">
    <div class="container">
        <div class="hero-text-content">
            <h1>Creator Board</h1>
            <p>Welcome back, <strong><?php echo h($_SESSION['user_name']); ?></strong>! Ready to share your culinary masterpiece?</p>
        </div>
    </div>
</div>

<main class="creator-board container">
    <div class="creator-layout">
        <section class="recipe-form-section">
            <?php if ($success): ?>
                <script>document.addEventListener('DOMContentLoaded', () => showToast('<?php echo $success; ?>'));</script>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message" style="background: #fff5f2; color: var(--orange); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--orange);">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="modal-card creator-form-card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-utensils"></i> Post New Recipe</h2>
                    <p>Fill in the details below to publish your recipe.</p>
                </div>
                <form action="creator-board.php" method="POST" class="auth-form complex-recipe-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $auth->generateToken(); ?>">
                    
                    <div class="form-section-title">General Information</div>
                    <div class="input-grid">
                        <div class="input-group">
                            <i class="fa-solid fa-heading"></i>
                            <input type="text" name="title" required placeholder="Recipe Title">
                        </div>
                        
                        <div class="input-group">
                            <i class="fa-solid fa-list"></i>
                            <select name="category" required>
                                <option value="" disabled selected>Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo h($cat['name']); ?>"><?php echo h($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="input-grid">
                        <div class="input-group">
                            <i class="fa-regular fa-image"></i>
                            <input type="text" name="image_url" placeholder="Image URL">
                        </div>
                        <div class="input-group">
                            <i class="fa-regular fa-star"></i>
                            <input type="number" name="rating" min="1" max="5" value="5" placeholder="Rating">
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-section-header">
                        <div class="form-section-title">Ingredients</div>
                        <button type="button" class="btn-circle" id="add-ingredient"><i class="fa-solid fa-plus"></i></button>
                    </div>

                    <div id="ingredients-container">
                        <div class="ingredient-row">
                            <div class="input-group flex-3">
                                <i class="fa-solid fa-pepper-hot"></i>
                                <input type="text" name="ing_name[]" placeholder="Ingredient (e.g. Flour)" required>
                            </div>
                            <div class="input-group flex-1">
                                <input type="text" name="ing_quantity[]" placeholder="Qty" required>
                            </div>
                            <div class="input-group flex-1">
                                <select name="ing_unit[]">
                                    <option value="units">units</option>
                                    <option value="g">g</option>
                                    <option value="kg">kg</option>
                                    <option value="ml">ml</option>
                                    <option value="L">L</option>
                                    <option value="tsp">tsp</option>
                                    <option value="tbsp">tbsp</option>
                                    <option value="cups">cups</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-section-header">
                        <div class="form-section-title">Instructions</div>
                        <button type="button" class="btn-circle" id="add-instruction"><i class="fa-solid fa-plus"></i></button>
                    </div>

                    <div id="instructions-container">
                        <div class="instruction-row">
                            <span class="step-number">1</span>
                            <div class="input-group full-width">
                                <textarea name="instruction[]" placeholder="Describe this step..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary btn-full publish-btn">Publish Masterpiece</button>
                </form>
            </div>
        </section>

        <aside class="creator-sidebar">
            <div class="sidebar-widget stats-widget">
                <h3>Your Impact</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <strong><?php echo count($db->getRecipes(fn($r) => $r['author_id'] == $_SESSION['user_id'])); ?></strong>
                        <span>Recipes</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php 
                            $my_recipes = $db->getRecipes(fn($r) => $r['author_id'] == $_SESSION['user_id']);
                            $total_rating = array_reduce($my_recipes, fn($carry, $item) => $carry + $item['rating'], 0);
                            echo count($my_recipes) > 0 ? round($total_rating / count($my_recipes), 1) : '0';
                        ?></strong>
                        <span>Avg Rating</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-widget recipes-widget">
                <h3>Recent Posts</h3>
                <div class="mini-recipe-list">
                    <?php 
                    $recent_my = array_slice(array_reverse($my_recipes), 0, 5);
                    if (empty($recent_my)): ?>
                        <p class="empty-state">You haven't posted any recipes yet.</p>
                    <?php else: 
                        foreach ($recent_my as $mr): ?>
                        <div class="mini-recipe">
                            <img src="<?php echo $mr['image']; ?>" alt="">
                            <div class="mini-info">
                                <h4><?php echo h($mr['title']); ?></h4>
                                <span><?php echo h($mr['category']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </aside>
    </div>
</main>

<style>
.creator-form-card { padding: 40px; }
.form-section-title { font-weight: 700; font-size: 1.1rem; color: var(--black); margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
.form-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.form-divider { height: 1px; background: #eee; margin: 30px 0; }
.input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.ingredient-row, .instruction-row { position: relative; }
.btn-remove { position: absolute; right: -40px; top: 10px; background: none; border: none; color: #ccc; cursor: pointer; font-size: 1.2rem; transition: color 0.2s; }
.btn-remove:hover { color: var(--orange); }
.ingredient-row { padding-right: 0; }
@media (max-width: 1100px) {
    .btn-remove { position: static; margin-left: 10px; }
    .ingredient-row, .instruction-row { align-items: center; }
}
.step-number { width: 32px; height: 32px; background: var(--orange-light); color: var(--orange); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; margin-top: 5px; }
.flex-3 { flex: 3; }
.flex-1 { flex: 1; }
.full-width { width: 100%; }
.btn-circle { width: 36px; height: 36px; border-radius: 50%; border: none; background: var(--orange); color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s; }
.btn-circle:hover { transform: rotate(90deg); }
.complex-recipe-form select { width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px 12px 35px; background: none; appearance: none; outline: none; }
.ingredient-row select { padding-left: 10px; }
.complex-recipe-form textarea { width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 10px; min-height: 80px; resize: none; outline: none; background: transparent; }
.publish-btn { margin-top: 40px; padding: 20px !important; font-size: 20px !important; border-radius: 12px !important; }

@media (max-width: 900px) {
    .creator-layout { grid-template-columns: 1fr; }
    .creator-board-hero { padding: 60px 20px; text-align: center; }
    .creator-board-hero h1 { font-size: 2.5rem; }
    .input-grid { grid-template-columns: 1fr; gap: 0; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Dynamic Ingredients
    const addIngBtn = document.getElementById('add-ingredient');
    const ingContainer = document.getElementById('ingredients-container');
    
    addIngBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'ingredient-row';
        row.innerHTML = `
            <div class="input-group flex-3">
                <i class="fa-solid fa-pepper-hot"></i>
                <input type="text" name="ing_name[]" placeholder="Ingredient" required>
            </div>
            <div class="input-group flex-1">
                <input type="text" name="ing_quantity[]" placeholder="Qty" required>
            </div>
            <div class="input-group flex-1">
                <select name="ing_unit[]">
                   <option value="units">units</option>
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="ml">ml</option>
                    <option value="L">L</option>
                    <option value="tsp">tsp</option>
                    <option value="tbsp">tbsp</option>
                    <option value="cups">cups</option>
                </select>
            </div>
            <button type="button" class="btn-remove"><i class="fa-solid fa-xmark"></i></button>
        `;
        ingContainer.appendChild(row);
    });

    // Dynamic Instructions
    const addInstBtn = document.getElementById('add-instruction');
    const instContainer = document.getElementById('instructions-container');
    
    addInstBtn.addEventListener('click', () => {
        const stepCount = instContainer.querySelectorAll('.instruction-row').length + 1;
        const row = document.createElement('div');
        row.className = 'instruction-row';
        row.innerHTML = `
            <span class="step-number">${stepCount}</span>
            <div class="input-group full-width">
                <textarea name="instruction[]" placeholder="Describe this step..." required></textarea>
            </div>
            <button type="button" class="btn-remove"><i class="fa-solid fa-xmark"></i></button>
        `;
        instContainer.appendChild(row);
    });

    // Remove Logic (Delegation)
    document.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove')) {
            const btn = e.target.closest('.btn-remove');
            const row = btn.parentElement;
            const container = row.parentElement;
            row.remove();
            
            // Re-index instructions if needed
            if (container.id === 'instructions-container') {
                container.querySelectorAll('.instruction-row').forEach((r, idx) => {
                    r.querySelector('.step-number').innerText = idx + 1;
                });
            }
        }
    });
});
</script>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
