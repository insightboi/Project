// Smart Film Makers - Main JavaScript

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Setup form validation
    setupFormValidation();
    
    // Setup auto-save functionality
    setupAutoSave();
    
    // Setup smooth scrolling
    setupSmoothScroll();
    
    // Setup loading states
    setupLoadingStates();
}

// Form Validation
function setupFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// Auto-save functionality
function setupAutoSave() {
    const autoSaveElements = document.querySelectorAll('[data-auto-save]');
    let saveTimeout;
    
    autoSaveElements.forEach(element => {
        element.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveFormData(element);
            }, 2000);
        });
    });
}

function saveFormData(element) {
    const formData = new FormData(element.closest('form'));
    const data = Object.fromEntries(formData);
    
    // Show saving indicator
    showNotification('Saving...', 'info');
    
    // Send to server (implement actual save logic)
    fetch('api/save-draft.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Draft saved successfully', 'success');
        } else {
            showNotification('Failed to save draft', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error saving draft', 'error');
    });
}

// Smooth scrolling
function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Loading states
function setupLoadingStates() {
    document.querySelectorAll('.btn-loading').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            this.disabled = true;
            
            // Reset after 3 seconds (adjust as needed)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 3000);
        });
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Project management functions
function createProject(formData) {
    showLoadingOverlay();
    
    fetch('api/create-project.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingOverlay();
        
        if (data.success) {
            showNotification('Project created successfully', 'success');
            setTimeout(() => {
                window.location.href = `project.php?id=${data.project_id}`;
            }, 1500);
        } else {
            showNotification(data.message || 'Failed to create project', 'error');
        }
    })
    .catch(error => {
        hideLoadingOverlay();
        console.error('Error:', error);
        showNotification('Error creating project', 'error');
    });
}

function generateContent(projectId, section) {
    const button = document.querySelector(`[data-generate="${section}"]`);
    const originalText = button.innerHTML;
    
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
    button.disabled = true;
    
    fetch('api/generate-content.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            project_id: projectId,
            section: section
        })
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            updateSectionContent(section, data.content);
            showNotification(`${section} generated successfully`, 'success');
        } else {
            showNotification(data.message || 'Failed to generate content', 'error');
        }
    })
    .catch(error => {
        button.innerHTML = originalText;
        button.disabled = false;
        console.error('Error:', error);
        showNotification('Error generating content', 'error');
    });
}

function updateSectionContent(section, content) {
    const container = document.getElementById(`${section}-content`);
    if (container) {
        container.innerHTML = content;
        container.classList.add('fade-in');
    }
}

// Export functions
function exportProject(projectId, format) {
    showLoadingOverlay();
    
    window.open(`api/export.php?project_id=${projectId}&format=${format}`, '_blank');
    
    setTimeout(() => {
        hideLoadingOverlay();
        showNotification(`Exporting to ${format.toUpperCase()}...`, 'info');
    }, 1000);
}

// Loading overlay
function showLoadingOverlay() {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
    overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
    overlay.style.zIndex = '9999';
    overlay.innerHTML = `
        <div class="bg-white p-4 rounded-3 text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0">Processing...</p>
        </div>
    `;
    
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Character management
function addCharacter(type = 'main') {
    const container = document.getElementById(`${type}-characters`);
    const index = container.children.length;
    
    const characterDiv = document.createElement('div');
    characterDiv.className = 'character-item card mb-3';
    characterDiv.innerHTML = `
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Character Name</label>
                        <input type="text" class="form-control" name="characters[${type}][${index}][name]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="characters[${type}][${index}][role]" required>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="characters[${type}][${index}][description]" rows="3" required></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCharacter(this)">Remove</button>
        </div>
    `;
    
    container.appendChild(characterDiv);
}

function removeCharacter(button) {
    if (confirm('Are you sure you want to remove this character?')) {
        button.closest('.character-item').remove();
    }
}

// Search and filter
function setupSearchFilter() {
    const searchInput = document.getElementById('search-projects');
    const filterSelect = document.getElementById('filter-projects');
    const projectCards = document.querySelectorAll('.project-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterProjects);
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', filterProjects);
    }
    
    function filterProjects() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const filterValue = filterSelect ? filterSelect.value : '';
        
        projectCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const status = card.dataset.status;
            
            const matchesSearch = title.includes(searchTerm);
            const matchesFilter = !filterValue || status === filterValue;
            
            card.style.display = matchesSearch && matchesFilter ? 'block' : 'none';
        });
    }
}

// Tab management
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('show', 'active');
    
    // Add active class to clicked tab button
    document.querySelector(`[data-bs-target="#${tabId}"]`).classList.add('active');
}

// Word counter
function setupWordCounter() {
    const textareas = document.querySelectorAll('[data-word-counter]');
    
    textareas.forEach(textarea => {
        const counter = document.createElement('small');
        counter.className = 'text-muted word-counter';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0).length;
            const chars = textarea.value.length;
            counter.textContent = `${words} words, ${chars} characters`;
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
}

// Initialize search filter when page loads
document.addEventListener('DOMContentLoaded', setupSearchFilter);
document.addEventListener('DOMContentLoaded', setupWordCounter);
