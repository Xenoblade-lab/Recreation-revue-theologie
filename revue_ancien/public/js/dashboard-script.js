// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle for dashboard
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }

    // Navigation item click handlers
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Close sidebar on mobile after click
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('active');
            }
        });
    });

    // File upload functionality
    const fileUploads = document.querySelectorAll('.file-upload');
    fileUploads.forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        
        upload.addEventListener('click', function() {
            input.click();
        });

        upload.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--color-blue)';
            this.style.backgroundColor = 'var(--color-gray-50)';
        });

        upload.addEventListener('dragleave', function() {
            this.style.borderColor = 'var(--color-gray-300)';
            this.style.backgroundColor = 'transparent';
        });

        upload.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--color-gray-300)';
            this.style.backgroundColor = 'transparent';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                updateFileName(this, files[0].name);
            }
        });

        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                updateFileName(upload, this.files[0].name);
            }
        });
    });

    function updateFileName(uploadElement, fileName) {
        // Chercher d'abord un élément avec l'ID file-name-display
        let p = uploadElement.querySelector('#file-name-display');
        if (!p) {
            // Sinon, chercher le premier élément p
            p = uploadElement.querySelector('p');
        }
        if (p) {
            p.textContent = `Fichier sélectionné : ${fileName}`;
            p.style.color = 'var(--color-blue)';
            p.style.fontWeight = '600';
        }
    }

    // Stats animation on scroll
    const statValues = document.querySelectorAll('.stat-value');
    const observerOptions = {
        threshold: 0.5
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateValue(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    statValues.forEach(stat => {
        statsObserver.observe(stat);
    });

    function animateValue(element) {
        const text = element.textContent;
        const hasSymbol = text.includes('$');
        const value = parseFloat(text.replace(/[^0-9.]/g, ''));
        
        if (isNaN(value)) return;

        const duration = 1000;
        const steps = 30;
        const increment = value / steps;
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= value) {
                current = value;
                clearInterval(timer);
            }
            element.textContent = hasSymbol ? `$${Math.floor(current).toLocaleString()}` : Math.floor(current);
        }, duration / steps);
    }

    // Form submission handlers (only for simples forms without custom JS)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Ne pas intercepter les formulaires qui ont déjà une gestion JS dédiée
        const formId = form.getAttribute('id') || '';
        if (formId === 'createVolumeForm' || formId === 'createIssueForm') {
            return;
        }

        // Possibilité de désactiver ce handler global via un attribut
        if (form.hasAttribute('data-no-global-handler')) {
            return;
        }

        form.addEventListener('submit', function(e) {
            // Si un autre script a déjà appelé preventDefault, ne pas interférer
            if (e.defaultPrevented) {
                return;
            }

            e.preventDefault();
            
            // Message générique pour les vieux formulaires sans logique spécifique
            if (typeof showToast === 'function') {
                showToast('Formulaire soumis avec succès !', 'success');
            } else {
                alert('Formulaire soumis avec succès !');
            }
            
            // Reset form
            this.reset();
        });
    });

    // Action button tooltips
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = title;
                tooltip.style.cssText = `
                    position: absolute;
                    background: var(--color-gray-900);
                    color: white;
                    padding: 0.5rem;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    white-space: nowrap;
                    z-index: 1000;
                    pointer-events: none;
                `;
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
                tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
                
                this.tooltip = tooltip;
            }
        });

        btn.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.remove();
                this.tooltip = null;
            }
        });
    });
});