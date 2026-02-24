<div id="feedback-modal" class="search-modal" style="background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="modal-card feedback-content-card">
        <button class="close-modal-btn" id="close-feedback-btn">&times;</button>
        
        <div class="modal-header">
            <h2>Already made this?</h2>
            <p>Share your thoughts with the community!</p>
        </div>

        <form id="feedback-form" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateToken(); ?>">
            
            <div class="rating-selector">
                <input type="radio" id="f-star5" name="rating" value="5" required><label for="f-star5"></label>
                <input type="radio" id="f-star4" name="rating" value="4"><label for="f-star4"></label>
                <input type="radio" id="f-star3" name="rating" value="3"><label for="f-star3"></label>
                <input type="radio" id="f-star2" name="rating" value="2"><label for="f-star2"></label>
                <input type="radio" id="f-star1" name="rating" value="1"><label for="f-star1"></label>
            </div>

            <div class="input-group">
                <i class="fa-regular fa-comment-dots"></i>
                <textarea name="comment" required placeholder="What did you think? (e.g. I added extra cinnamon and it was perfect!)"></textarea>
            </div>

            <button type="submit" class="btn-primary btn-full" style="padding: 16px;">Post Review</button>
        </form>
    </div>
</div>

<style>
#feedback-modal.active {
    display: flex !important;
}

.feedback-content-card {
    max-width: 500px;
    width: 90%;
    position: relative;
    padding: 50px 40px;
    animation: modalBounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes modalBounce {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.close-modal-btn {
    position: absolute;
    top: 20px;
    right: 25px;
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--gray-text);
    transition: color 0.2s;
}

.close-modal-btn:hover { color: var(--black); }

.modal-header { text-align: center; margin-bottom: 30px; }
.modal-header h2 { font-family: var(--font-heading); font-size: 2rem; margin-bottom: 8px; }
.modal-header p { color: var(--gray-text); font-size: 1.1rem; }

.rating-selector {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
}

.rating-selector input { display: none; }
.rating-selector label {
    font-size: 2.5rem;
    color: #e0e0e0;
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
}

.rating-selector label:before {
    content: "\f005";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
}

.rating-selector input:checked ~ label,
.rating-selector label:hover,
.rating-selector label:hover ~ label {
    color: #FFB800;
}

.rating-selector label:hover {
    transform: scale(1.2);
}

#feedback-form textarea {
    width: 100%;
    min-height: 120px;
    border: none;
    border-bottom: 1px solid var(--border-color);
    padding: 12px 12px 12px 35px;
    font-family: var(--font-primary);
    font-size: 1rem;
    outline: none;
    resize: none;
    background: transparent;
}

#feedback-form textarea:focus {
    border-bottom-color: var(--orange);
}
</style>
