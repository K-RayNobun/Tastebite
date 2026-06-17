<?php
require_once 'includes/config.php';

// Protect route
$auth->requireLogin();

$success = '';
$error = '';

$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$edit_recipe = null;

if ($recipe_id) {
    $edit_recipe = $db->getRecipeById($recipe_id);
    // Security: Only allow editing own recipes
    if (!$edit_recipe || $edit_recipe['author_id'] != $_SESSION['user_id']) {
        $edit_recipe = null;
        $recipe_id = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    
    if (!$auth->validateToken($token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $recipe_id = isset($_POST['recipe_id']) ? (int)$_POST['recipe_id'] : null;
        $title = sanitize($_POST['title'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $image = sanitize($_POST['image_url'] ?? 'assets/images/hero/Image.png');
        $rating = (int)($_POST['rating'] ?? 5);

        // Handle Image Upload
        if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['recipe_image']['tmp_name'];
            $fileName = $_FILES['recipe_image']['name'];
            $fileSize = $_FILES['recipe_image']['size'];
            $fileType = $_FILES['recipe_image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = './uploads/';
                $dest_path = $uploadFileDir . $newFileName;
                
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $image = 'uploads/' . $newFileName;
                } else {
                    $error = 'There was an error moving the uploaded file.';
                }
            } else {
                $error = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            }
        }

        if (!$error) {
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

            if ($recipe_id) {
                if ($db->updateRecipe($recipe_id, $recipeData)) {
                    $success = 'Recipe updated successfully!';
                    $edit_recipe = $db->getRecipeById($recipe_id); // Refresh data
                } else {
                    $error = 'Failed to update recipe.';
                }
            } else {
                if ($db->addRecipe($recipeData)) {
                    $success = 'Recipe published successfully!';
                } else {
                    $error = 'Failed to publish recipe.';
                }
            }
        }
    }
}

$categories = $db->getCategories();
$page_title = "Tastebite | Creator Board";
$extra_css = ['assets/css/creator.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<div class="creator-board-hero">
    <div class="container hero-layout">
        <div class="hero-text-content">
            <h1>Creator Board</h1>
            <p>Welcome back, <strong><?php echo h($_SESSION['user_name']); ?></strong>! Ready to share your culinary masterpiece?</p>
        </div>
        <div class="hero-badge">
            <i class="fa-solid fa-award"></i>
            <span>Master Chef</span>
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

            <div class="creator-form-card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-utensils"></i> Post New Recipe</h2>
                    <p>Fill in the details below to publish your recipe to the Tastebite community.</p>
                </div>
                <form action="creator-board.php<?php echo $edit_recipe ? '?id='.$edit_recipe['id'] : ''; ?>" method="POST" id="recipe-publish-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $auth->generateToken(); ?>">
                    <?php if ($edit_recipe): ?>
                        <input type="hidden" name="recipe_id" value="<?php echo $edit_recipe['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-section-header">
                        <h3>General Information</h3>
                    </div>
                    <div class="input-grid">
                        <div class="input-group">
                            <label>Title</label>
                            <input type="text" name="title" required placeholder="Recipe Title" value="<?php echo $edit_recipe ? h($edit_recipe['title']) : ''; ?>" style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
                        </div>
                        
                        <div class="input-group">
                            <label>Category</label>
                            <select name="category" required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent; appearance: none;">
                                <option value="" disabled <?php echo !$edit_recipe ? 'selected' : ''; ?>>Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo h($cat['name']); ?>" <?php echo ($edit_recipe && $edit_recipe['category'] == $cat['name']) ? 'selected' : ''; ?>>
                                        <?php echo h($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="input-grid" style="margin-top: 30px;">
                        <div class="input-group">
                            <label>Recipe Image (Upload)</label>
                            <input type="file" name="recipe_image" id="recipe-image-file" accept="image/*" style="padding: 10px 0;">
                            <label style="margin-top: 10px;">Or Image URL</label>
                            <input type="text" name="image_url" id="image-url-input" placeholder="Paste image link..." style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
                            <div class="image-preview-container" id="image-preview" style="margin-top: 15px;">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Difficulty Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" value="<?php echo $edit_recipe ? $edit_recipe['rating'] : '5'; ?>" style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
                        </div>
                    </div>

                    <div class="form-section-header" style="margin-top: 60px;">
                        <h3 style="display: flex; justify-content: space-between; width: 100%;">
                            Ingredients
                            <button type="button" class="btn-circle" id="add-ingredient" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--orange); color: #fff; cursor: pointer;"><i class="fa-solid fa-plus"></i></button>
                        </h3>
                    </div>

                    <div id="ingredients-container">
                        <?php 
                        $ings = ($edit_recipe && !empty($edit_recipe['ingredients'])) ? $edit_recipe['ingredients'] : [['name'=>'', 'amount'=>'', 'unit'=>'units']];
                        foreach ($ings as $idx => $ing): ?>
                        <div class="ingredient-row">
                            <div class="input-group" style="flex: 3;">
                                <input type="text" name="ing_name[]" value="<?php echo h($ing['name']); ?>" placeholder="Ingredient name" required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
                            </div>
                            <div class="input-group" style="flex: 1;">
                                <input type="text" name="ing_quantity[]" value="<?php echo h($ing['amount']); ?>" placeholder="Qty" required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
                            </div>
                            <div class="input-group" style="flex: 1;">
                                <select name="ing_unit[]" style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent; appearance: none;">
                                    <?php $units = ['units', 'g', 'kg', 'ml', 'L', 'tsp', 'tbsp', 'cups']; 
                                    foreach ($units as $u): ?>
                                        <option value="<?php echo $u; ?>" <?php echo $ing['unit'] == $u ? 'selected' : ''; ?>><?php echo $u; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ($idx > 0): ?>
                                <button type="button" class="btn-remove"><i class="fa-solid fa-xmark"></i></button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-section-header" style="margin-top: 60px;">
                        <h3 style="display: flex; justify-content: space-between; width: 100%;">
                            Instructions
                            <button type="button" class="btn-circle" id="add-instruction" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--orange); color: #fff; cursor: pointer;"><i class="fa-solid fa-plus"></i></button>
                        </h3>
                    </div>

                    <div id="instructions-container">
                        <?php 
                        $insts = ($edit_recipe && !empty($edit_recipe['instructions'])) ? $edit_recipe['instructions'] : [''];
                        foreach ($insts as $idx => $inst): ?>
                        <div class="instruction-row">
                            <span class="step-num" style="width: 24px; height: 24px; background: var(--orange); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0; margin-top: 5px;"><?php echo $idx + 1; ?></span>
                            <div class="input-group" style="flex: 1;">
                                <textarea name="instruction[]" placeholder="Describe this step..." required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 10px; min-height: 60px; outline: none; background: transparent; resize: none;"><?php echo h($inst); ?></textarea>
                            </div>
                            <?php if ($idx > 0): ?>
                                <button type="button" class="btn-remove"><i class="fa-solid fa-xmark"></i></button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn-primary publish-btn" style="width: 100%; padding: 20px; border-radius: 40px; border: none; background: var(--orange); color: #fff; font-size: 20px; font-weight: 700; cursor: pointer; margin-top: 60px; box-shadow: 0 10px 20px rgba(255, 100, 47, 0.2);">Publish Masterpiece</button>
                </form>
            </div>
        </section>

        <aside class="creator-sidebar">
            <div class="sidebar-widget stats-widget">
                <h3>Your Activity</h3>
                <div class="stats-grid" style="display: flex; gap: 20px;">
                    <div class="stat-item" style="flex: 1; text-align: center;">
                        <strong style="display: block; font-size: 24px;"><?php echo count($my_recipes); ?></strong>
                        <span style="font-size: 12px; color: var(--gray-text); text-transform: uppercase;">Recipes</span>
                    </div>
                    <div class="stat-item" style="flex: 1; text-align: center;">
                        <strong style="display: block; font-size: 24px;"><?php echo count($my_recipes) > 0 ? round($total_rating / count($my_recipes), 1) : '0'; ?></strong>
                        <span style="font-size: 12px; color: var(--gray-text); text-transform: uppercase;">Avg Rating</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-widget recent-widget">
                <h3>My Recent Posts</h3>
                <div class="mini-recipe-list">
                    <?php 
                    $recent_my = array_slice(array_reverse($my_recipes), 0, 5);
                    if (empty($recent_my)): ?>
                        <p style="font-size: 14px; color: var(--gray-text); text-align: center; padding: 20px 0;">You haven't posted any recipes yet.</p>
                    <?php else: 
                        foreach ($recent_my as $mr): ?>
                        <div class="mini-recipe">
                            <img src="<?php echo get_recipe_image($mr['image']); ?>?w=50" alt="" onerror="this.src='https://via.placeholder.com/50?text=Recipe'">
                            <div class="mini-info">
                                <h4><?php echo h($mr['title']); ?></h4>
                                <span><?php echo h($mr['category']); ?></span>
                            </div>
                            <div class="mini-actions" style="margin-left: auto; display: flex; gap: 8px;">
                                <a href="creator-board.php?id=<?php echo $mr['id']; ?>" class="edit-link" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                <button class="delete-recipe-btn" data-id="<?php echo $mr['id']; ?>" title="Delete" style="background: none; border: none; color: #ff4d4d; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </aside>
    </div>
</main>

<style>
.hero-layout { display: flex; justify-content: space-between; align-items: center; }
.hero-badge { background: #fff; padding: 12px 24px; border-radius: 40px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.hero-badge i { color: #f1c40f; font-size: 20px; }
.hero-badge span { font-weight: 700; color: var(--black); }
.image-preview-container img { width: 100%; height: 100%; object-fit: cover; }
.btn-remove { background: none; border: none; color: #ccc; cursor: pointer; transition: color 0.3s; margin-left:10px;}
.btn-remove:hover { color: var(--orange); }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Dynamic Ingredients
    const addIngBtn = document.getElementById('add-ingredient');
    const ingContainer = document.getElementById('ingredients-container');
    
    addIngBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'ingredient-row';
        row.style.display = 'flex';
        row.style.gap = '15px';
        row.style.marginBottom = '15px';
        row.innerHTML = `
            <div class="input-group" style="flex: 3;">
                <input type="text" name="ing_name[]" placeholder="Ingredient" required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
            </div>
            <div class="input-group" style="flex: 1;">
                <input type="text" name="ing_quantity[]" placeholder="Qty" required style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent;">
            </div>
            <div class="input-group" style="flex: 1;">
                <select name="ing_unit[]" style="width: 100%; border: none; border-bottom: 1px solid var(--border-color); padding: 12px 10px; outline: none; background: transparent; appearance: none;">
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

    // Image Preview Logic
    const imageInput = document.getElementById('image-url-input');
    const fileInput = document.getElementById('recipe-image-file');
    const imagePreview = document.getElementById('image-preview');

    const updatePreview = (url, isFile = false) => {
        if (url) {
            imagePreview.innerHTML = `<img src="${url}" alt="Preview" onerror="this.parentElement.innerHTML='<i class=\'fa-solid fa-circle-exclamation\' style=\'color: var(--orange)\'></i>'">`;
        } else {
            imagePreview.innerHTML = '<i class="fa-regular fa-image"></i>';
        }
    }

    imageInput.addEventListener('input', (e) => {
        if (!fileInput.files.length) {
            updatePreview(e.target.value.trim());
        }
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => updatePreview(event.target.result, true);
            reader.readAsDataURL(file);
        } else {
            updatePreview(imageInput.value.trim());
        }
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

    // Image Compression Helper (Issue #4)
    const compressImage = async (file, maxWidth = 1200, quality = 0.7) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth) {
                        height = (maxWidth / width) * height;
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        resolve(blob);
                    }, 'image/webp', quality); // Default to WebP for best compression
                };
            };
        });
    };

    // Form Validation & Async Submission (Issue #8 & #4)
    const publishForm = document.getElementById('recipe-publish-form');
    if (publishForm) {
        publishForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            let hasError = false;
            const requiredFields = publishForm.querySelectorAll('[required]');
            const submitBtn = publishForm.querySelector('.publish-btn');
            const originalText = submitBtn.innerText;

            requiredFields.forEach(field => {
                field.classList.remove('input-error');
                if (!field.value.trim()) {
                    field.classList.add('input-error');
                    hasError = true;
                }
            });

            if (hasError) {
                showToast('Please fill in all required fields.');
                const firstError = publishForm.querySelector('.input-error');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Start Submission
            submitBtn.classList.add('btn-loading');
            submitBtn.innerText = 'Optimizing & Publishing...';
            submitBtn.disabled = true;

            const formData = new FormData(publishForm);
            const fileField = document.getElementById('recipe-image-file');
            
            // Client-Side Compression (Fix #4)
            if (fileField.files.length > 0) {
                const compressedBlob = await compressImage(fileField.files[0]);
                formData.set('recipe_image', compressedBlob, 'recipe.webp');
            }

            // AJAX Submit
            fetch(publishForm.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.text()) // We return text because creator-board.php might return HTML or redirects
            .then(html => {
                // Check if success toast should be shown (simulated from PHP response)
                if (html.includes('successfully')) {
                    showToast('Masterpiece Published!');
                    setTimeout(() => window.location.href = 'creator-board.php', 1500);
                } else {
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const errorMsg = temp.querySelector('.error-message')?.innerText || 'Failed to publish.';
                    showToast(errorMsg);
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error('Submission error:', err);
                showToast('Network error. Please try again.');
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
        });

        // Real-time error cleanup
        publishForm.addEventListener('input', (e) => {
            if (e.target.classList.contains('input-error')) {
                if (e.target.value.trim()) e.target.classList.remove('input-error');
            }
        });
    }

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
                    r.querySelector('.step-num').innerText = idx + 1;
                });
            }
        }
        
        if (e.target.closest('.delete-recipe-btn')) {
            const btn = e.target.closest('.delete-recipe-btn');
            const recipeId = btn.dataset.id;
            
            if (confirm('Are you absolute sure you want to delete this recipe? This action cannot be undone.')) {
                fetch('api/delete-recipe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ recipe_id: recipeId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Recipe deleted successfully.');
                        window.location.href = 'creator-board.php';
                    } else {
                        showToast('Failed to delete: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(err => {
                    console.error('Error deleting recipe:', err);
                    showToast('Communication error. Please try again.');
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
