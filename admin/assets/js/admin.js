/**
 * OptiSpace Admin Panel JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }
    
    // Modal functionality
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };
    
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };
    
    // Close modal on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) {
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop.active').forEach(function(modal) {
                modal.classList.remove('active');
            });
            document.body.style.overflow = '';
        }
    });
    
    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert[data-auto-hide]').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // File upload preview
    document.querySelectorAll('.file-upload input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const uploadText = this.closest('.file-upload').querySelector('.file-upload-text');
            if (fileName && uploadText) {
                uploadText.textContent = fileName;
            }
        });
    });
    
    // Form validation
    document.querySelectorAll('form[data-validate]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            form.querySelectorAll('[required]').forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    let errorMsg = field.parentElement.querySelector('.form-error');
                    if (!errorMsg) {
                        errorMsg = document.createElement('span');
                        errorMsg.className = 'form-error';
                        errorMsg.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentElement.querySelector('.form-error');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Confirm delete
    window.confirmDelete = function(message, formId) {
        if (confirm(message || 'Are you sure you want to delete this item?')) {
            if (formId) {
                document.getElementById(formId).submit();
            }
            return true;
        }
        return false;
    };
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                const query = e.target.value.trim();
                if (query.length >= 2) {
                    // Trigger search - can be customized per page
                    const event = new CustomEvent('adminSearch', { detail: { query } });
                    document.dispatchEvent(event);
                }
            }, 300);
        });
    }
    
    // Toggle switches
    document.querySelectorAll('.toggle-switch input').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const event = new CustomEvent('toggleChange', { 
                detail: { 
                    name: this.name,
                    checked: this.checked 
                } 
            });
            document.dispatchEvent(event);
        });
    });
    
    // Initialize tooltips (simple implementation)
    document.querySelectorAll('[title]').forEach(function(element) {
        element.addEventListener('mouseenter', function(e) {
            const title = this.getAttribute('title');
            if (!title) return;
            
            this.setAttribute('data-title', title);
            this.removeAttribute('title');
            
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = title;
            tooltip.style.cssText = `
                position: fixed;
                background: var(--admin-gray-900);
                color: white;
                padding: 0.375rem 0.75rem;
                border-radius: 4px;
                font-size: 0.75rem;
                z-index: 1000;
                pointer-events: none;
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
            tooltip.style.left = (rect.left + (rect.width - tooltip.offsetWidth) / 2) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
            const dataTitle = this.getAttribute('data-title');
            if (dataTitle) {
                this.setAttribute('title', dataTitle);
                this.removeAttribute('data-title');
            }
        });
    });
});
