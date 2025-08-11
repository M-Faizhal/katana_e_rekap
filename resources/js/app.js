import './bootstrap';

// KATANA Dashboard JavaScript

// Navigation active state
document.addEventListener('DOMContentLoaded', function() {
    // Update active navigation based on current URL
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar nav a');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.substring(1))) {
            link.classList.add('bg-red-500');
        }
    });
});

// Search functionality
function performSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const searchTerm = searchInput.value.trim();
        if (searchTerm !== '') {
            // Implement search logic here
            console.log('Searching for:', searchTerm);
            // You can redirect to search results page or show results inline
        }
    }
}

// Dropdown menus
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

function toggleMonthFilter() {
    const filter = document.getElementById('monthFilter');
    if (filter) {
        filter.classList.toggle('hidden');
    }
}

function selectMonth(month) {
    const monthText = document.getElementById('monthText');
    const monthFilter = document.getElementById('monthFilter');

    if (monthText) monthText.textContent = month;
    if (monthFilter) monthFilter.classList.add('hidden');
}

// Filter functionality
function toggleFilter() {
    // Implement filter functionality
    console.log('Filter toggled');
    // You can show filter modal or sidebar
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const monthFilter = document.getElementById('monthFilter');

    if (!event.target.closest('.relative')) {
        if (userMenu) userMenu.classList.add('hidden');
        if (monthFilter) monthFilter.classList.add('hidden');
    }
});

// Search on Enter key
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
});

// Dashboard animations
function animateCards() {
    const cards = document.querySelectorAll('.card, .bg-white');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
}

// Chart placeholder functionality
function initializeCharts() {
    // This is where you would initialize actual charts
    // For now, we'll just add some visual feedback
    const chartPlaceholders = document.querySelectorAll('.chart-placeholder');
    chartPlaceholders.forEach(placeholder => {
        placeholder.style.cursor = 'pointer';
        placeholder.addEventListener('click', function() {
            alert('Chart integration coming soon!');
        });
    });
}

// Real-time updates simulation
function simulateRealTimeUpdates() {
    // Simulate real-time data updates
    setInterval(() => {
        // Update random statistics
        const statElements = document.querySelectorAll('.text-2xl.font-bold');
        statElements.forEach(element => {
            if (element.textContent.includes('Rp')) {
                // Add slight animation to money values
                element.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }, 30000); // Update every 30 seconds
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;

    const bgColor = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'warning': 'bg-yellow-500',
        'info': 'bg-blue-500'
    }[type] || 'bg-blue-500';

    notification.className += ` ${bgColor} text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Local storage for user preferences
function saveUserPreferences(key, value) {
    localStorage.setItem(`katana_${key}`, JSON.stringify(value));
}

function getUserPreferences(key) {
    const stored = localStorage.getItem(`katana_${key}`);
    return stored ? JSON.parse(stored) : null;
}

// Theme switching
function toggleTheme() {
    const currentTheme = getUserPreferences('theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    document.documentElement.classList.toggle('dark', newTheme === 'dark');
    saveUserPreferences('theme', newTheme);
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load saved theme
    const savedTheme = getUserPreferences('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }

    // Initialize components
    animateCards();
    initializeCharts();
    simulateRealTimeUpdates();

    // Show welcome notification
    setTimeout(() => {
        showNotification('Selamat datang di KATANA Dashboard!', 'success');
    }, 1000);
});

// Export functions for global use
window.performSearch = performSearch;
window.toggleUserMenu = toggleUserMenu;
window.toggleMonthFilter = toggleMonthFilter;
window.selectMonth = selectMonth;
window.toggleFilter = toggleFilter;
window.showNotification = showNotification;
window.toggleTheme = toggleTheme;
