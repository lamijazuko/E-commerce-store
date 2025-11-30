<?php
// Product Routes

// GET /api/products - Get all products
Flight::route('GET /api/products', function() {
    try {
        $service = new ProductService();
        $products = $service->getAllProducts();
        Flight::json(['data' => $products], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/products/:id - Get product by ID
Flight::route('GET /api/products/@id', function($id) {
    try {
        $service = new ProductService();
        $product = $service->getProductById($id);
        Flight::json(['data' => $product], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// GET /api/products/category/:categoryId - Get products by category
Flight::route('GET /api/products/category/@categoryId', function($categoryId) {
    try {
        $service = new ProductService();
        $products = $service->getProductsByCategory($categoryId);
        Flight::json(['data' => $products], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/products - Create new product (Admin only)
Flight::route('POST /api/products', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        $data = ValidationMiddleware::validateJson(
            ['name', 'price'],
            [
                'price' => ['numeric' => true, 'positive' => true]
            ]
        );
        
        if ($data === null) {
            return;
        }
        
        $service = new ProductService();
        $product = $service->createProduct($data);
        Flight::json(['data' => $product, 'message' => 'Product created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/products/:id - Update product (Admin only)
Flight::route('PUT /api/products/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new ProductService();
        $product = $service->updateProduct($id, $data);
        Flight::json(['data' => $product, 'message' => 'Product updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/products/:id - Delete product (Admin only)
Flight::route('DELETE /api/products/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $service = new ProductService();
        $service->deleteProduct($id);
        Flight::json(['message' => 'Product deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/products/:id/stock - Update product stock (Admin only)
Flight::route('PUT /api/products/@id/stock', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        if (!AuthorizationMiddleware::requireAdmin()) {
            return; // Stop execution if not authorized
        }
        
        $data = json_decode(Flight::request()->getBody(), true);
        $quantity = $data['quantity'] ?? 0;
        $service = new ProductService();
        $product = $service->updateStock($id, $quantity);
        Flight::json(['data' => $product, 'message' => 'Stock updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

