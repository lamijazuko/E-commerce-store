/**
 * Admin Panel JavaScript
 * Handles admin-specific functionality
 */

// Admin Panel Page
async function loadAdminPage() {
    // Check authentication and admin role
    if (!user) {
        showToast('Please login to access admin panel', 'danger');
        showPage('login');
        return;
    }
    
    if (user.role !== 'admin') {
        showToast('Access denied. Admin privileges required.', 'danger');
        showPage('home');
        return;
    }
    
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container-fluid py-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="mb-0"><i class="fas fa-cog me-2"></i>Admin Panel</h1>
                        <p class="text-muted">Manage your e-commerce store</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4" id="admin-stats">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <h2 class="mb-0" id="stats-users">-</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <h2 class="mb-0" id="stats-products">-</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="mb-0" id="stats-orders">-</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Categories</h5>
                                <h2 class="mb-0" id="stats-categories">-</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Tabs -->
                <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                            <i class="fas fa-users me-2"></i>Users
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button">
                            <i class="fas fa-box me-2"></i>Products
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button">
                            <i class="fas fa-tags me-2"></i>Categories
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="adminTabContent">
                    <!-- Users Tab -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">User Management</h5>
                                <button class="btn btn-primary btn-sm" onclick="showAddUserModal()">
                                    <i class="fas fa-plus me-1"></i>Add User
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="users-table-body">
                                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Tab -->
                    <div class="tab-pane fade" id="products" role="tabpanel">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Product Management</h5>
                                <button class="btn btn-primary btn-sm" onclick="showAddProductModal()">
                                    <i class="fas fa-plus me-1"></i>Add Product
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Category</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-table-body">
                                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Tab -->
                    <div class="tab-pane fade" id="categories" role="tabpanel">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Category Management</h5>
                                <button class="btn btn-primary btn-sm" onclick="showAddCategoryModal()">
                                    <i class="fas fa-plus me-1"></i>Add Category
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="categories-table-body">
                                            <tr><td colspan="3" class="text-center">Loading...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Order Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orders-table-body">
                                            <tr><td colspan="6" class="text-center">Loading...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        pageContent.classList.add('active');
        loadAdminStats();
        loadAdminUsers();
        // Initialize tab handlers after page loads
        setTimeout(() => initAdminTabs(), 200);
    }, 100);
}

// Load admin statistics
async function loadAdminStats() {
    try {
        const [usersRes, productsRes, ordersRes, categoriesRes] = await Promise.all([
            window.API.Users.getAll(),
            window.API.Products.getAll(),
            window.API.Orders.getAll(),
            window.API.Categories.getAll()
        ]);
        
        document.getElementById('stats-users').textContent = usersRes.success ? (usersRes.data.data?.length || 0) : '-';
        document.getElementById('stats-products').textContent = productsRes.success ? (productsRes.data.data?.length || 0) : '-';
        document.getElementById('stats-orders').textContent = ordersRes.success ? (ordersRes.data.data?.length || 0) : '-';
        document.getElementById('stats-categories').textContent = categoriesRes.success ? (categoriesRes.data.data?.length || 0) : '-';
    } catch (error) {
        console.error('Error loading admin stats:', error);
    }
}

// Load users table
async function loadAdminUsers() {
    try {
        const response = await window.API.Users.getAll();
        const tbody = document.getElementById('users-table-body');
        
        if (response.success && response.data.data) {
            tbody.innerHTML = response.data.data.map(user => `
                <tr>
                    <td>${user.user_id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td><span class="badge ${user.role === 'admin' ? 'bg-danger' : 'bg-secondary'}">${user.role}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(${user.user_id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.user_id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>';
        }
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('users-table-body').innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>';
    }
}

// Load products table
async function loadAdminProducts() {
    try {
        const response = await window.API.Products.getAll();
        const tbody = document.getElementById('products-table-body');
        
        if (response.success && response.data.data) {
            tbody.innerHTML = response.data.data.map(product => `
                <tr>
                    <td>${product.product_id}</td>
                    <td>${product.name}</td>
                    <td>$${parseFloat(product.price).toFixed(2)}</td>
                    <td>${product.category_id || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editProduct(${product.product_id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${product.product_id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading products</td></tr>';
        }
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

// Load categories table
async function loadAdminCategories() {
    try {
        const response = await window.API.Categories.getAll();
        const tbody = document.getElementById('categories-table-body');
        
        if (response.success && response.data.data) {
            tbody.innerHTML = response.data.data.map(category => `
                <tr>
                    <td>${category.category_id}</td>
                    <td>${category.name}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editCategory(${category.category_id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${category.category_id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading categories</td></tr>';
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Load orders table
async function loadAdminOrders() {
    try {
        const response = await window.API.Orders.getAll();
        const tbody = document.getElementById('orders-table-body');
        
        if (response.success && response.data.data) {
            tbody.innerHTML = response.data.data.map(order => `
                <tr>
                    <td>${order.order_id}</td>
                    <td>User #${order.user_id}</td>
                    <td>$${parseFloat(order.total_price).toFixed(2)}</td>
                    <td><span class="badge bg-info">${order.status}</span></td>
                    <td>${new Date(order.order_date).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(${order.order_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="updateOrderStatus(${order.order_id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading orders</td></tr>';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
    }
}

// Initialize tab handlers after admin page loads
function initAdminTabs() {
    // Listen for tab changes
    const tabButtons = document.querySelectorAll('#adminTabs [data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(event) {
            const targetId = event.target.getAttribute('data-bs-target');
            
            if (targetId === '#products') {
                loadAdminProducts();
            } else if (targetId === '#categories') {
                loadAdminCategories();
            } else if (targetId === '#orders') {
                loadAdminOrders();
            }
        });
    });
}

// ============================================
// USER MANAGEMENT FUNCTIONS
// ============================================

async function showAddUserModal() {
    const modal = createModal('Add User', `
        <form id="addUserForm">
            <div class="mb-3">
                <label for="userName" class="form-label">Name *</label>
                <input type="text" class="form-control" id="userName" required>
            </div>
            <div class="mb-3">
                <label for="userEmail" class="form-label">Email *</label>
                <input type="email" class="form-control" id="userEmail" required>
            </div>
            <div class="mb-3">
                <label for="userPassword" class="form-label">Password *</label>
                <input type="password" class="form-control" id="userPassword" minlength="6" required>
            </div>
            <div class="mb-3">
                <label for="userRole" class="form-label">Role *</label>
                <select class="form-select" id="userRole" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="userAddress" class="form-label">Address</label>
                <textarea class="form-control" id="userAddress" rows="2"></textarea>
            </div>
        </form>
    `, 'Save User');
    
    document.getElementById('modalConfirmBtn').onclick = async () => {
        const form = document.getElementById('addUserForm');
        if (form.checkValidity()) {
            const userData = {
                name: document.getElementById('userName').value,
                email: document.getElementById('userEmail').value,
                password: document.getElementById('userPassword').value,
                role: document.getElementById('userRole').value,
                address: document.getElementById('userAddress').value || null
            };
            
            try {
                const response = await window.API.Users.create(userData);
                if (response.success) {
                    showToast('User created successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                    loadAdminUsers();
                } else {
                    showToast(response.data?.message || 'Error creating user', 'danger');
                }
            } catch (error) {
                showToast('Error creating user: ' + (error.message || 'Unknown error'), 'danger');
            }
        } else {
            form.reportValidity();
        }
    };
}

async function editUser(id) {
    try {
        const response = await window.API.Users.getById(id);
        if (!response.success || !response.data.data) {
            showToast('Error loading user data', 'danger');
            return;
        }
        
        const user = response.data.data;
        const modal = createModal('Edit User', `
            <form id="editUserForm">
                <div class="mb-3">
                    <label for="editUserName" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="editUserName" value="${user.name || ''}" required>
                </div>
                <div class="mb-3">
                    <label for="editUserEmail" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="editUserEmail" value="${user.email || ''}" required>
                </div>
                <div class="mb-3">
                    <label for="editUserRole" class="form-label">Role *</label>
                    <select class="form-select" id="editUserRole" required>
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editUserAddress" class="form-label">Address</label>
                    <textarea class="form-control" id="editUserAddress" rows="2">${user.address || ''}</textarea>
                </div>
                <div class="mb-3">
                    <label for="editUserPassword" class="form-label">New Password (leave empty to keep current)</label>
                    <input type="password" class="form-control" id="editUserPassword" minlength="6">
                </div>
            </form>
        `, 'Update User');
        
        document.getElementById('modalConfirmBtn').onclick = async () => {
            const form = document.getElementById('editUserForm');
            if (form.checkValidity()) {
                const userData = {
                    name: document.getElementById('editUserName').value,
                    email: document.getElementById('editUserEmail').value,
                    role: document.getElementById('editUserRole').value,
                    address: document.getElementById('editUserAddress').value || null
                };
                
                const newPassword = document.getElementById('editUserPassword').value;
                if (newPassword) {
                    userData.password = newPassword;
                }
                
                try {
                    const updateResponse = await window.API.Users.update(id, userData);
                    if (updateResponse.success) {
                        showToast('User updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                        loadAdminUsers();
                    } else {
                        showToast(updateResponse.data?.message || 'Error updating user', 'danger');
                    }
                } catch (error) {
                    showToast('Error updating user: ' + (error.message || 'Unknown error'), 'danger');
                }
            } else {
                form.reportValidity();
            }
        };
    } catch (error) {
        showToast('Error loading user: ' + (error.message || 'Unknown error'), 'danger');
    }
}

async function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        try {
            const response = await window.API.Users.delete(id);
            if (response.success) {
                showToast('User deleted successfully', 'success');
                loadAdminUsers();
            } else {
                showToast(response.data?.message || 'Error deleting user', 'danger');
            }
        } catch (error) {
            showToast('Error deleting user: ' + (error.message || 'Unknown error'), 'danger');
        }
    }
}

// ============================================
// PRODUCT MANAGEMENT FUNCTIONS
// ============================================

async function showAddProductModal() {
    // Load categories for dropdown
    let categoriesHtml = '<option value="">Select Category</option>';
    try {
        const catResponse = await window.API.Categories.getAll();
        if (catResponse.success && catResponse.data.data) {
            categoriesHtml += catResponse.data.data.map(cat => 
                `<option value="${cat.category_id}">${cat.name}</option>`
            ).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
    
    const modal = createModal('Add Product', `
        <form id="addProductForm">
            <div class="mb-3">
                <label for="productName" class="form-label">Name *</label>
                <input type="text" class="form-control" id="productName" required>
            </div>
            <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Price *</label>
                <input type="number" class="form-control" id="productPrice" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <select class="form-select" id="productCategory">
                    ${categoriesHtml}
                </select>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Image URL</label>
                <input type="url" class="form-control" id="productImage">
            </div>
        </form>
    `, 'Save Product');
    
    document.getElementById('modalConfirmBtn').onclick = async () => {
        const form = document.getElementById('addProductForm');
        if (form.checkValidity()) {
            const productData = {
                name: document.getElementById('productName').value,
                description: document.getElementById('productDescription').value || null,
                price: parseFloat(document.getElementById('productPrice').value),
                category_id: document.getElementById('productCategory').value || null,
                image_url: document.getElementById('productImage').value || null
            };
            
            try {
                const response = await window.API.Products.create(productData);
                if (response.success) {
                    showToast('Product created successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                    loadAdminProducts();
                } else {
                    showToast(response.data?.message || 'Error creating product', 'danger');
                }
            } catch (error) {
                showToast('Error creating product: ' + (error.message || 'Unknown error'), 'danger');
            }
        } else {
            form.reportValidity();
        }
    };
}

async function editProduct(id) {
    try {
        const response = await window.API.Products.getById(id);
        if (!response.success || !response.data.data) {
            showToast('Error loading product data', 'danger');
            return;
        }
        
        const product = response.data.data;
        
        // Load categories for dropdown
        let categoriesHtml = '<option value="">Select Category</option>';
        try {
            const catResponse = await window.API.Categories.getAll();
            if (catResponse.success && catResponse.data.data) {
                categoriesHtml += catResponse.data.data.map(cat => 
                    `<option value="${cat.category_id}" ${cat.category_id == product.category_id ? 'selected' : ''}>${cat.name}</option>`
                ).join('');
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
        
        const modal = createModal('Edit Product', `
            <form id="editProductForm">
                <div class="mb-3">
                    <label for="editProductName" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="editProductName" value="${product.name || ''}" required>
                </div>
                <div class="mb-3">
                    <label for="editProductDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="editProductDescription" rows="3">${product.description || ''}</textarea>
                </div>
                <div class="mb-3">
                    <label for="editProductPrice" class="form-label">Price *</label>
                    <input type="number" class="form-control" id="editProductPrice" step="0.01" min="0" value="${product.price || 0}" required>
                </div>
                <div class="mb-3">
                    <label for="editProductCategory" class="form-label">Category</label>
                    <select class="form-select" id="editProductCategory">
                        ${categoriesHtml}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editProductImage" class="form-label">Image URL</label>
                    <input type="url" class="form-control" id="editProductImage" value="${product.image_url || ''}">
                </div>
            </form>
        `, 'Update Product');
        
        document.getElementById('modalConfirmBtn').onclick = async () => {
            const form = document.getElementById('editProductForm');
            if (form.checkValidity()) {
                const productData = {
                    name: document.getElementById('editProductName').value,
                    description: document.getElementById('editProductDescription').value || null,
                    price: parseFloat(document.getElementById('editProductPrice').value),
                    category_id: document.getElementById('editProductCategory').value || null,
                    image_url: document.getElementById('editProductImage').value || null
                };
                
                try {
                    const updateResponse = await window.API.Products.update(id, productData);
                    if (updateResponse.success) {
                        showToast('Product updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                        loadAdminProducts();
                    } else {
                        showToast(updateResponse.data?.message || 'Error updating product', 'danger');
                    }
                } catch (error) {
                    showToast('Error updating product: ' + (error.message || 'Unknown error'), 'danger');
                }
            } else {
                form.reportValidity();
            }
        };
    } catch (error) {
        showToast('Error loading product: ' + (error.message || 'Unknown error'), 'danger');
    }
}

async function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        try {
            const response = await window.API.Products.delete(id);
            if (response.success) {
                showToast('Product deleted successfully', 'success');
                loadAdminProducts();
            } else {
                showToast(response.data?.message || 'Error deleting product', 'danger');
            }
        } catch (error) {
            showToast('Error deleting product: ' + (error.message || 'Unknown error'), 'danger');
        }
    }
}

// ============================================
// CATEGORY MANAGEMENT FUNCTIONS
// ============================================

async function showAddCategoryModal() {
    const modal = createModal('Add Category', `
        <form id="addCategoryForm">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Name *</label>
                <input type="text" class="form-control" id="categoryName" required>
            </div>
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
            </div>
        </form>
    `, 'Save Category');
    
    document.getElementById('modalConfirmBtn').onclick = async () => {
        const form = document.getElementById('addCategoryForm');
        if (form.checkValidity()) {
            const categoryData = {
                name: document.getElementById('categoryName').value,
                description: document.getElementById('categoryDescription').value || null
            };
            
            try {
                const response = await window.API.Categories.create(categoryData);
                if (response.success) {
                    showToast('Category created successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                    loadAdminCategories();
                } else {
                    showToast(response.data?.message || 'Error creating category', 'danger');
                }
            } catch (error) {
                showToast('Error creating category: ' + (error.message || 'Unknown error'), 'danger');
            }
        } else {
            form.reportValidity();
        }
    };
}

async function editCategory(id) {
    try {
        const response = await window.API.Categories.getById(id);
        if (!response.success || !response.data.data) {
            showToast('Error loading category data', 'danger');
            return;
        }
        
        const category = response.data.data;
        const modal = createModal('Edit Category', `
            <form id="editCategoryForm">
                <div class="mb-3">
                    <label for="editCategoryName" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="editCategoryName" value="${category.name || ''}" required>
                </div>
                <div class="mb-3">
                    <label for="editCategoryDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="editCategoryDescription" rows="3">${category.description || ''}</textarea>
                </div>
            </form>
        `, 'Update Category');
        
        document.getElementById('modalConfirmBtn').onclick = async () => {
            const form = document.getElementById('editCategoryForm');
            if (form.checkValidity()) {
                const categoryData = {
                    name: document.getElementById('editCategoryName').value,
                    description: document.getElementById('editCategoryDescription').value || null
                };
                
                try {
                    const updateResponse = await window.API.Categories.update(id, categoryData);
                    if (updateResponse.success) {
                        showToast('Category updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                        loadAdminCategories();
                    } else {
                        showToast(updateResponse.data?.message || 'Error updating category', 'danger');
                    }
                } catch (error) {
                    showToast('Error updating category: ' + (error.message || 'Unknown error'), 'danger');
                }
            } else {
                form.reportValidity();
            }
        };
    } catch (error) {
        showToast('Error loading category: ' + (error.message || 'Unknown error'), 'danger');
    }
}

async function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        try {
            const response = await window.API.Categories.delete(id);
            if (response.success) {
                showToast('Category deleted successfully', 'success');
                loadAdminCategories();
            } else {
                showToast(response.data?.message || 'Error deleting category', 'danger');
            }
        } catch (error) {
            showToast('Error deleting category: ' + (error.message || 'Unknown error'), 'danger');
        }
    }
}

// ============================================
// ORDER MANAGEMENT FUNCTIONS
// ============================================

async function viewOrder(id) {
    try {
        const response = await window.API.Orders.getById(id);
        if (!response.success || !response.data.data) {
            showToast('Error loading order data', 'danger');
            return;
        }
        
        const order = response.data.data;
        const modal = createModal('Order Details', `
            <div class="mb-3">
                <strong>Order ID:</strong> ${order.order_id}<br>
                <strong>User ID:</strong> ${order.user_id}<br>
                <strong>Total Price:</strong> $${parseFloat(order.total_price).toFixed(2)}<br>
                <strong>Status:</strong> <span class="badge bg-info">${order.status}</span><br>
                <strong>Date:</strong> ${new Date(order.order_date).toLocaleString()}
            </div>
        `, 'Close');
        
        document.getElementById('modalConfirmBtn').onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
        };
    } catch (error) {
        showToast('Error loading order: ' + (error.message || 'Unknown error'), 'danger');
    }
}

async function updateOrderStatus(id) {
    try {
        const response = await window.API.Orders.getById(id);
        if (!response.success || !response.data.data) {
            showToast('Error loading order data', 'danger');
            return;
        }
        
        const order = response.data.data;
        const statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        
        const modal = createModal('Update Order Status', `
            <form id="updateOrderStatusForm">
                <div class="mb-3">
                    <label for="orderStatus" class="form-label">Status *</label>
                    <select class="form-select" id="orderStatus" required>
                        ${statuses.map(status => 
                            `<option value="${status}" ${status === order.status ? 'selected' : ''}>${status}</option>`
                        ).join('')}
                    </select>
                </div>
            </form>
        `, 'Update Status');
        
        document.getElementById('modalConfirmBtn').onclick = async () => {
            const form = document.getElementById('updateOrderStatusForm');
            if (form.checkValidity()) {
                const status = document.getElementById('orderStatus').value;
                
                try {
                    const updateResponse = await window.API.Orders.updateStatus(id, status);
                    if (updateResponse.success) {
                        showToast('Order status updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('genericModal')).hide();
                        loadAdminOrders();
                    } else {
                        showToast(updateResponse.data?.message || 'Error updating order status', 'danger');
                    }
                } catch (error) {
                    showToast('Error updating order status: ' + (error.message || 'Unknown error'), 'danger');
                }
            } else {
                form.reportValidity();
            }
        };
    } catch (error) {
        showToast('Error loading order: ' + (error.message || 'Unknown error'), 'danger');
    }
}

// ============================================
// HELPER FUNCTION TO CREATE MODAL
// ============================================

function createModal(title, body, confirmText = 'Save', cancelText = 'Cancel') {
    // Remove existing modal if any
    const existingModal = document.getElementById('genericModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Create modal HTML
    const modalHtml = `
        <div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="genericModalLabel">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${body}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>
                        <button type="button" class="btn btn-primary" id="modalConfirmBtn">${confirmText}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Append to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('genericModal'));
    modal.show();
    
    // Clean up when hidden
    document.getElementById('genericModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
    
    return modal;
}

