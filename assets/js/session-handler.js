/**
 * Modal & Session Handler for Tastebite
 */

document.addEventListener('DOMContentLoaded', () => {
    const loginModal = document.querySelector('.login-modal-overlay');
    const signupModal = document.querySelector('.signup-modal-overlay');
    const authTriggers = document.querySelectorAll('.auth-trigger');
    const restrictedActions = document.querySelectorAll('.restricted-action');

    // Helper to open modal
    const openModal = (modalType) => {
        // Implementation for opening specific modals
        console.log(`Opening ${modalType}`);
        // This will be expanded once we have the common modal structure
    };

    // Listen for URL parameters (Case C)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auth') === 'required') {
        openModal('login');
    }

    // Intercept Restricted Actions (Case A)
    restrictedActions.forEach(action => {
        action.addEventListener('click', (e) => {
            if (!isLoggedIn) { // Global var set by PHP
                e.preventDefault();
                openModal('login');
            }
        });
    });

    // Handle Login/Signup Toggle (Case D)
    const signupLink = document.querySelector('.to-signup');
    const loginLink = document.querySelector('.to-login');

    if (signupLink) {
        signupLink.addEventListener('click', (e) => {
            e.preventDefault();
            // Logic to swap modals
        });
    }

    // Header Dropdown Toggle (Case B)
    const profileImg = document.querySelector('.profile-img');
    const profileDropdown = document.querySelector('.profile-dropdown');

    if (profileImg && profileDropdown) {
        profileImg.addEventListener('click', () => {
            profileDropdown.classList.toggle('show');
        });
    }
});
