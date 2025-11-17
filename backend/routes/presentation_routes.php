<?php
// Presentation Layer Routes - Dynamic content rendering with FlightPHP

// Home page
Flight::route('GET /', function() {
    Flight::render('home', [
        'title' => 'EverCart - Welcome',
        'message' => 'Welcome to EverCart E-Commerce Store'
    ]);
});

// Products page
Flight::route('GET /products', function() {
    try {
        $service = new ProductService();
        $products = $service->getAllProducts();
        Flight::render('products', [
            'title' => 'Products',
            'products' => $products
        ]);
    } catch (Exception $e) {
        Flight::render('error', [
            'title' => 'Error',
            'message' => $e->getMessage()
        ]);
    }
});

// Product detail page
Flight::route('GET /products/@id', function($id) {
    try {
        $productService = new ProductService();
        $reviewService = new ReviewService();
        
        $product = $productService->getProductById($id);
        $reviews = $reviewService->getReviewsByProductId($id);
        $rating = $reviewService->getAverageRating($id);
        
        Flight::render('product_detail', [
            'title' => $product['name'],
            'product' => $product,
            'reviews' => $reviews,
            'rating' => $rating
        ]);
    } catch (Exception $e) {
        Flight::render('error', [
            'title' => 'Error',
            'message' => $e->getMessage()
        ]);
    }
});

// Categories page
Flight::route('GET /categories', function() {
    try {
        $service = new CategoryService();
        $categories = $service->getAllCategories();
        Flight::render('categories', [
            'title' => 'Categories',
            'categories' => $categories
        ]);
    } catch (Exception $e) {
        Flight::render('error', [
            'title' => 'Error',
            'message' => $e->getMessage()
        ]);
    }
});

// Category products page
Flight::route('GET /categories/@id/products', function($id) {
    try {
        $categoryService = new CategoryService();
        $productService = new ProductService();
        
        $category = $categoryService->getCategoryById($id);
        $products = $productService->getProductsByCategory($id);
        
        Flight::render('category_products', [
            'title' => $category['name'] . ' Products',
            'category' => $category,
            'products' => $products
        ]);
    } catch (Exception $e) {
        Flight::render('error', [
            'title' => 'Error',
            'message' => $e->getMessage()
        ]);
    }
});

// Set views path
Flight::set('flight.views.path', __DIR__ . '/../views');

