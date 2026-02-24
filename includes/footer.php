<footer class="footer">
    <div class="footer-top">
        <div class="footer-logo">
            <img src="about-page/images/logo.png" alt="Tastebite" height="32">
            <div class="footer-quote">
                "On the other hand, we denounce with righteous indignation and dislike men who are so beguiled
                and demoralized by the charms of pleasure of the moment"
            </div>
        </div>
        <div class="footer-links">
            <div>
                <strong>Tastebite <i class="fa-solid fa-chevron-down"></i></strong>
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Feedback</a></li>
                </ul>
            </div>
            <div>
                <strong>Legal <i class="fa-solid fa-chevron-down"></i></strong>
                <ul>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Conditions</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">Copyright</a></li>
                </ul>
            </div>
            <div>
                <strong>Follow <i class="fa-solid fa-chevron-down"></i></strong>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span><?php echo $copyright; ?></span>
        <div class="footer-socials">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>
</footer>

<!-- Cookie Notice -->
<div id="cookie-notice" class="cookie-notice-wrapper" style="display: none;">
    <div class="modal-card cookie-notice">
        <div class="cookie-content">
            <h2>Cookie Notice</h2>
            <p>To ensure an optimum user experience, we use cookies to collect some user data for advertising and analytics purposes.</p>
        </div>
        <button class="btn-primary" id="accept-cookies">Got it</button>
    </div>
</div>

<!-- Toast Stack -->
<div id="toast-container" class="toast-container"></div>

<style>
.cookie-notice-wrapper {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: 20px;
    z-index: 3000;
    display: flex;
    justify-content: center;
}

.cookie-notice-wrapper .modal-card {
    max-width: 800px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px 40px;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 4000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast-message {
    background: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@media (max-width: 900px) {
    .cookie-notice-wrapper .modal-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>
