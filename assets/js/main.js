document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    
    // Set header as transparent initially if on homepage
    const isHomePage = window.location.pathname === '/project/index.php' || 
                       window.location.pathname === '/project/' || 
                       window.location.pathname === '/project/index.html' ||
                       window.location.pathname === '/';
    
    if (isHomePage && header) {
        header.classList.add('transparent');
    }
    
    // Handle scroll event for transparent header
    window.addEventListener('scroll', function() {
        if (!header) return;
        
        if (window.scrollY > 100) {
            header.classList.remove('transparent');
        } else {
            if (isHomePage) {
                header.classList.add('transparent');
            }
        }
    });

    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');

            const spans = this.querySelectorAll('span');
            spans.forEach((span, index) => {
                if (mainNav.classList.contains('active')) {
                    if (index === 0) span.style.transform = 'rotate(45deg) translateY(8px)';
                    if (index === 1) span.style.opacity = '0';
                    if (index === 2) span.style.transform = 'rotate(-45deg) translateY(-8px)';
                } else {
                    span.style.transform = '';
                    span.style.opacity = '';
                }
            });
        });
    }

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

                    if (mainNav && mainNav.classList.contains('active')) {
                        mainNav.classList.remove('active');
                        const spans = mobileMenuToggle.querySelectorAll('span');
                        spans.forEach(span => {
                            span.style.transform = '';
                            span.style.opacity = '';
                        });
                    }
                }
            }
        });
    });

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });

            console.log('Form submitted:', formObject);

            alert('Thank you for your submission! This is a demo form. In production, this would send your information to Solutions OptiSpace.');

            this.reset();
        });
    });

    let lastScrollTop = 0;

    if (header) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > 100) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }, false);
    }

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
