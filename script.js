/* ==========================================
   NOVA STUDIO - MAIN JAVASCRIPT
   ========================================== */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Smart Floating Navbar (hide on scroll down, show on scroll up)
    const floatingNavbar = document.getElementById('floatingNavbar');

    if (floatingNavbar) {
        let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
        let ticking = false;
        const TOP_THRESHOLD = 10; // always show near the very top of the page

        function updateNavbar() {
            const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScrollY <= TOP_THRESHOLD) {
                // At the top of the page — always visible
                floatingNavbar.classList.remove('navbar-hidden');
            } else if (currentScrollY > lastScrollY) {
                // Scrolling down — hide
                floatingNavbar.classList.add('navbar-hidden');
            } else if (currentScrollY < lastScrollY) {
                // Scrolling up — show, even for a small upward movement
                floatingNavbar.classList.remove('navbar-hidden');
            }
            // If scroll position hasn't changed, do nothing — visibility is
            // controlled only by scroll direction, never by scroll stopping.

            lastScrollY = currentScrollY;
            ticking = false;
        }

        // rAF-throttled scroll listener so we never do more than one
        // style read/write per animation frame, regardless of scroll event volume
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });

        // 1b. Auto-detect the active nav item from the current page URL,
        // instead of relying only on a hard-coded "active" class per page.
        // This is progressive enhancement: each HTML file still marks its
        // own link with class="active" and aria-current="page" as a
        // no-JS fallback, and this keeps everything in sync automatically
        // if a page is ever renamed or the markup drifts.
        const navLinks = floatingNavbar.querySelectorAll('.nav-item:not(.nav-item-cta)');
        let currentPage = window.location.pathname.split('/').pop();
        if (currentPage === '') {
            currentPage = 'index.html';
        }

        navLinks.forEach(link => {
            const linkPage = (link.getAttribute('href') || '').split('/').pop();
            const isActive = linkPage === currentPage;

            link.classList.toggle('active', isActive);
            if (isActive) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    }

    // 2. Project Filtering (Vanilla JS)
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (filterButtons.length > 0 && projectCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filterValue = button.getAttribute('data-filter');

                projectCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    if (filterValue === 'all' || category === filterValue) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // 3. Contact Form Validation & Success Handling
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !subject || !message) {
                showFormMessage('Please fill in all required fields.', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showFormMessage('Please enter a valid email address.', 'error');
                return;
            }

            // Simulate successful asynchronous dispatch
            showFormMessage('Thank you! Your message has been sent successfully.', 'success');
            contactForm.reset();
        });
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showFormMessage(text, type) {
        if (!formMessage) return;
        formMessage.textContent = text;
        formMessage.style.padding = '12px';
        formMessage.style.borderRadius = '6px';
        formMessage.style.marginTop = '16px';
        formMessage.style.fontSize = '14px';

        if (type === 'success') {
            formMessage.style.backgroundColor = '#d4edda';
            formMessage.style.color = '#155724';
        } else {
            formMessage.style.backgroundColor = '#f8d7da';
            formMessage.style.color = '#721c24';
        }
    }
});