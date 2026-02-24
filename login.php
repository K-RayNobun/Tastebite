<?php
require_once 'includes/config.php';

$extra_css = ['components/modals/styles.css'];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    
    if (!$auth->validateToken($token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; // Don't sanitize passwords as they can have special chars

        if ($auth->login($email, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$page_title = "Tastebite | Login";
include 'includes/head.php';
include 'includes/header.php';
?>

<main class="modals-container">
    <div class="modal-card auth-modal">
        <h2>LOGIN</h2>
        
        <?php if ($error): ?>
            <div class="error-message" style="color: var(--danger-red); margin-bottom: 20px; font-size: 14px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateToken(); ?>">
            
            <div class="input-group">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" required placeholder="Email" value="<?php echo isset($email) ? h($email) : ''; ?>">
            </div>
            
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" required placeholder="Password">
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-primary btn-full">Login</button>

            <div class="divider">
                <span>Or login with</span>
            </div>

            <div class="social-login">
                <button type="button" class="btn-social btn-facebook">
                    <i class="fa-brands fa-facebook-f"></i> Facebook
                </button>
                <button type="button" class="btn-social btn-google">
                    <img src="profile-page/images/accounts/Google Logo.svg" alt="Google" width="18"> Google
                </button>
            </div>

            <div class="auth-footer">
                Don't have an account? <a href="signup.php">Sign up</a>
            </div>
        </form>
    </div>
</main>

<?php 
include 'includes/footer.php';
include 'includes/foot.php';
?>
