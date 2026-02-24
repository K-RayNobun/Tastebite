<?php
require_once 'includes/config.php';

$error = '';
$extra_css = ['components/modals/styles.css'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    
    if (!$auth->validateToken($token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } elseif ($db->getUser($email)) {
            $error = 'Email already registered.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $user = $db->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password,
                'avatar' => 'https://randomuser.me/api/portraits/lego/1.jpg' // Default avatar
            ]);

            if ($user) {
                if ($auth->login($email, $password)) {
                    header('Location: index.php');
                    exit;
                }
            } else {
                $error = 'Failed to create account.';
            }
        }
    }
}

$page_title = "Tastebite | Sign Up";
include 'includes/head.php';
include 'includes/header.php';
?>

<main class="modals-container">
    <div class="modal-card auth-modal">
        <h2>SIGN UP</h2>
        
        <?php if ($error): ?>
            <div class="error-message" style="color: var(--danger-red); margin-bottom: 20px; font-size: 14px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateToken(); ?>">
            
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="name" required placeholder="Full Name" value="<?php echo isset($name) ? h($name) : ''; ?>">
            </div>

            <div class="input-group">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" required placeholder="Email" value="<?php echo isset($email) ? h($email) : ''; ?>">
            </div>
            
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" required placeholder="Password">
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="confirm_password" required placeholder="Confirm Password">
            </div>

            <button type="submit" class="btn-primary btn-full">Sign Up</button>

            <div class="divider">
                <span>Or sign up with</span>
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
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </form>
    </div>
</main>

<?php 
include 'includes/footer.php';
include 'includes/foot.php';
?>
