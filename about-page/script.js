// Page initialization
document.addEventListener('DOMContentLoaded', function() {
    // Initialize smooth scrolling for navigation links
    initSmoothScrolling();

    // Initialize dropdown menus
    initDropdownMenus();

    // Initialize social media interactions
    initSocialInteractions();

    // Initialize team member hover effects
    initTeamHoverEffects();

    // Initialize navigation icon interactions
    initNavigationIcons();

    // Initialize mobile menu functionality
    initMobileMenu();
});

// Smooth scrolling for navigation
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-link)');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Dropdown menu functionality
function initDropdownMenus() {
    const dropdownLinks = document.querySelectorAll('.dropdown-link');

    dropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Toggle dropdown state
            this.classList.toggle('active');

            // Here you would typically show/hide a dropdown menu
            // For now, we'll just add a visual indicator
            const chevron = this.querySelector('.fa-chevron-down');
            if (chevron) {
                chevron.classList.toggle('rotated');
            }
        });
    });
}



// Social media interactions
function initSocialInteractions() {
    const socialIcons = document.querySelectorAll('.social-icon, .footer-social-icons i');

    socialIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();

            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);

            // Here you would implement actual social media linking
            // For now, show a placeholder alert
            const platform = this.className.includes('instagram') ? 'Instagram' :
                           this.className.includes('twitter') ? 'Twitter' :
                           this.className.includes('facebook') ? 'Facebook' :
                           this.className.includes('youtube') ? 'YouTube' : 'Social Media';

            console.log(`Redirecting to ${platform}...`);
        });
    });
}

// Team member hover effects
function initTeamHoverEffects() {
    const teamCards = document.querySelectorAll('.team-card');

    teamCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Navigation icon interactions
function initNavigationIcons() {
    const searchIcon = document.querySelector('.nav-icons .fa-search');
    const avatarPlaceholder = document.querySelector('.avatar-placeholder');

    if (searchIcon) {
        searchIcon.addEventListener('click', function() {
            // Add search functionality here
            console.log('Search clicked');
        });
    }

    if (avatarPlaceholder) {
        avatarPlaceholder.addEventListener('click', function() {
            // Add user menu functionality here
            console.log('Avatar clicked');
        });
    }
}

// Mobile menu functionality
function initMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerMenu = document.querySelector('.hamburger-menu');

    // Ensure mobile menu exists
    if (!mobileMenu || !hamburgerMenu) {
        console.warn('Mobile menu elements not found');
        return;
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !hamburgerMenu.contains(e.target)) {
            closeMobileMenu();
        }
    });
}

// Footer collapsible sections functionality
function toggleFooterSection(sectionName) {
    const sectionContent = document.getElementById(sectionName + '-content');
    const sectionHeader = document.querySelector(`[onclick="toggleFooterSection('${sectionName}')"]`);

    if (!sectionContent || !sectionHeader) {
        console.warn(`Footer section elements not found for: ${sectionName}`);
        return;
    }

    const isActive = sectionContent.classList.contains('active');
    const chevron = sectionHeader.querySelector('.fas');

    if (isActive) {
        // Close section
        sectionContent.classList.remove('active');
        sectionHeader.classList.remove('active');
        if (chevron) {
            chevron.style.transform = 'rotate(0deg)';
        }
    } else {
        // Close all other sections first
        closeAllFooterSections();

        // Open current section
        sectionContent.classList.add('active');
        sectionHeader.classList.add('active');
        if (chevron) {
            chevron.style.transform = 'rotate(180deg)';
        }
    }
}

function closeAllFooterSections() {
    const sectionContents = document.querySelectorAll('.section-content');
    const sectionHeaders = document.querySelectorAll('.section-header');

    sectionContents.forEach(content => {
        content.classList.remove('active');
    });

    sectionHeaders.forEach(header => {
        header.classList.remove('active');
        const chevron = header.querySelector('.fas');
        if (chevron) {
            chevron.style.transform = 'rotate(0deg)';
        }
    });
}

// Toggle mobile menu function (referenced in HTML onclick)
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerMenu = document.querySelector('.hamburger-menu');

    if (!mobileMenu || !hamburgerMenu) {
        console.warn('Mobile menu elements not found');
        return;
    }

    const isActive = mobileMenu.classList.contains('active');

    if (isActive) {
        closeMobileMenu();
    } else {
        openMobileMenu();
    }
}

function openMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerMenu = document.querySelector('.hamburger-menu');

    if (mobileMenu && hamburgerMenu) {
        mobileMenu.classList.add('active');
        hamburgerMenu.classList.add('active');

        // Prevent body scroll when menu is open
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerMenu = document.querySelector('.hamburger-menu');

    if (mobileMenu && hamburgerMenu) {
        mobileMenu.classList.remove('active');
        hamburgerMenu.classList.remove('active');

        // Restore body scroll
        document.body.style.overflow = '';
    }
}

// Accessibility improvements
document.addEventListener('keydown', function(e) {
    // Handle keyboard navigation and focus management
    if (e.key === 'Tab') {
        // Additional tab navigation logic can be added here
    }

    // Handle escape key for closing any open menus
    if (e.key === 'Escape') {
        // Close any open dropdowns or menus
        const activeDropdowns = document.querySelectorAll('.dropdown-link.active');
        activeDropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
            const chevron = dropdown.querySelector('.fa-chevron-down');
            if (chevron) {
                chevron.classList.remove('rotated');
            }
        });

        // Also close mobile menu if open
        closeMobileMenu();
    }
});
