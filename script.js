/* ==========================================
   NOVA STUDIO - MAIN JAVASCRIPT
   ========================================== */

document.addEventListener('DOMContentLoaded', () => {


    // ==========================================
    // 1. SMART FLOATING NAVBAR
    // ==========================================

    const floatingNavbar =
        document.getElementById('floatingNavbar');

    if (floatingNavbar) {

        let lastScrollY =
            window.pageYOffset ||
            document.documentElement.scrollTop;

        let ticking = false;

        const TOP_THRESHOLD = 10;

        function updateNavbar() {

            const currentScrollY =
                window.pageYOffset ||
                document.documentElement.scrollTop;

            if (currentScrollY <= TOP_THRESHOLD) {

                // At the top — always visible
                floatingNavbar.classList.remove(
                    'navbar-hidden'
                );

            } else if (currentScrollY > lastScrollY) {

                // Scrolling down — hide
                floatingNavbar.classList.add(
                    'navbar-hidden'
                );

            } else if (currentScrollY < lastScrollY) {

                // Scrolling up — show
                floatingNavbar.classList.remove(
                    'navbar-hidden'
                );
            }

            lastScrollY = currentScrollY;

            ticking = false;
        }


        window.addEventListener(
            'scroll',
            () => {

                if (!ticking) {

                    window.requestAnimationFrame(
                        updateNavbar
                    );

                    ticking = true;
                }

            },
            { passive: true }
        );


        // ==========================================
        // 1B. AUTO ACTIVE NAV ITEM
        // ==========================================

        const navLinks =
            floatingNavbar.querySelectorAll(
                '.nav-item:not(.nav-item-cta)'
            );

        let currentPage =
            window.location.pathname
                .split('/')
                .pop();

        if (currentPage === '') {
            currentPage = 'index.html';
        }

        navLinks.forEach(link => {

            const linkPage =
                (link.getAttribute('href') || '')
                    .split('/')
                    .pop();

            const isActive =
                linkPage === currentPage;

            link.classList.toggle(
                'active',
                isActive
            );

            if (isActive) {

                link.setAttribute(
                    'aria-current',
                    'page'
                );

            } else {

                link.removeAttribute(
                    'aria-current'
                );
            }

        });
    }


    // ==========================================
    // 2. PROJECT FILTERING
    //    Works on BOTH Home + Projects page
    // ==========================================

    function initializeProjectFilters() {

        const filterButtons =
            document.querySelectorAll(
                '.filter-btn'
            );

        if (filterButtons.length === 0) {
            return;
        }


        filterButtons.forEach(button => {

            // Prevent duplicate event listeners
            if (
                button.dataset.filterInitialized ===
                'true'
            ) {
                return;
            }

            button.dataset.filterInitialized =
                'true';


            button.addEventListener(
                'click',
                () => {

                    // Remove active from all buttons
                    filterButtons.forEach(btn => {

                        btn.classList.remove(
                            'active'
                        );

                    });


                    // Activate clicked button
                    button.classList.add(
                        'active'
                    );


                    const filterValue =
                        button.getAttribute(
                            'data-filter'
                        );


                    // Get current page project cards
                    const projectCards =
                        document.querySelectorAll(
                            '.project-card'
                        );


                    projectCards.forEach(card => {

                        const category =
                            card.getAttribute(
                                'data-category'
                            );


                        if (
                            filterValue === 'all' ||
                            category === filterValue
                        ) {

                            card.style.display = '';

                        } else {

                            card.style.display =
                                'none';
                        }

                    });

                }
            );

        });
    }


    // Initialize filters immediately
    // This handles Home page cards
    initializeProjectFilters();


    // ==========================================
    // 3. PROJECTS API
    //    Only runs on projects.html
    // ==========================================

    const projectsGrid =
        document.getElementById(
            'projectsGrid'
        );


    if (projectsGrid) {

        const API_URL =
            'api/projects.php';


        // ------------------------------------------
        // Convert database category to filter value
        // ------------------------------------------

        function getProjectCategory(category) {

            const categoryMap = {

                'Web Design': 'websites',

                'Web Development': 'websites',

                'Branding': 'branding',

                'UI/UX Design': 'ui-ux',

                'E-Commerce': 'ecommerce'

            };


            return (
                categoryMap[category] ||
                'websites'
            );
        }


        // ------------------------------------------
        // Create project card
        // ------------------------------------------

        function createProjectCard(project) {

            const category =
                getProjectCategory(
                    project.category
                );


            const card =
                document.createElement('div');

            card.className =
                'project-card';

            card.setAttribute(
                'data-category',
                category
            );


            card.innerHTML = `

                <div class="project-img">

                    <img
                        src="${project.image}"
                        alt="${project.title}"
                        onerror="
                            this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80';
                        "
                    >

                </div>


                <div class="project-info">

                    <span class="project-category">
                        ${project.category}
                    </span>

                    <h3>
                        ${project.title}
                    </h3>

                </div>

            `;


            return card;
        }


        // ------------------------------------------
        // Load projects from API
        // ------------------------------------------

        async function loadProjects() {

            try {

                const response =
                    await fetch(API_URL);


                if (!response.ok) {

                    throw new Error(
                        'Failed to fetch projects.'
                    );
                }


                const result =
                    await response.json();


                if (
                    !result.success ||
                    !Array.isArray(result.data)
                ) {

                    throw new Error(
                        'Invalid API response.'
                    );
                }


                // Clear loading message
                projectsGrid.innerHTML = '';


                // No projects
                if (result.data.length === 0) {

                    projectsGrid.innerHTML = `

                        <p>
                            No projects available
                            at the moment.
                        </p>

                    `;

                    return;
                }


                // Create cards
                result.data.forEach(
                    project => {

                        const card =
                            createProjectCard(
                                project
                            );

                        projectsGrid.appendChild(
                            card
                        );

                    }
                );


                // IMPORTANT:
                // Do NOT initialize filters again.
                // The common filter system above
                // already handles the buttons.

            } catch (error) {

                console.error(
                    'Projects API Error:',
                    error
                );


                projectsGrid.innerHTML = `

                    <p>
                        Unable to load projects.
                        Please try again later.
                    </p>

                `;

            }

        }


        // Start API request
        loadProjects();

    }


    // ==========================================
    // 4. CONTACT FORM
    // ==========================================

    const contactForm =
        document.getElementById(
            'contactForm'
        );

    const formMessage =
        document.getElementById(
            'formMessage'
        );


    if (contactForm) {

        contactForm.addEventListener(
            'submit',
            async (e) => {

                e.preventDefault();


                const name =
                    document
                        .getElementById('name')
                        .value
                        .trim();


                const email =
                    document
                        .getElementById('email')
                        .value
                        .trim();


                const subject =
                    document
                        .getElementById('subject')
                        .value
                        .trim();


                const message =
                    document
                        .getElementById('message')
                        .value
                        .trim();


                // ------------------------------------------
                // Required fields validation
                // ------------------------------------------

                if (
                    !name ||
                    !email ||
                    !subject ||
                    !message
                ) {

                    showFormMessage(
                        'Please fill in all required fields.',
                        'error'
                    );

                    return;
                }


                // ------------------------------------------
                // Email validation
                // ------------------------------------------

                if (!isValidEmail(email)) {

                    showFormMessage(
                        'Please enter a valid email address.',
                        'error'
                    );

                    return;
                }


                // ------------------------------------------
                // Current behaviour preserved
                // ------------------------------------------

                showFormMessage(
                    'Thank you! Your message has been sent successfully.',
                    'success'
                );


                contactForm.reset();

            }
        );
    }


    // ==========================================
    // 5. EMAIL VALIDATION
    // ==========================================

    function isValidEmail(email) {

        const re =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return re.test(email);
    }


    // ==========================================
    // 6. FORM MESSAGE
    // ==========================================

    function showFormMessage(
        text,
        type
    ) {

        if (!formMessage) {
            return;
        }


        formMessage.textContent =
            text;


        formMessage.style.padding =
            '12px';


        formMessage.style.borderRadius =
            '6px';


        formMessage.style.marginTop =
            '16px';


        formMessage.style.fontSize =
            '14px';


        if (type === 'success') {

            formMessage.style.backgroundColor =
                '#d4edda';

            formMessage.style.color =
                '#155724';

        } else {

            formMessage.style.backgroundColor =
                '#f8d7da';

            formMessage.style.color =
                '#721c24';
        }

    }

});