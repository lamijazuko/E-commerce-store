/**
 * API Service
 * Handles all API communication with backend
 */

const API_BASE_URL = 'http://localhost:8080/lamija_e_commerce_store/backend/rest';

// Get authentication token from localStorage
function getAuthToken() {
    const user = JSON.parse(localStorage.getItem('user')) || null;
    return user ? user.token : null;
}

// Get user from localStorage
function getCurrentUser() {
    return JSON.parse(localStorage.getItem('user')) || null;
}

// Set user in localStorage
function setCurrentUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
}

// Remove user from localStorage
function removeCurrentUser() {
    localStorage.removeItem('user');
}

// Make API request with authentication
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const token = getAuthToken();
    
    const defaultHeaders = {
        'Content-Type': 'application/json',
    };
    
    // Add authorization header if token exists
    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }
    
    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {})
        }
    };
    
    try {
        const response = await fetch(url, config);
        const data = await response.json();
        
        // Handle 401 Unauthorized - logout user
        if (response.status === 401) {
            removeCurrentUser();
            if (window.location.pathname !== '/') {
                window.location.href = '/';
            }
        }
        
        return {
            success: response.ok,
            status: response.status,
            data: data
        };
    } catch (error) {
        console.error('API Request Error:', error);
        return {
            success: false,
            status: 0,
            error: error.message
        };
    }
}

// Authentication API
const AuthAPI = {
    // Register new user
    async register(userData) {
        return await apiRequest('/api/auth/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    },
    
    // Login user
    async login(email, password) {
        return await apiRequest('/api/auth/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
    },
    
    // Logout user
    async logout() {
        return await apiRequest('/api/auth/logout', {
            method: 'POST'
        });
    },
    
    // Get current user
    async getCurrentUser() {
        return await apiRequest('/api/auth/me');
    }
};

// Products API
const ProductsAPI = {
    async getAll() {
        return await apiRequest('/api/products');
    },
    
    async getById(id) {
        return await apiRequest(`/api/products/${id}`);
    },
    
    async getByCategory(categoryId) {
        return await apiRequest(`/api/products/category/${categoryId}`);
    },
    
    async create(productData) {
        return await apiRequest('/api/products', {
            method: 'POST',
            body: JSON.stringify(productData)
        });
    },
    
    async update(id, productData) {
        return await apiRequest(`/api/products/${id}`, {
            method: 'PUT',
            body: JSON.stringify(productData)
        });
    },
    
    async delete(id) {
        return await apiRequest(`/api/products/${id}`, {
            method: 'DELETE'
        });
    }
};

// Categories API
const CategoriesAPI = {
    async getAll() {
        return await apiRequest('/api/categories');
    },
    
    async getById(id) {
        return await apiRequest(`/api/categories/${id}`);
    },
    
    async create(categoryData) {
        return await apiRequest('/api/categories', {
            method: 'POST',
            body: JSON.stringify(categoryData)
        });
    },
    
    async update(id, categoryData) {
        return await apiRequest(`/api/categories/${id}`, {
            method: 'PUT',
            body: JSON.stringify(categoryData)
        });
    },
    
    async delete(id) {
        return await apiRequest(`/api/categories/${id}`, {
            method: 'DELETE'
        });
    }
};

// Cart API
const CartAPI = {
    async getUserCart(userId) {
        return await apiRequest(`/api/cart/user/${userId}`);
    },
    
    async getCartTotal(userId) {
        return await apiRequest(`/api/cart/user/${userId}/total`);
    },
    
    async addItem(cartData) {
        return await apiRequest('/api/cart', {
            method: 'POST',
            body: JSON.stringify(cartData)
        });
    },
    
    async updateItem(cartId, quantity) {
        return await apiRequest(`/api/cart/${cartId}`, {
            method: 'PUT',
            body: JSON.stringify({ quantity })
        });
    },
    
    async removeItem(cartId) {
        return await apiRequest(`/api/cart/${cartId}`, {
            method: 'DELETE'
        });
    },
    
    async clearCart(userId) {
        return await apiRequest(`/api/cart/user/${userId}`, {
            method: 'DELETE'
        });
    }
};

// Orders API
const OrdersAPI = {
    async getAll() {
        return await apiRequest('/api/orders');
    },
    
    async getById(id) {
        return await apiRequest(`/api/orders/${id}`);
    },
    
    async getUserOrders(userId) {
        return await apiRequest(`/api/orders/user/${userId}`);
    },
    
    async create(orderData) {
        return await apiRequest('/api/orders', {
            method: 'POST',
            body: JSON.stringify(orderData)
        });
    },
    
    async updateStatus(id, status) {
        return await apiRequest(`/api/orders/${id}/status`, {
            method: 'PUT',
            body: JSON.stringify({ status })
        });
    },
    
    async delete(id) {
        return await apiRequest(`/api/orders/${id}`, {
            method: 'DELETE'
        });
    }
};

// Reviews API
const ReviewsAPI = {
    async getAll() {
        return await apiRequest('/api/reviews');
    },
    
    async getById(id) {
        return await apiRequest(`/api/reviews/${id}`);
    },
    
    async getByProduct(productId) {
        return await apiRequest(`/api/reviews/product/${productId}`);
    },
    
    async getByUser(userId) {
        return await apiRequest(`/api/reviews/user/${userId}`);
    },
    
    async getAverageRating(productId) {
        return await apiRequest(`/api/reviews/product/${productId}/rating`);
    },
    
    async create(reviewData) {
        return await apiRequest('/api/reviews', {
            method: 'POST',
            body: JSON.stringify(reviewData)
        });
    },
    
    async update(id, reviewData) {
        return await apiRequest(`/api/reviews/${id}`, {
            method: 'PUT',
            body: JSON.stringify(reviewData)
        });
    },
    
    async delete(id) {
        return await apiRequest(`/api/reviews/${id}`, {
            method: 'DELETE'
        });
    }
};

// Users API (Admin only)
const UsersAPI = {
    async getAll() {
        return await apiRequest('/api/users');
    },
    
    async getById(id) {
        return await apiRequest(`/api/users/${id}`);
    },
    
    async create(userData) {
        return await apiRequest('/api/users', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    },
    
    async update(id, userData) {
        return await apiRequest(`/api/users/${id}`, {
            method: 'PUT',
            body: JSON.stringify(userData)
        });
    },
    
    async delete(id) {
        return await apiRequest(`/api/users/${id}`, {
            method: 'DELETE'
        });
    }
};

// Export API modules
window.API = {
    Auth: AuthAPI,
    Products: ProductsAPI,
    Categories: CategoriesAPI,
    Cart: CartAPI,
    Orders: OrdersAPI,
    Reviews: ReviewsAPI,
    Users: UsersAPI,
    getCurrentUser,
    setCurrentUser,
    removeCurrentUser,
    getAuthToken
};

