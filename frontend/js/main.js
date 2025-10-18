// Main JavaScript for Lamija E-Commerce Store SPA

// Global variables
let currentPage = 'home';
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let user = JSON.parse(localStorage.getItem('user')) || null;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    updateUserStatus();
    showPage('home');
});

// Page navigation function
function showPage(pageName) {
    currentPage = pageName;
    
    // Hide all page content
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = '<div class="loading-spinner"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    // Load the requested page after a short delay for smooth transition
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
        // Apply category filter after page loads
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
        userDropdown.innerHTML = `<i class="fas fa-user"></i> ${user.name}`;
    } else {
        userDropdown.innerHTML = '<i class="fas fa-user"></i> Account';
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
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to toast container or create one
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

// User authentication functions
function login(email, password) {
    // Simulate login - in real app, this would make API call
    if (email && password) {
        user = {
            id: 1,
            name: 'John Doe',
            email: email,
            avatar: 'https://via.placeholder.com/150'
        };
        localStorage.setItem('user', JSON.stringify(user));
        updateUserStatus();
        showToast('Login successful!', 'success');
        showPage('home');
        return true;
    }
    return false;
}

function register(name, email, password) {
    // Simulate registration - in real app, this would make API call
    if (name && email && password) {
        user = {
            id: 1,
            name: name,
            email: email,
            avatar: 'https://via.placeholder.com/150'
        };
        localStorage.setItem('user', JSON.stringify(user));
        updateUserStatus();
        showToast('Registration successful!', 'success');
        showPage('home');
        return true;
    }
    return false;
}

function logout() {
    user = null;
    localStorage.removeItem('user');
    updateUserStatus();
    showToast('Logged out successfully!', 'info');
    showPage('home');
}

// Handle search input (real-time search)
function handleSearchInput(event) {
    // Clear previous timeout
    if (window.searchTimeout) {
        clearTimeout(window.searchTimeout);
    }
    
    // Set new timeout for search (debounced)
    window.searchTimeout = setTimeout(() => {
        const query = event.target.value;
        searchProducts(query);
    }, 300); // 300ms delay for better performance
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
    let filteredProducts = sampleProducts;
    
    // Apply category filter first
    if (categoryId) {
        const categoryName = sampleCategories.find(cat => cat.id == categoryId)?.name;
        filteredProducts = filteredProducts.filter(product => product.category === categoryName);
    }
    
    // Apply search filter
    if (searchQuery && searchQuery.trim() !== '') {
        filteredProducts = filteredProducts.filter(product => 
            product.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            product.description.toLowerCase().includes(searchQuery.toLowerCase())
        );
    }
    
    renderProducts(filteredProducts);
    
    // Show appropriate message
    if (searchQuery && categoryId) {
        const categoryName = sampleCategories.find(cat => cat.id == categoryId)?.name;
        showToast(`Found ${filteredProducts.length} ${categoryName} product(s) for "${searchQuery}"`, 'info');
    } else if (searchQuery) {
        showToast(`Found ${filteredProducts.length} product(s) for "${searchQuery}"`, 'success');
    } else if (categoryId) {
        const categoryName = sampleCategories.find(cat => cat.id == categoryId)?.name;
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
function renderProducts(products) {
    const productsContainer = document.querySelector('#products-grid');
    if (productsContainer) {
        productsContainer.innerHTML = products.map(product => `
            <div class="col-lg-4 col-md-6">
                <div class="card product-card h-100" onclick="showProductDetail(${product.id})">
                    <img src="${product.image}" class="card-img-top" alt="${product.name}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text text-muted small">${product.description}</p>
                        <div class="product-rating mb-2">
                            ${generateStarRating(product.rating)}
                            <span class="ms-1">(${product.rating})</span>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">${formatPrice(product.price)}</span>
                                <button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); addToCart(${product.id}, '${product.name}', ${product.price}, '${product.image}')">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
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

// Generate star rating HTML
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