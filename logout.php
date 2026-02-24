<?php
require_once 'includes/config.php';

// If confirmed, logout
if (isset($_POST['confirm_logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$page_title = "Tastebite | Log Out";
$extra_css = ['components/modals/styles.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="modals-container">
    <div class="modal-card delete-modal">
        <h2>Are you sure you want to log out?</h2>
        <p>You will need to re-enter your credentials to access your profile and creator board.</p>

        <form action="logout.php" method="POST" class="mt-32">
            <div class="action-buttons">
                <button type="submit" name="confirm_logout" class="btn-primary">Log Out</button>
                <a href="index.php" class="btn-outline" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php 
include 'includes/footer.php';
include 'includes/foot.php';
?>
