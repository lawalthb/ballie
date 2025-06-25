// Common JavaScript functionality for tenant dashboard

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initializeTooltips();

    // Initialize modals
    initializeModals();

    // Initialize dropdowns
    initializeDropdowns();

    // Initialize notifications
    initializeNotifications();

    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
});

// Tooltip functionality
function initializeTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');

    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = createTooltip(tooltipText);
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        });

        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
}

function createTooltip(text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip absolute bg-gray-900 text-white text-xs rounded py-1 px-2 z-50';
    tooltip.textContent = text;
    return tooltip;
}

// Modal functionality
function initializeModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modalCloses = document.querySelectorAll('[data-modal-close]');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const targetModal = document.getElementById(this.getAttribute('data-modal-target'));
            if (targetModal) {
                showModal(targetModal);
            }
        });
    });

    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                hideModal(modal);
            }
        });
    });

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                hideModal(modal);
            }
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal:not(.hidden)');
            if (openModal) {
                hideModal(openModal);
            }
        }
    });
}

function showModal(modal) {
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    // Focus first focusable element
    const focusableElement = modal.querySelector('input, button, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusableElement) {
        focusableElement.focus();
    }
}

function hideModal(modal) {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Dropdown functionality
function initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-dropdown-toggle]');

    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const targetDropdown = document.getElementById(this.getAttribute('data-dropdown-toggle'));

            // Close other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                if (dropdown !== targetDropdown) {
                    dropdown.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            if (targetDropdown) {
                targetDropdown.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    });
}

// Notification functionality
function initializeNotifications() {
    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            hideNotification(notification);
        }, 5000);
    });

    // Close notification on click
    const notificationCloses = document.querySelectorAll('.notification-close');
    notificationCloses.forEach(close => {
        close.addEventListener('click', function() {
            const notification = this.closest('.notification');
            if (notification) {
                hideNotification(notification);
            }
        });
    });
}

function hideNotification(notification) {
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
        notification.remove();
    }, 300);
}

function showNotification(message, type = 'info') {
    const notification = createNotification(message, type);
    document.body.appendChild(notification);

    // Trigger animation
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto-hide after 5 seconds
    setTimeout(() => {
        hideNotification(notification);
    }, 5000);
}

function createNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    const icons = {
        success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>`,
        error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`,
        warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>`,
        info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>`
    };

    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-3 transform translate-x-full opacity-0 transition-all duration-300`;
    notification.innerHTML = `
        <div class="flex-shrink-0">${icons[type]}</div>
        <div class="flex-1">${message}</div>
        <button class="notification-close flex-shrink-0 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    return notification;
}

// Chart initialization
function initializeCharts() {
    // Set default chart options
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.color = '#6B7280';
    Chart.defaults.scale.grid.color = '#F3F4F6';
    Chart.defaults.plugins.legend.display = false;
}

// Utility functions
function formatCurrency(amount, currency = 'NGN') {
    const formatter = new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
    return formatter.format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat('en-NG').format(number);
}

function formatDate(date, options = {}) {
    const defaultOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    };
    return new Intl.DateTimeFormat('en-NG', { ...defaultOptions, ...options }).format(new Date(date));
}

// Form validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });

    // Email validation
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            showFieldError(field, 'Please enter a valid email address');
            isValid = false;
        }
    });

    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);

    field.classList.add('border-red-500');
    const errorElement = document.createElement('p');
    errorElement.className = 'field-error text-red-500 text-sm mt-1';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Loading states
function showLoading(element, text = 'Loading...') {
    const originalContent = element.innerHTML;
    element.setAttribute('data-original-content', originalContent);
    element.disabled = true;
    element.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        ${text}
    `;
}

function hideLoading(element) {
    const originalContent = element.getAttribute('data-original-content');
    if (originalContent) {
        element.innerHTML = originalContent;
        element.disabled = false;
        element.removeAttribute('data-original-content');
    }
}

// AJAX helpers
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    };

    return fetch(url, { ...defaultOptions, ...options })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Data table functionality
function initializeDataTable(tableId, options = {}) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const defaultOptions = {
        sortable: true,
        searchable: true,
        pagination: true,
        perPage: 10
    };

    const config = { ...defaultOptions, ...options };

    if (config.sortable) {
        addSortingToTable(table);
    }

    if (config.searchable) {
        addSearchToTable(table);
    }

    if (config.pagination) {
        addPaginationToTable(table, config.perPage);
    }
}

function addSortingToTable(table) {
    const headers = table.querySelectorAll('th[data-sortable]');

    headers.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const column = this.getAttribute('data-sortable');
            const currentSort = this.getAttribute('data-sort') || 'asc';
            const newSort = currentSort === 'asc' ? 'desc' : 'asc';

            // Clear other sort indicators
            headers.forEach(h => {
                h.removeAttribute('data-sort');
                h.querySelector('.sort-indicator')?.remove();
            });

            // Set new sort
            this.setAttribute('data-sort', newSort);

            // Add sort indicator
            const indicator = document.createElement('span');
            indicator.className = 'sort-indicator ml-1';
            indicator.innerHTML = newSort === 'asc' ? '↑' : '↓';
            this.appendChild(indicator);

            // Sort table rows
            sortTableByColumn(table, column, newSort);
        });
    });
}

function sortTableByColumn(table, column, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const aValue = a.querySelector(`[data-column="${column}"]`)?.textContent.trim() || '';
        const bValue = b.querySelector(`[data-column="${column}"]`)?.textContent.trim() || '';

        // Try to parse as numbers first
        const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
        const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }

        // Fall back to string comparison
        return direction === 'asc'
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });

    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

function addSearchToTable(table) {
    const searchContainer = document.createElement('div');
    searchContainer.className = 'mb-4';
    searchContainer.innerHTML = `
        <input type="text"
               placeholder="Search..."
               class="table-search w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
    `;

    table.parentNode.insertBefore(searchContainer, table);

    const searchInput = searchContainer.querySelector('.table-search');
    searchInput.addEventListener('input', function() {
        filterTableRows(table, this.value);
    });
}

function filterTableRows(table, searchTerm) {
    const rows = table.querySelectorAll('tbody tr');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

function addPaginationToTable(table, perPage) {
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / perPage);

    // Create pagination container
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'flex items-center justify-between mt-4';
    table.parentNode.appendChild(paginationContainer);

    function showPage(page) {
        const start = (page - 1) * perPage;
        const end = start + perPage;

        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });

        updatePaginationControls();
    }

    function updatePaginationControls() {
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, rows.length);

        paginationContainer.innerHTML = `
            <div class="text-sm text-gray-700">
                Showing ${start} to ${end} of ${rows.length} results
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 border border-gray-300 rounded ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}"
                        ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
                    Previous
                </button>
                <span class="px-3 py-1 bg-primary-600 text-white rounded">
                    ${currentPage}
                </span>
                <button class="px-3 py-1 border border-gray-300 rounded ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}"
                        ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
                    Next
                </button>
            </div>
        `;
    }

    window.changePage = function(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            showPage(currentPage);
        }
    };

    // Show first page initially
    showPage(1);
}

// File upload functionality
function initializeFileUpload(inputId, options = {}) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const defaultOptions = {
        maxSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'],
        multiple: false
    };

    const config = { ...defaultOptions, ...options };

    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);

        files.forEach(file => {
            if (!validateFile(file, config)) {
                e.target.value = '';
                return;
            }

            if (config.preview && file.type.startsWith('image/')) {
                showImagePreview(file, config.previewContainer);
            }
        });
    });
}

function validateFile(file, config) {
    if (file.size > config.maxSize) {
        showNotification(`File size must be less than ${formatFileSize(config.maxSize)}`, 'error');
        return false;
    }

    if (!config.allowedTypes.includes(file.type)) {
        showNotification('File type not allowed', 'error');
        return false;
    }

    return true;
}

function showImagePreview(file, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'max-w-full h-auto rounded-lg';
        container.innerHTML = '';
        container.appendChild(img);
    };
    reader.readAsDataURL(file);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Local storage helpers
function saveToLocalStorage(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
        return true;
    } catch (error) {
        console.error('Error saving to localStorage:', error);
        return false;
    }
}

function getFromLocalStorage(key, defaultValue = null) {
    try {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        console.error('Error reading from localStorage:', error);
        return defaultValue;
    }
}

function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (error) {
        console.error('Error removing from localStorage:', error);
        return false;
    }
}

// Theme functionality
function initializeTheme() {
    const savedTheme = getFromLocalStorage('theme', 'light');
    applyTheme(savedTheme);

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
}

function toggleTheme() {
    const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    applyTheme(newTheme);
    saveToLocalStorage('theme', newTheme);
}

function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('.global-search');
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Ctrl/Cmd + N for new item
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            const newButton = document.querySelector('[data-shortcut="new"]');
            if (newButton) {
                newButton.click();
            }
        }

        // Escape to close modals/dropdowns
        if (e.key === 'Escape') {
            // Close modals
            const openModal = document.querySelector('.modal:not(.hidden)');
            if (openModal) {
                hideModal(openModal);
                return;
            }

            // Close dropdowns
            document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
}

// Auto-save functionality
function initializeAutoSave(formId, interval = 30000) {
    const form = document.getElementById(formId);
    if (!form) return;

    const autoSaveKey = `autosave_${formId}`;

    // Load saved data
    const savedData = getFromLocalStorage(autoSaveKey);
    if (savedData) {
        Object.keys(savedData).forEach(name => {
            const field = form.querySelector(`[name="${name}"]`);
            if (field) {
                field.value = savedData[name];
            }
        });

        showNotification('Draft restored', 'info');
    }

    // Auto-save on interval
    setInterval(() => {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        saveToLocalStorage(autoSaveKey, data);
    }, interval);

    // Clear auto-save on successful submit
    form.addEventListener('submit', function() {
        removeFromLocalStorage(autoSaveKey);
    });
}

// Export functions for global use
window.TenantApp = {
    showNotification,
    hideNotification,
    showModal,
    hideModal,
    formatCurrency,
    formatNumber,
    formatDate,
    validateForm,
    showLoading,
    hideLoading,
    makeRequest,
    initializeDataTable,
    initializeFileUpload,
    saveToLocalStorage,
    getFromLocalStorage,
    removeFromLocalStorage,
    toggleTheme,
    initializeAutoSave
};