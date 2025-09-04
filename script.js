// Enhanced Portfolio JavaScript with Animations and Better UX

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initializePortfolio();
});

function initializePortfolio() {
    // Initialize smooth scrolling
    initSmoothScrolling();
    
    // Initialize animations
    initAnimations();
    
    // Initialize dark mode
    initDarkMode();
    
    // Initialize loading animations
    initLoadingAnimations();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize scroll effects
    initScrollEffects();
}

// Smooth Scrolling for Navigation Links
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 100; // Account for fixed nav
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                closeMobileMenu();
            }
        });
    });
}

// Enhanced Mobile Menu
function initMobileMenu() {
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const menuLinks = document.querySelector('.menu-links');
    
    if (hamburgerIcon && menuLinks) {
        hamburgerIcon.addEventListener('click', toggleMenu);
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!hamburgerIcon.contains(e.target) && !menuLinks.contains(e.target)) {
                closeMobileMenu();
            }
        });
    }
}

function toggleMenu() {
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const menuLinks = document.querySelector('.menu-links');
    
    if (hamburgerIcon && menuLinks) {
        hamburgerIcon.classList.toggle('open');
        menuLinks.classList.toggle('open');
        
        // Add animation to menu items
        const menuItems = menuLinks.querySelectorAll('a');
        menuItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fade-in');
        });
    }
}

function closeMobileMenu() {
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const menuLinks = document.querySelector('.menu-links');
    
    if (hamburgerIcon && menuLinks) {
        hamburgerIcon.classList.remove('open');
        menuLinks.classList.remove('open');
    }
}

// Enhanced Dark Mode Toggle
function initDarkMode() {
    const modeToggle = document.getElementById('mode-toggle');
    const body = document.body;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    body.classList.toggle('dark', currentTheme === 'dark');
    updateModeButton(currentTheme);
    
    if (modeToggle) {
        modeToggle.addEventListener('click', function() {
            body.classList.toggle('dark');
            
            const isDark = body.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            updateModeButton(isDark ? 'dark' : 'light');
            
            // Add animation effect
            this.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                this.style.transform = '';
            }, 500);
        });
    }
}

function updateModeButton(theme) {
    const modeToggle = document.getElementById('mode-toggle');
    if (modeToggle) {
        if (theme === 'dark') {
            modeToggle.textContent = '☀️ Light';
            modeToggle.style.background = 'var(--gradient-warning)';
        } else {
            modeToggle.textContent = '🌙 Dark';
            modeToggle.style.background = 'var(--gradient-secondary)';
        }
    }
}

// Enhanced Animations
function initAnimations() {
    // Rotating text animation
    const rotatingText = document.getElementById('rotating-text');
    if (rotatingText) {
        const texts = [
            'CSE student at KUET',
            'Frontend Developer',
            'Problem Solver',
            'Creative Thinker'
        ];
        
        let currentIndex = 0;
        
        function rotateText() {
            rotatingText.style.opacity = '0';
            rotatingText.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % texts.length;
                rotatingText.textContent = texts[currentIndex];
                rotatingText.style.opacity = '1';
                rotatingText.style.transform = 'translateY(0)';
            }, 300);
        }
        
        // Rotate text every 3 seconds
        setInterval(rotateText, 3000);
    }
    
    // Add hover effects to project cards
    const projectCards = document.querySelectorAll('.color-container');
    projectCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Loading Animations
function initLoadingAnimations() {
    const sections = document.querySelectorAll('section');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('loaded');
                
                // Add staggered animation to children
                const children = entry.target.querySelectorAll('.details-container, .color-container, article');
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('fade-in');
                    }, index * 100);
                });
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    sections.forEach(section => {
        section.classList.add('loading');
        observer.observe(section);
    });
}

// Scroll Effects
function initScrollEffects() {
    const nav = document.querySelector('#desktop-nav');
    
    window.addEventListener('scroll', function() {
        // if (window.scrollY > 100) {
        //     nav.style.background = 'rgba(255, 255, 255, 0.98)';
        //     nav.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
        // } else {
        //     nav.style.background = 'rgba(255, 255, 255, 0.95)';
        //     nav.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        // }
    });
    
    // Parallax effect for profile image
    const profileImage = document.querySelector('#profile .section__pic-container');
    if (profileImage) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            profileImage.style.transform = `translateY(${rate}px)`;
        });
    }
}

// Enhanced Form Validation (if forms exist)
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error animation
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'error');
            }
        });
    });
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? 'var(--danger-color)' : 'var(--success-color)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideInRight 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Enhanced Button Interactions
function initButtonInteractions() {
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Performance Optimization
function optimizePerformance() {
    // Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Debounce scroll events
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        scrollTimeout = setTimeout(() => {
            // Handle scroll events here
        }, 16); // 60fps
    });
}

// Initialize all button interactions
document.addEventListener('DOMContentLoaded', function() {
    initButtonInteractions();
    initFormValidation();
    optimizePerformance();
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .error {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 2px rgba(250, 112, 154, 0.2) !important;
    }
    
    .lazy {
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .lazy.loaded {
        opacity: 1;
    }
    
    .notification button {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        margin-left: 0.5rem;
    }
    
    .notification button:hover {
        opacity: 0.8;
    }
`;

document.head.appendChild(style);

// Export functions for global use
window.portfolio = {
    toggleMenu,
    closeMobileMenu,
    showNotification,
    initDarkMode
};