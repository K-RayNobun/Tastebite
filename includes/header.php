<header class="header">
    <div class="logo">
        <a href="index.php">
            <img src="about-page/images/logo.png" alt="Tastebite" height="32">
        </a>
    </div>
    <nav class="nav">
        <button class="nav-toggle" id="nav-toggle" aria-label="Toggle navigation menu">&#9776;</button>
        <ul id="nav-menu">
            <li class="has-dropdown">
                <a href="index.php" aria-haspopup="true" aria-expanded="false">Homepage <i class="fa-solid fa-chevron-down dropdown" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="index.php">Main Home</a></li>
                    <li><a href="index.php#latest">Latest Recipes</a></li>
                    <li><a href="index.php#popular">Popular Categories</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="recipe.php?id=1" aria-haspopup="true" aria-expanded="false">Recipe Page <i class="fa-solid fa-chevron-down dropdown" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <?php 
                    $nav_recipes = $db->getLatestRecipes(3);
                    foreach ($nav_recipes as $nr): ?>
                        <li><a href="recipe.php?id=<?php echo $nr['id']; ?>"><?php echo h($nr['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a href="creator-board.php">Creator Board</a>
            </li>
            <li class="has-dropdown">
                <a href="categories.php" aria-haspopup="true" aria-expanded="false">Categories <i class="fa-solid fa-chevron-down dropdown" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <?php 
                    $nav_cats = $db->getCategories();
                    foreach ($nav_cats as $nc): ?>
                        <li><a href="category.php?name=<?php echo urlencode($nc['name']); ?>"><?php echo h($nc['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="#">More <i class="fa-solid fa-chevron-down dropdown"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="about.php">About Us</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
    <div class="header-actions">
        <a href="#" id="search-btn" aria-label="Open search"><i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i></a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-menu" id="user-menu-trigger">
                <div class="profile-wrapper">
                    <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="<?php echo $_SESSION['user_name']; ?>" class="profile-img">
                    <i class="fa-solid fa-chevron-down profile-arrow"></i>
                </div>
                <div class="user-dropdown" id="user-dropdown-menu">
                    <div class="user-info-brief">
                        <strong><?php echo h($_SESSION['user_name']); ?></strong>
                        <span><?php echo h($_SESSION['user_email']); ?></span>
                    </div>
                    <hr>
                    <a href="profile.php"><i class="fa-regular fa-user"></i> My Profile</a>
                    <a href="creator-board.php"><i class="fa-solid fa-plus"></i> Post Recipe</a>
                    <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-primary" style="padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">Log In</a>
        <?php endif; ?>
    </div>
</header>

<!-- Search Modal (Accessibility Overhaul - Issue #11) -->
<div id="search-modal" class="search-modal" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
    <h2 id="search-modal-title" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;">Search Recipes</h2>
    <div class="search-container">
        <span class="close-search" id="close-search">&times;</span>
        <div class="search-input-group">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="search-input" placeholder="Type to search recipes..." autocomplete="off">
        </div>
        <div id="search-live-results" class="search-live-results"></div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="#" id="see-all-results" class="btn-primary" style="display: none; padding: 0.8rem 2rem; border-radius: 4px; text-decoration: none;">See all results</a>
        </div>
    </div>
</div>

<style>
.profile-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.profile-arrow {
    font-size: 0.7rem;
    color: var(--gray-text);
    transition: transform 0.3s;
}

.user-menu:hover .profile-arrow {
    transform: rotate(180deg);
}

.user-info-brief {
    padding: 10px 20px;
    display: flex;
    flex-direction: column;
}

.user-info-brief strong {
    font-size: 14px;
    color: var(--black);
}

.user-info-brief span {
    font-size: 11px;
    color: var(--gray-text);
}

.user-dropdown hr {
    border: 0;
    border-top: 1px solid var(--border-color);
    margin: 5px 0;
}

.user-dropdown a i {
    margin-right: 10px;
    width: 14px;
}

.logout-link {
    color: var(--orange) !important;
}

.logout-link:hover {
    background: #fff5f2 !important;
}
</style>
