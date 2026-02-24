<?php
/**
 * Auth Class - Handles Session and User Authentication for Tastebite
 */
class Auth {
    private static $instance = null;
    private $db;

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once 'Database.php';
        $this->db = new Database();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current logged-in user data
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) return null;
        
        $email = $_SESSION['user_email'];
        return $this->db->getUser($email);
    }

    /**
     * Attempt to login a user
     */
    public function login($email, $password) {
        $user = $this->db->getUser($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'] ?? ($user['first_name'] . ' ' . $user['last_name']);
            $_SESSION['user_avatar'] = $user['avatar'] ?? 'https://randomuser.me/api/portraits/lego/1.jpg';
            return true;
        }
        return false;
    }

    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    /**
     * Generate CSRF token
     */
    public function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    public function validateToken($token) {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Redirect if not logged in
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: /index.php?auth=required');
            exit;
        }
    }
}
?>
