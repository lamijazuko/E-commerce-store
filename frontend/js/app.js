// App.js 

// Global variables to store products and categories from API
let products = [];
let categories = [];

// Load products and categories from API
async function loadProductsAndCategories() {
    try {
        const [productsRes, categoriesRes] = await Promise.all([
            window.API.Products.getAll(),
            window.API.Categories.getAll()
        ]);
        
        if (productsRes.success && productsRes.data.data) {
            products = productsRes.data.data.map(p => ({
                id: p.product_id,
                name: p.name,
                price: parseFloat(p.price),
                image: p.image_url || 'https://via.placeholder.com/300x200?text=No+Image',
                category_id: p.category_id,
                description: p.description || '',
                rating: 4.5 // Default rating, can fetch from reviews API if needed
            }));
        }
        
        if (categoriesRes.success && categoriesRes.data.data) {
            // Map category icons based on name
            const categoryIcons = {
                'Electronics': 'fas fa-laptop',
                'Clothing': 'fas fa-tshirt',
                'Home & Kitchen': 'fas fa-home',
                'Accessories': 'fas fa-gem',
                'Sports': 'fas fa-dumbbell',
                'Books': 'fas fa-book'
            };
            
            categories = categoriesRes.data.data.map(cat => {
                const productCount = products.filter(p => p.category_id == cat.category_id).length;
                return {
                    id: cat.category_id,
                    name: cat.name,
                    icon: categoryIcons[cat.name] || 'fas fa-box',
                    count: productCount,
                    description: cat.description || ''
                };
            });
        }
    } catch (error) {
        console.error('Error loading products/categories:', error);
        showToast('Error loading products. Please refresh the page.', 'danger');
    }
}

// Helper function to get category name by ID
function getCategoryName(categoryId) {
    const category = categories.find(c => c.id == categoryId);
    return category ? category.name : 'Unknown';
}

// Helper function to get rating for a product (from reviews)
async function getProductRating(productId) {
    try {
        const ratingRes = await window.API.Reviews.getAverageRating(productId);
        if (ratingRes.success && ratingRes.data.data) {
            return parseFloat(ratingRes.data.data.avg_rating || 4.5);
        }
    } catch (error) {
        console.error('Error loading rating:', error);
    }
    return 4.5; // Default rating
}

// Legacy hardcoded products (kept for reference, but will be replaced)
const sampleProducts = [
    // Electronics
    {
        id: 1,
        name: "Wireless Bluetooth Headphones",
        price: 99.99,
        image: "https://via.placeholder.com/300x200/007bff/ffffff?text=Headphones",
        category: "Electronics",
        rating: 4.5,
        description: "High-quality wireless headphones with noise cancellation and long battery life."
    },
    {
        id: 2,
        name: "Smart Fitness Watch",
        price: 199.99,
        image: "https://via.placeholder.com/300x200/28a745/ffffff?text=Fitness+Watch",
        category: "Electronics",
        rating: 4.8,
        description: "Track your fitness goals with this advanced smartwatch featuring heart rate monitoring."
    },
    {
        id: 5,
        name: "Wireless Phone Charger",
        price: 39.99,
        image: "https://via.placeholder.com/300x200/6f42c1/ffffff?text=Charger",
        category: "Electronics",
        rating: 4.3,
        description: "Fast wireless charging pad compatible with all Qi-enabled devices."
    },
    {
        id: 7,
        name: "Gaming Laptop",
        price: 1299.99,
        image: "https://via.placeholder.com/300x200/17a2b8/ffffff?text=Gaming+Laptop",
        category: "Electronics",
        rating: 4.7,
        description: "High-performance gaming laptop with RTX graphics and fast SSD storage."
    },
    {
        id: 8,
        name: "Bluetooth Speaker",
        price: 79.99,
        image: "https://via.placeholder.com/300x200/6c757d/ffffff?text=Speaker",
        category: "Electronics",
        rating: 4.4,
        description: "Portable Bluetooth speaker with 360-degree sound and waterproof design."
    },
    
    // Clothing
    {
        id: 3,
        name: "Organic Cotton T-Shirt",
        price: 24.99,
        image: "https://via.placeholder.com/300x200/ffc107/000000?text=T-Shirt",
        category: "Clothing",
        rating: 4.2,
        description: "Comfortable organic cotton t-shirt available in multiple colors."
    },
    {
        id: 9,
        name: "Denim Jeans",
        price: 59.99,
        image: "https://via.placeholder.com/300x200/343a40/ffffff?text=Jeans",
        category: "Clothing",
        rating: 4.6,
        description: "Classic fit denim jeans made from premium cotton with stretch comfort."
    },
    {
        id: 10,
        name: "Running Shoes",
        price: 89.99,
        image: "https://via.placeholder.com/300x200/e83e8c/ffffff?text=Running+Shoes",
        category: "Clothing",
        rating: 4.5,
        description: "Lightweight running shoes with advanced cushioning and breathable mesh."
    },
    
    // Home & Kitchen
    {
        id: 4,
        name: "Stainless Steel Water Bottle",
        price: 29.99,
        image: "https://via.placeholder.com/300x200/dc3545/ffffff?text=Water+Bottle",
        category: "Home & Kitchen",
        rating: 4.6,
        description: "Insulated stainless steel water bottle that keeps drinks cold for 24 hours."
    },
    {
        id: 11,
        name: "Coffee Maker",
        price: 149.99,
        image: "https://via.placeholder.com/300x200/795548/ffffff?text=Coffee+Maker",
        category: "Home & Kitchen",
        rating: 4.8,
        description: "Programmable coffee maker with built-in grinder and thermal carafe."
    },
    {
        id: 12,
        name: "Non-Stick Cookware Set",
        price: 199.99,
        image: "https://via.placeholder.com/300x200/607d8b/ffffff?text=Cookware",
        category: "Home & Kitchen",
        rating: 4.7,
        description: "Professional-grade non-stick cookware set with durable ceramic coating."
    },
    
    // Accessories
    {
        id: 6,
        name: "Leather Wallet",
        price: 49.99,
        image: "https://via.placeholder.com/300x200/fd7e14/ffffff?text=Wallet",
        category: "Accessories",
        rating: 4.4,
        description: "Genuine leather wallet with RFID blocking technology."
    },
    {
        id: 13,
        name: "Sunglasses",
        price: 129.99,
        image: "https://via.placeholder.com/300x200/212529/ffffff?text=Sunglasses",
        category: "Accessories",
        rating: 4.3,
        description: "UV protection sunglasses with polarized lenses and lightweight frame."
    },
    {
        id: 14,
        name: "Backpack",
        price: 79.99,
        image: "https://via.placeholder.com/300x200/20c997/ffffff?text=Backpack",
        category: "Accessories",
        rating: 4.5,
        description: "Durable travel backpack with laptop compartment and multiple pockets."
    },
    
    // Sports
    {
        id: 15,
        name: "Yoga Mat",
        price: 39.99,
        image: "https://via.placeholder.com/300x200/28a745/ffffff?text=Yoga+Mat",
        category: "Sports",
        rating: 4.6,
        description: "Premium yoga mat with superior grip and cushioning for all poses."
    },
    {
        id: 16,
        name: "Dumbbells Set",
        price: 89.99,
        image: "https://via.placeholder.com/300x200/6c757d/ffffff?text=Dumbbells",
        category: "Sports",
        rating: 4.7,
        description: "Adjustable dumbbells set with multiple weight options for home workouts."
    },
    
    // Books
    {
        id: 17,
        name: "Programming Guide",
        price: 29.99,
        image: "https://via.placeholder.com/300x200/17a2b8/ffffff?text=Programming+Book",
        category: "Books",
        rating: 4.8,
        description: "Complete guide to modern programming with practical examples and exercises."
    },
    {
        id: 18,
        name: "Business Strategy",
        price: 24.99,
        image: "https://via.placeholder.com/300x200/6f42c1/ffffff?text=Business+Book",
        category: "Books",
        rating: 4.5,
        description: "Essential business strategy guide for entrepreneurs and managers."
    }
];

const sampleCategories = [
    { id: 1, name: "Electronics", icon: "fas fa-laptop", count: 5 },
    { id: 2, name: "Clothing", icon: "fas fa-tshirt", count: 3 },
    { id: 3, name: "Home & Kitchen", icon: "fas fa-home", count: 3 },
    { id: 4, name: "Accessories", icon: "fas fa-gem", count: 3 },
    { id: 5, name: "Sports", icon: "fas fa-dumbbell", count: 2 },
    { id: 6, name: "Books", icon: "fas fa-book", count: 2 }
];

const sampleOrders = [
    {
        id: 1,
        date: "2024-01-15",
        total: 149.98,
        status: "delivered",
        items: [
            { name: "Wireless Bluetooth Headphones", quantity: 1, price: 99.99 },
            { name: "Organic Cotton T-Shirt", quantity: 2, price: 24.99 }
        ]
    },
    {
        id: 2,
        date: "2024-01-10",
        total: 239.98,
        status: "shipped",
        items: [
            { name: "Smart Fitness Watch", quantity: 1, price: 199.99 },
            { name: "Stainless Steel Water Bottle", quantity: 1, price: 29.99 }
        ]
    },
    {
        id: 3,
        date: "2024-01-05",
        total: 89.98,
        status: "processing",
        items: [
            { name: "Wireless Phone Charger", quantity: 1, price: 39.99 },
            { name: "Leather Wallet", quantity: 1, price: 49.99 }
        ]
    }
];

// Home Page
async function loadHomePage() {
    const mainContent = document.getElementById('main-content');
    
    // Load products and categories first
    if (products.length === 0 || categories.length === 0) {
        await loadProductsAndCategories();
    }
    
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <!-- Hero Section -->
            <section class="hero-section" style="background-image: url('images/wallpaper_image.jpg');">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="display-4 fw-bold">Welcome to EverCart</h1>
                            <p class="lead">Discover amazing products at unbeatable prices. Shop with confidence and enjoy fast, reliable delivery.</p>
                            <div class="mt-4">
                                <a href="#" class="btn btn-light btn-lg me-3" onclick="showPage('products')">
                                    <i class="fas fa-shopping-bag me-2"></i>Shop Now
                                </a>
                                <a href="#" class="btn btn-outline-light btn-lg" onclick="showPage('about')">
                                    <i class="fas fa-info-circle me-2"></i>Learn More
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <!-- Background image will be applied via CSS -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Categories -->
            <section class="py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center mb-5">Shop by Category</h2>
                        </div>
                    </div>
                    <div class="row g-4" id="categories-section">
                        ${categories.slice(0, 4).map(category => `
                            <div class="col-md-3 col-sm-6">
                                <a href="#" class="category-card" onclick="showPageWithCategory('products', ${category.id});">
                                    <i class="${category.icon} fa-3x mb-3"></i>
                                    <h5>${category.name}</h5>
                                    <p class="mb-0">${category.count} products</p>
                                </a>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </section>

            <!-- Featured Products -->
            <section class="py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center mb-5">Featured Products</h2>
                        </div>
                    </div>
                    <div class="row g-4" id="featured-products-section">
                        ${products.slice(0, 4).map(product => `
                            <div class="col-lg-3 col-md-6">
                                <div class="card product-card h-100" onclick="showProductDetail(${product.id})">
                                    <img src="${product.image}" class="card-img-top" alt="${product.name}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text text-muted small">${product.description}</p>
                                        <div class="product-rating mb-2">
                                            ${generateStarRating(product.rating)}
                                            <span class="ms-1">(${product.rating.toFixed(1)})</span>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="product-price">${formatPrice(product.price)}</span>
                                                <button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}')">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-primary btn-lg" onclick="showPage('products')">
                            View All Products <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-5">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                <h4>Fast Shipping</h4>
                                <p class="text-muted">Free shipping on orders over $50. Fast and reliable delivery to your doorstep.</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <h4>Secure Payment</h4>
                                <p class="text-muted">Your payment information is secure with our encrypted payment system.</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                                <h4>24/7 Support</h4>
                                <p class="text-muted">Our customer support team is available 24/7 to help with any questions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    `;
    
    // Add active class after content is loaded
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        pageContent.classList.add('active');
    }, 100);
}

// Products Page
async function loadProductsPage() {
    const mainContent = document.getElementById('main-content');
    
    // Load products and categories if not already loaded
    if (products.length === 0 || categories.length === 0) {
        await loadProductsAndCategories();
    }
    
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4">Our Products</h1>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search products..." id="searchInput" 
                                   onkeyup="handleSearchInput(event)" oninput="handleSearchInput(event)">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchProducts(document.getElementById('searchInput').value)">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" onchange="filterByCategory(this.value)">
                            <option value="">All Categories</option>
                            ${categories.map(category => `
                                <option value="${category.id}">${category.name}</option>
                            `).join('')}
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4" id="products-grid">
                    <div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        pageContent.classList.add('active');
        
        // Render all products initially
        renderProducts(products);
    }, 100);
}

// Product Detail Page
async function showProductDetail(productId) {
    let product = products.find(p => p.id === productId);
    
    // If product not in cache, fetch from API
    if (!product) {
        try {
            const response = await window.API.Products.getById(productId);
            if (response.success && response.data.data) {
                const p = response.data.data;
                product = {
                    id: p.product_id,
                    name: p.name,
                    price: parseFloat(p.price),
                    image: p.image_url || 'https://via.placeholder.com/300x200?text=No+Image',
                    category_id: p.category_id,
                    description: p.description || '',
                    rating: 4.5
                };
            } else {
                showToast('Product not found', 'danger');
                showPage('products');
                return;
            }
        } catch (error) {
            showToast('Error loading product', 'danger');
            showPage('products');
            return;
        }
    }
    
    // Get rating from reviews API
    const rating = await getProductRating(productId);
    product.rating = rating;
    
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="showPage('home')">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="showPage('products')">Products</a></li>
                        <li class="breadcrumb-item active">${product.name}</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6">
                        <img src="${product.image}" class="img-fluid rounded shadow" alt="${product.name}" onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                    </div>
                    <div class="col-md-6">
                        <h1 class="mb-3">${product.name}</h1>
                        <div class="product-rating mb-3">
                            ${generateStarRating(product.rating)}
                            <span class="ms-2">${product.rating.toFixed(1)} (reviews)</span>
                        </div>
                        <h2 class="product-price mb-4">${formatPrice(product.price)}</h2>
                        <p class="lead mb-4">${product.description}</p>
                        
                        <div class="mb-4">
                            <label class="form-label">Quantity:</label>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(-1)">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1" id="quantity">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(1)">+</button>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex">
                            <button class="btn btn-primary btn-lg" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}')">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                            <button class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-heart me-2"></i>Wishlist
                            </button>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="row">
                            <div class="col-6">
                                <h6><i class="fas fa-truck me-2"></i>Free Shipping</h6>
                                <p class="small text-muted">On orders over $50</p>
                            </div>
                            <div class="col-6">
                                <h6><i class="fas fa-undo me-2"></i>Easy Returns</h6>
                                <p class="small text-muted">30-day return policy</p>
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
    }, 100);
}

// Categories Page
async function loadCategoriesPage() {
    const mainContent = document.getElementById('main-content');
    
    // Load categories if not already loaded
    if (categories.length === 0) {
        await loadProductsAndCategories();
    }
    
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4">Product Categories</h1>
                        <p class="lead text-muted">Browse our wide selection of product categories</p>
                    </div>
                </div>

                <div class="row g-4">
                    ${categories.map(category => `
                        <div class="col-lg-4 col-md-6">
                            <div class="card category-card h-100 text-center" onclick="showPageWithCategory('products', ${category.id});">
                                <div class="card-body">
                                    <i class="${category.icon} fa-4x text-primary mb-3"></i>
                                    <h4 class="card-title">${category.name}</h4>
                                    ${category.description ? `<p class="card-text text-muted small">${category.description}</p>` : ''}
                                    <p class="card-text">${category.count} products available</p>
                                    <a href="#" class="btn btn-primary" onclick="event.stopPropagation(); showPageWithCategory('products', ${category.id});">
                                        Browse Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        pageContent.classList.add('active');
    }, 100);
}

// Cart Page
function loadCartPage() {
    const mainContent = document.getElementById('main-content');
    
    if (cart.length === 0) {
        mainContent.innerHTML = `
            <div class="page-content fade-in">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                            <h2>Your cart is empty</h2>
                            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                            <a href="#" class="btn btn-primary btn-lg" onclick="showPage('products')">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else {
        mainContent.innerHTML = `
            <div class="page-content fade-in">
                <div class="container py-5">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="mb-4">Shopping Cart</h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            ${cart.map(item => `
                                <div class="cart-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="${item.image}" alt="${item.name}" class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1">${item.name}</h5>
                                            <p class="text-muted mb-0">${formatPrice(item.price)} each</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                                <input type="number" class="form-control text-center" value="${item.quantity}" min="1" onchange="updateCartQuantity(${item.id}, parseInt(this.value))">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>${formatPrice(item.price * item.quantity)}</strong>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-outline-danger btn-sm" onclick="removeFromCart(${item.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>

                        <div class="col-lg-4">
                            <div class="cart-summary">
                                <h4>Order Summary</h4>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${formatPrice(calculateCartTotal())}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>${calculateCartTotal() > 50 ? 'Free' : formatPrice(9.99)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>${formatPrice(calculateCartTotal() * 0.08)}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    <strong>${formatPrice(calculateCartTotal() + (calculateCartTotal() > 50 ? 0 : 9.99) + (calculateCartTotal() * 0.08))}</strong>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" onclick="showToast('Checkout functionality would be implemented here!', 'info')">
                                        <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                                    </button>
                                    <a href="#" class="btn btn-outline-primary" onclick="showPage('products')">
                                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        pageContent.classList.add('active');
    }, 100);
}

// Login Page
function loadLoginPage() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <h2 class="text-center mb-4">Login to Your Account</h2>
                                <form onsubmit="handleLogin(event)">
                                    <div class="mb-3">
                                        <label for="loginEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="loginEmail" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="loginPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="loginPassword" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                                    </div>
                                </form>
                                <hr class="my-4">
                                <div class="text-center">
                                    <p class="mb-0">Don't have an account? <a href="#" onclick="showPage('register')">Sign up here</a></p>
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
    }, 100);
}

// Register Page
function loadRegisterPage() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <h2 class="text-center mb-4">Create Your Account</h2>
                                <form onsubmit="handleRegister(event)">
                                    <div class="mb-3">
                                        <label for="registerName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="registerName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="registerEmail" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="registerPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">I agree to the Terms and Conditions</label>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                                    </div>
                                </form>
                                <hr class="my-4">
                                <div class="text-center">
                                    <p class="mb-0">Already have an account? <a href="#" onclick="showPage('login')">Login here</a></p>
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
    }, 100);
}

// Profile Page (Updated to use real API)
async function loadProfilePage() {
    if (!user) {
        showToast('Please login to view your profile', 'danger');
        showPage('login');
        return;
    }
    
    // Load fresh user data from API
    try {
        const response = await window.API.Auth.getCurrentUser();
        if (response.success && response.data.data) {
            user = { ...response.data.data, token: user.token };
            setCurrentUser(user);
        }
    } catch (error) {
        console.error('Error loading user data:', error);
    }
    
    const mainContent = document.getElementById('main-content');
    const roleBadge = user.role === 'admin' ? '<span class="badge bg-danger ms-2">Admin</span>' : '<span class="badge bg-secondary ms-2">User</span>';
    
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                                </div>
                                <h4>${user.name || 'User'}</h4>
                                <p class="text-muted mb-2">${user.email}</p>
                                ${roleBadge}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Profile Information</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="handleProfileUpdate(event)">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="profile-name" value="${user.name || ''}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="profile-email" value="${user.email || ''}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" id="profile-address" rows="3">${user.address || ''}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="${user.role || 'user'}" disabled>
                                        <small class="text-muted">Role cannot be changed by user</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
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
    }, 100);
}

// Handle profile update
async function handleProfileUpdate(event) {
    event.preventDefault();
    if (!user) return;
    
    const name = document.getElementById('profile-name').value;
    const email = document.getElementById('profile-email').value;
    const address = document.getElementById('profile-address').value;
    
    try {
        const response = await window.API.Users.update(user.user_id, { name, email, address });
        if (response.success) {
            user = { ...user, ...response.data.data, token: user.token };
            setCurrentUser(user);
            updateUserStatus();
            showToast('Profile updated successfully!', 'success');
        } else {
            showToast(response.data.message || 'Error updating profile', 'danger');
        }
    } catch (error) {
        console.error('Profile update error:', error);
        showToast('Error updating profile', 'danger');
    }
}

// Orders Page (Updated to use real API)
async function loadOrdersPage() {
    if (!user) {
        showToast('Please login to view your orders', 'danger');
        showPage('login');
        return;
    }
    
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4">My Orders</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div id="orders-container">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Load orders from API
    try {
        const response = await window.API.Orders.getUserOrders(user.user_id);
        const container = document.getElementById('orders-container');
        
        if (response.success && response.data.data && response.data.data.length > 0) {
            container.innerHTML = response.data.data.map(order => `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h6 class="mb-1">Order #${order.order_id}</h6>
                                <p class="text-muted mb-0 small">${new Date(order.order_date).toLocaleDateString()}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-info">${order.status || 'Pending'}</span></p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-0"><strong>Total:</strong> ${formatPrice(parseFloat(order.total_price))}</p>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-outline-primary btn-sm" onclick="viewOrderDetails(${order.order_id})">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="alert alert-info text-center">
                    <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                    <h4>No orders yet</h4>
                    <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                    <a href="#" class="btn btn-primary" onclick="showPage('products')">Browse Products</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('orders-container').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>Error loading orders. Please try again later.
            </div>
        `;
    }
    
    setTimeout(() => {
        const pageContent = mainContent.querySelector('.page-content');
        if (pageContent) {
            pageContent.classList.add('active');
        }
    }, 100);
}

function viewOrderDetails(orderId) {
    showToast(`Viewing order ${orderId} details - to be implemented`, 'info');
}

// About Page
function loadAboutPage() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="page-content fade-in">
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="text-center mb-5">
                            <h1 class="display-4 mb-4">About EverCart</h1>
                            <p class="lead text-muted">Your trusted online shopping destination for quality products at great prices.</p>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <i class="fas fa-award fa-3x text-primary mb-3"></i>
                                    <h4>Quality Products</h4>
                                    <p class="text-muted">We carefully curate our products to ensure the highest quality for our customers.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <i class="fas fa-dollar-sign fa-3x text-primary mb-3"></i>
                                    <h4>Best Prices</h4>
                                    <p class="text-muted">We offer competitive prices and regular discounts to give you the best value.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                    <h4>Fast Shipping</h4>
                                    <p class="text-muted">Quick and reliable shipping with tracking information for all orders.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                                    <h4>24/7 Support</h4>
                                    <p class="text-muted">Our dedicated customer support team is always ready to help you.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body p-4">
                                <h3 class="mb-4">Our Story</h3>
                                <p>Founded in 2025, EverCart started with a simple mission: to provide customers with access to high-quality products at affordable prices. We believe that everyone deserves access to great products, regardless of their budget.</p>
                                <p>Over the years, we have grown from a small online store to one of the most trusted e-commerce platforms. Our commitment to customer satisfaction, quality products, and excellent service has made us the preferred choice for thousands of customers worldwide.</p>
                                <p>We continue to innovate and improve our platform to provide the best shopping experience possible. Thank you for choosing EverCart for your shopping needs!</p>
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
    }, 100);
}

// Form handlers (Updated to use async functions)
async function handleLogin(event) {
    event.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Logging in...';
    
    try {
        await login(email, password);
    } catch (error) {
        console.error('Login error:', error);
        showToast('Login failed. Please try again.', 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

async function handleRegister(event) {
    event.preventDefault();
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password !== confirmPassword) {
        showToast('Passwords do not match', 'danger');
        return;
    }
    
    if (password.length < 6) {
        showToast('Password must be at least 6 characters', 'danger');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registering...';
    
    try {
        await register(name, email, password);
    } catch (error) {
        console.error('Registration error:', error);
        showToast('Registration failed. Please try again.', 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Utility functions for product detail page
function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const newValue = Math.max(1, currentValue + change);
    quantityInput.value = newValue;
}
