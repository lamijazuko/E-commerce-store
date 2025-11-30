
// Global variables
let currentPage = 'home';
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let user = null; // Will be loaded from localStorage/API on init

// Load user from localStorage or API
function loadUser() {
    const storedUser = JSON.parse(localStorage.getItem('user')) || null;
    if (storedUser && storedUser.token) {
        // Verify token is still valid
        window.API.Auth.getCurrentUser().then(response => {
            if (response.success && response.data.data) {
                user = {
                    ...response.data.data,
                    token: storedUser.token
                };
                setCurrentUser(user);
            } else {
                removeCurrentUser();
                user = null;
            }
            updateUserStatus();
        }).catch(() => {
            user = null;
            removeCurrentUser();
            updateUserStatus();
        });
    } else {
        user = null;
        updateUserStatus();
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', async function() {
    // Load products and categories from API on page load
    if (typeof loadProductsAndCategories === 'function') {
        await loadProductsAndCategories();
    }
    // Load user from localStorage first
    const storedUser = JSON.parse(localStorage.getItem('user')) || null;
    if (storedUser && storedUser.token) {
        user = storedUser;
    }
    
    updateCartCount();
    loadUser(); // Verify user token with API
    showPage('home');
    updateNavigation(); // Update navigation based on user role
});

// Page navigation function
function showPage(pageName) {
    currentPage = pageName;
    
    // Hide all page content
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = '<div class="loading-spinner"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    setTimeout(() => {
        loadPageContent(pageName);
    }, 300);
}

// Page navigation function with category filter
function showPageWithCategory(pageName, categoryId) {
    currentPage = pageName;
    
    // Hide all page content
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = '<div class="loading-spinner"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    // Load the requested page after a short delay for smooth transition
    setTimeout(() => {
        loadPageContent(pageName);
        setTimeout(() => {
            filterByCategory(categoryId);
        }, 200);
    }, 300);
}

// Load page content dynamically
function loadPageContent(pageName) {
    const mainContent = document.getElementById('main-content');
    
    switch(pageName) {
        case 'home':
            loadHomePage();
            break;
        case 'products':
            loadProductsPage();
            break;
        case 'product-detail':
            loadProductDetailPage();
            break;
        case 'categories':
            loadCategoriesPage();
            break;
        case 'cart':
            loadCartPage();
            break;
        case 'login':
            loadLoginPage();
            break;
        case 'register':
            loadRegisterPage();
            break;
        case 'profile':
            loadProfilePage();
            break;
        case 'orders':
            loadOrdersPage();
            break;
        case 'admin':
            loadAdminPage();
            break;
        case 'about':
            loadAboutPage();
            break;
        default:
            loadHomePage();
    }
}

// Update cart count in navigation
function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
}

// Update user status in navigation
function updateUserStatus() {
    const userDropdown = document.getElementById('userDropdown');
    if (user) {
        const roleBadge = user.role === 'admin' ? '<span class="badge bg-danger ms-1">Admin</span>' : '';
        userDropdown.innerHTML = `<i class="fas fa-user"></i> ${user.name || user.email} ${roleBadge}`;
    } else {
        userDropdown.innerHTML = '<i class="fas fa-user"></i> Account';
    }
    updateNavigation(); // Update navigation visibility
}

// Update navigation based on user role
function updateNavigation() {
    // Show/hide admin menu items
    const adminMenuItems = document.querySelectorAll('.admin-only');
    const userMenuItems = document.querySelectorAll('.user-only');
    
    adminMenuItems.forEach(item => {
        item.style.display = isAdmin() ? '' : 'none';
    });
    
    userMenuItems.forEach(item => {
        item.style.display = isAuthenticated() ? '' : 'none';
    });
    
    // Update account dropdown
    const accountDropdown = document.querySelector('#userDropdown + ul');
    if (accountDropdown) {
        const loginItem = accountDropdown.querySelector('[onclick*="login"]');
        const registerItem = accountDropdown.querySelector('[onclick*="register"]');
        const logoutItem = accountDropdown.querySelector('[onclick*="logout"]');
        const profileItem = accountDropdown.querySelector('[onclick*="profile"]');
        const ordersItem = accountDropdown.querySelector('[onclick*="orders"]');
        const adminItem = accountDropdown.querySelector('[onclick*="admin"]');
        
        if (isAuthenticated()) {
            if (loginItem) loginItem.style.display = 'none';
            if (registerItem) registerItem.style.display = 'none';
            if (logoutItem) logoutItem.style.display = '';
            if (profileItem) profileItem.style.display = '';
            if (ordersItem) ordersItem.style.display = '';
            if (isAdmin() && adminItem) adminItem.style.display = '';
        } else {
            if (loginItem) loginItem.style.display = '';
            if (registerItem) registerItem.style.display = '';
            if (logoutItem) logoutItem.style.display = 'none';
            if (profileItem) profileItem.style.display = 'none';
            if (ordersItem) ordersItem.style.display = 'none';
            if (adminItem) adminItem.style.display = 'none';
        }
    }
}

// Add to cart function
function addToCart(productId, productName, price, image) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            image: image,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showToast('Product added to cart!', 'success');
}

// Remove from cart function
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showToast('Product removed from cart!', 'info');
}

// Update cart quantity
function updateCartQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }
    }
}

// Calculate cart total
function calculateCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Show toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// User authentication functions (Updated to use real API)
async function login(email, password) {
    if (!email || !password) {
        showToast('Please enter email and password', 'danger');
        return false;
    }
    
    try {
        const response = await window.API.Auth.login(email, password);
        
        if (response.success && response.data.data) {
            // Store user data with token
            const userData = {
                ...response.data.data,
                token: response.data.token
            };
            user = userData;
            setCurrentUser(userData);
            updateUserStatus();
            showToast('Login successful!', 'success');
            showPage('home');
            return true;
        } else {
            showToast(response.data.message || 'Invalid email or password', 'danger');
            return false;
        }
    } catch (error) {
        console.error('Login error:', error);
        showToast('Login failed. Please try again.', 'danger');
        return false;
    }
}

async function register(name, email, password) {
    if (!name || !email || !password) {
        showToast('Please fill in all fields', 'danger');
        return false;
    }
    
    if (password.length < 6) {
        showToast('Password must be at least 6 characters', 'danger');
        return false;
    }
    
    try {
        const response = await window.API.Auth.register({ name, email, password });
        
        if (response.success && response.data.data) {
            // Store user data with token
            const userData = {
                ...response.data.data,
                token: response.data.token
            };
            user = userData;
            setCurrentUser(userData);
            updateUserStatus();
            showToast('Registration successful! Welcome to EverCart!', 'success');
            showPage('home');
            return true;
        } else {
            showToast(response.data.message || 'Registration failed', 'danger');
            return false;
        }
    } catch (error) {
        console.error('Registration error:', error);
        showToast('Registration failed. Please try again.', 'danger');
        return false;
    }
}

async function logout() {
    try {
        await window.API.Auth.logout();
    } catch (error) {
        console.error('Logout error:', error);
    }
    
    user = null;
    removeCurrentUser();
    updateUserStatus();
    showToast('Logged out successfully!', 'info');
    showPage('home');
}

// Check if user is authenticated
function isAuthenticated() {
    return user !== null;
}

// Check if user is admin
function isAdmin() {
    return user && user.role === 'admin';
}

// Get current user
function getCurrentUser() {
    return user;
}

// Handle search input 
function handleSearchInput(event) {
    // Clear previous timeout
    if (window.searchTimeout) {
        clearTimeout(window.searchTimeout);
    }
    
    // Set new timeout for search 
    window.searchTimeout = setTimeout(() => {
        const query = event.target.value;
        searchProducts(query);
    }, 300); 
}

// Search functionality
function searchProducts(query) {
    console.log('Searching for:', query);
    
    // Get current category filter
    const dropdown = document.querySelector('select[onchange*="filterByCategory"]');
    const categoryId = dropdown ? dropdown.value : '';
    
    // Use combined search and filter function
    searchAndFilterProducts(query, categoryId);
}

// Combined search and filter function
function searchAndFilterProducts(searchQuery, categoryId) {
    // Use global products array (loaded from API)
    let filteredProducts = products || [];
    
    // Apply category filter first
    if (categoryId) {
        filteredProducts = filteredProducts.filter(product => product.category_id == categoryId);
    }
    
    // Apply search filter
    if (searchQuery && searchQuery.trim() !== '') {
        filteredProducts = filteredProducts.filter(product => 
            product.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (product.description && product.description.toLowerCase().includes(searchQuery.toLowerCase()))
        );
    }
    
    renderProducts(filteredProducts);
    
    // Show appropriate message
    if (searchQuery && categoryId) {
        const category = categories.find(cat => cat.id == categoryId);
        const categoryName = category ? category.name : 'Unknown';
        showToast(`Found ${filteredProducts.length} ${categoryName} product(s) for "${searchQuery}"`, 'info');
    } else if (searchQuery) {
        showToast(`Found ${filteredProducts.length} product(s) for "${searchQuery}"`, 'success');
    } else if (categoryId) {
        const category = categories.find(cat => cat.id == categoryId);
        const categoryName = category ? category.name : 'Unknown';
        showToast(`Showing ${categoryName} products (${filteredProducts.length} items)`, 'info');
    } else {
        showToast('Showing all products', 'info');
    }
}

// Filter products by category
function filterByCategory(categoryId) {
    console.log('Filtering by category ID:', categoryId);
    
    // Update the dropdown to show the selected category
    const dropdown = document.querySelector('select[onchange*="filterByCategory"]');
    if (dropdown) {
        dropdown.value = categoryId;
    }
    
    // Get current search query
    const searchInput = document.getElementById('searchInput');
    const searchQuery = searchInput ? searchInput.value : '';
    
    // Use combined search and filter function
    searchAndFilterProducts(searchQuery, categoryId);
}

// Render products function
function renderProducts(productsToRender) {
    const productsContainer = document.querySelector('#products-grid');
    if (productsContainer) {
        if (!productsToRender || productsToRender.length === 0) {
            productsContainer.innerHTML = '<div class="col-12 text-center"><p class="text-muted">No products found.</p></div>';
            return;
        }
        
        productsContainer.innerHTML = productsToRender.map(product => {
            const safeName = (product.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeImage = product.image || 'https://via.placeholder.com/300x200?text=No+Image';
            const rating = product.rating || 4.5;
            return `
            <div class="col-lg-4 col-md-6">
                <div class="card product-card h-100" onclick="showProductDetail(${product.id})">
                    <img src="${safeImage}" class="card-img-top" alt="${safeName}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${safeName}</h5>
                        <p class="card-text text-muted small">${(product.description || '').substring(0, 100)}${product.description && product.description.length > 100 ? '...' : ''}</p>
                        <div class="product-rating mb-2">
                            ${generateStarRating(rating)}
                            <span class="ms-1">(${rating.toFixed(1)})</span>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">${formatPrice(product.price)}</span>
                                <button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); addToCart(${product.id}, '${safeName}', ${product.price}, '${safeImage}')">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }).join('');
    }
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

// Format date
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function generateStarRating(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

// Utility function to get URL parameters
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Utility function to set URL without page reload
function setUrl(page, params = {}) {
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    Object.keys(params).forEach(key => {
        url.searchParams.set(key, params[key]);
    });
    window.history.pushState({page, params}, '', url);
}

// Handle browser back/forward buttons
window.addEventListener('popstate', function(event) {
    if (event.state) {
        showPage(event.state.page);
    }
});

// Initialize URL handling
if (window.location.search) {
    const page = getUrlParameter('page');
    if (page) {
        showPage(page);
    }
}