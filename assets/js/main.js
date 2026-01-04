document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    const body = document.body;
    
    // Check if we're on the homepage
    const isHomePage = body.classList.contains('home-page') || document.querySelector('.hero');
    
    console.log('Page loaded. Is homepage:', isHomePage);
    console.log('Header has transparent class:', header.classList.contains('transparent'));
    
    // Handle scroll event for transparent header
    function handleScroll() {
        if (!header) return;
        
        const scrollPosition = window.scrollY || window.pageYOffset;
        
        // Add scrolled class for sticky header effect
        if (scrollPosition > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        if (!isHomePage) return;
        
        // Get hero section height
        const heroSection = document.querySelector('.hero, .hero-enhanced, .home-hero');
        const heroHeight = heroSection ? heroSection.offsetHeight : 500;
        
        if (scrollPosition > heroHeight) {
            if (header.classList.contains('transparent')) {
                header.classList.remove('transparent');
            }
        } else {
            if (!header.classList.contains('transparent')) {
                header.classList.add('transparent');
            }
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    
    // Call once on load to set initial state
    handleScroll();

    // Mobile Side Panel Navigation
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNavPanel = document.querySelector('.mobile-nav-panel');
    const mobileNavOverlay = document.querySelector('.mobile-nav-overlay');
    const mobileNavClose = document.querySelector('.mobile-nav-close');
    const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');

    function openMobileNav() {
        mobileNavPanel.classList.add('active');
        mobileNavOverlay.classList.add('active');
        document.body.classList.add('mobile-nav-open');
        
        // Animate hamburger to X
        const spans = mobileMenuToggle.querySelectorAll('span');
        spans[0].style.transform = 'rotate(45deg) translateY(8px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translateY(-8px)';
    }

    function closeMobileNav() {
        mobileNavPanel.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        document.body.classList.remove('mobile-nav-open');
        
        // Reset hamburger icon
        const spans = mobileMenuToggle.querySelectorAll('span');
        spans.forEach(span => {
            span.style.transform = '';
            span.style.opacity = '';
        });
    }

    if (mobileMenuToggle && mobileNavPanel) {
        mobileMenuToggle.addEventListener('click', function() {
            if (mobileNavPanel.classList.contains('active')) {
                closeMobileNav();
            } else {
                openMobileNav();
            }
        });
    }

    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', closeMobileNav);
    }

    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', closeMobileNav);
    }

    // Mobile submenu toggle
    mobileSubmenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentLi = this.closest('.mobile-has-submenu');
            
            // Close other open submenus
            document.querySelectorAll('.mobile-has-submenu.open').forEach(openItem => {
                if (openItem !== parentLi) {
                    openItem.classList.remove('open');
                }
            });
            
            parentLi.classList.toggle('open');
        });
    });

    // Close mobile nav when clicking a link
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-panel a:not(.mobile-submenu-toggle)');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            closeMobileNav();
        });
    });

    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    e.preventDefault();
                    const headerOffset = 80;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Close mobile nav if open
                    if (mobileNavPanel && mobileNavPanel.classList.contains('active')) {
                        closeMobileNav();
                    }
                }
            }
        });
    });

    // Handle demo forms (exclude actual backend forms like pulse check and contact form)
    const demoForms = document.querySelectorAll('form:not(#pulseCheckForm):not(#contactForm)');
    demoForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Only intercept forms that don't have a method attribute or aren't POST
            if (!this.method || this.method.toLowerCase() !== 'post') {
                e.preventDefault();

                const formData = new FormData(this);
                const formObject = {};
                formData.forEach((value, key) => {
                    formObject[key] = value;
                });

                console.log('Form submitted:', formObject);

                alert('Thank you for your submission! This is a demo form. In production, this would send your information to Solutions OptiSpace.');

                this.reset();
            }
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    const animateElements = document.querySelectorAll('.card, .process-step, .stat-card');
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
