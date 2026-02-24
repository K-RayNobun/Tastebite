<div class="newsletter-section">
    <div class="newsletter-content">
        <h2>Deliciousness to your inbox</h2>
        <p>Enjoy weekly hand picked recipes and recommendations, delivered straight to your email.</p>
        
        <form class="newsletter-form">
            <div class="input-wrapper">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" placeholder="Email Address" required>
            </div>
            <button type="submit">JOIN</button>
        </form>
        
        <p class="newsletter-terms">By joining, you agree to our <a href="#">Terms & Conditions</a>.</p>
    </div>
</div>

<style>
.newsletter-section {
    background-color: var(--orange-light);
    padding: 100px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.newsletter-section::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: var(--orange);
    border-radius: 50%;
    opacity: 0.1;
}

.newsletter-content {
    max-width: 650px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.newsletter-content h2 {
    font-family: var(--font-heading);
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    color: var(--black);
}

.newsletter-content p {
    font-size: 1.2rem;
    color: var(--black);
    margin-bottom: 2.5rem;
    opacity: 0.8;
}

.newsletter-form {
    display: flex;
    gap: 15px;
    background: white;
    padding: 8px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

.input-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-left: 15px;
}

.input-wrapper i {
    color: var(--gray-text);
    font-size: 1.1rem;
}

.input-wrapper input {
    border: none;
    outline: none;
    font-size: 1rem;
    width: 100%;
    font-family: var(--font-primary);
}

.newsletter-form button {
    background: var(--orange);
    color: white;
    border: none;
    padding: 16px 40px;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.newsletter-form button:hover {
    transform: scale(1.05);
    background: #e55a2a;
}

.newsletter-terms {
    font-size: 0.85rem !important;
    color: var(--gray-text) !important;
    margin: 0 !important;
}

.newsletter-terms a {
    color: var(--orange);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .newsletter-content h2 { font-size: 2.5rem; }
    .newsletter-form { flex-direction: column; background: transparent; box-shadow: none; padding: 0; }
    .input-wrapper { background: white; padding: 18px; border-radius: 12px; }
    .newsletter-form button { width: 100%; padding: 18px; }
}
</style>
