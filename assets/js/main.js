document.addEventListener('DOMContentLoaded', function () {
    // --- Responsive navigation menu ---
    const navToggle = document.getElementById("nav-toggle");
    const navMenu = document.getElementById("nav-menu");

    if (navToggle && navMenu) {
        navToggle.addEventListener("click", function () {
            navMenu.classList.toggle("show");
        });

        window.addEventListener("resize", function () {
            if (window.innerWidth > 900) {
                navMenu.classList.remove("show");
                document.querySelectorAll('.has-dropdown').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });

        // Mobile dropdown toggle
        document.querySelectorAll('.has-dropdown > a').forEach(link => {
            link.addEventListener('click', function (e) {
                if (window.innerWidth <= 900) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('active');
                }
            });
        });
    }

    // --- Search Modal Logic ---
    const searchBtn = document.getElementById('search-btn');
    const searchModal = document.getElementById('search-modal');
    const closeSearch = document.getElementById('close-search');
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('search-live-results');
    const seeAllBtn = document.getElementById('see-all-results');

    if (searchBtn && searchModal) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            searchModal.classList.add('active');
            searchInput.focus();
        });

        closeSearch.addEventListener('click', () => {
            searchModal.classList.remove('active');
        });

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') searchModal.classList.remove('active');
        });

        // Live Search
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const query = searchInput.value.trim();

            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                seeAllBtn.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`api/search.php?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                resultsContainer.innerHTML += `
                                    <a href="recipe.php?id=${item.id}" class="search-live-item">
                                        <img src="${item.image}" alt="${item.title}">
                                        <div class="info">
                                            <h4>${item.title}</h4>
                                            <span>${item.category}</span>
                                        </div>
                                    </a>
                                `;
                            });
                            seeAllBtn.href = `search.php?q=${encodeURIComponent(query)}`;
                            seeAllBtn.style.display = 'inline-block';
                        } else {
                            resultsContainer.innerHTML = '<p style="text-align:center; padding: 2rem;">No recipes found.</p>';
                            seeAllBtn.style.display = 'none';
                        }
                    });
            }, 300);
        });

        // Redirect to results on Enter
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                window.location.href = `search.php?q=${encodeURIComponent(searchInput.value.trim())}`;
            }
        });
    }

    // --- Feedback Modal Logic ---
    const feedbackBtn = document.getElementById('share-feedback');
    const feedbackModal = document.getElementById('feedback-modal');
    const closeFeedback = feedbackModal ? feedbackModal.querySelector('.close-modal') : null;
    const feedbackForm = document.getElementById('feedback-form');

    if (feedbackBtn && feedbackModal) {
        feedbackBtn.addEventListener('click', () => {
            feedbackModal.classList.add('active');
        });

        const closeFeedback = document.getElementById('close-feedback-btn') || feedbackModal.querySelector('.close-modal');
        if (closeFeedback) {
            closeFeedback.addEventListener('click', () => {
                feedbackModal.classList.remove('active');
            });
        }

        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = feedbackForm.querySelector('button');
            const originalText = submitBtn.innerText;

            submitBtn.innerText = 'Sending...';
            submitBtn.disabled = true;

            // Mock submission delay
            setTimeout(() => {
                showToast('Thank you for your feedback!');
                feedbackModal.classList.remove('active');
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
                feedbackForm.reset();
            }, 1000);
        });
    }

    // --- Newsletter Logic ---
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const emailInput = newsletterForm.querySelector('input[type="email"]');
            const submitBtn = newsletterForm.querySelector('button');
            const originalText = submitBtn.innerText;

            if (emailInput.value) {
                submitBtn.innerText = 'SUBSCRIBING...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    showToast('Thanks for subscribing!');
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                    emailInput.value = '';
                }, 1000);
            }
        });
    }

    // --- User Menu Toggle ---
    const userMenuTrigger = document.getElementById('user-menu-trigger');
    const userDropdown = document.getElementById('user-dropdown-menu');

    if (userMenuTrigger && userDropdown) {
        userMenuTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        document.addEventListener('click', () => {
            userDropdown.classList.remove('active');
        });
    }

    // --- Cookie Notice Logic ---
    const cookieNotice = document.getElementById('cookie-notice');
    const acceptCookies = document.getElementById('accept-cookies');

    if (cookieNotice) {
        const isAccepted = localStorage.getItem('cookies-accepted');
        if (!isAccepted) {
            cookieNotice.style.display = 'flex';
        } else {
            cookieNotice.style.display = 'none';
        }
    }

    if (acceptCookies) {
        acceptCookies.addEventListener('click', () => {
            localStorage.setItem('cookies-accepted', 'true');
            cookieNotice.style.display = 'none';
        });
    }

    // --- Global Toast Function ---
    window.showToast = function (message) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.innerHTML = `
            <span>${message}</span>
            <button class="btn-small btn-primary" onclick="this.parentElement.remove()">Dismiss</button>
        `;

        container.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) toast.remove();
        }, 5000);
    };
});
