<?php
require_once 'includes/config.php';

// Protect route
$auth->requireLogin();
$user = $auth->getCurrentUser();

$page_title = "Tastebite | Profile";
$extra_css = ['assets/css/pages/profile.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="profile-main">
    <h1 class="page-title">My Profile</h1>

    <div class="profile-layout container">
        <aside class="profile-sidebar">
            <nav class="sidebar-menu">
                <a href="#" class="active"><i class="fa-regular fa-user"></i> Personal Info</a>
                <a href="#"><i class="fa-regular fa-bookmark"></i> Saved Recipes</a>
                <a href="#"><i class="fa-regular fa-star"></i> Collections</a>
                <a href="#"><i class="fa-regular fa-bell"></i> Notifications</a>
                <a href="logout.php" class="logout-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
            </nav>
        </aside>

        <div class="profile-content">
            <section class="profile-section">
                <h2>Personal Info</h2>
                <div class="avatar-edit">
                    <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="<?php echo $_SESSION['user_name']; ?>">
                    <div class="avatar-actions">
                        <button class="btn-primary">Change Photo</button>
                        <button class="btn-secondary">Remove</button>
                    </div>
                </div>
                
                <div class="info-group">
                    <label>Full Name</label>
                    <p><?php echo h($_SESSION['user_name']); ?></p>
                </div>
                <div class="info-group" style="margin-top: 1rem;">
                    <label>Email Address</label>
                    <p><?php echo h($_SESSION['user_email']); ?></p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
