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

// POST /api/products - Create new product
Flight::route('POST /api/products', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new ProductService();
        $product = $service->createProduct($data);
        Flight::json(['data' => $product, 'message' => 'Product created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/products/:id - Update product
Flight::route('PUT /api/products/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new ProductService();
        $product = $service->updateProduct($id, $data);
        Flight::json(['data' => $product, 'message' => 'Product updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/products/:id - Delete product
Flight::route('DELETE /api/products/@id', function($id) {
    try {
        $service = new ProductService();
        $service->deleteProduct($id);
        Flight::json(['message' => 'Product deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/products/:id/stock - Update product stock
Flight::route('PUT /api/products/@id/stock', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $quantity = $data['quantity'] ?? 0;
        $service = new ProductService();
        $product = $service->updateStock($id, $quantity);
        Flight::json(['data' => $product, 'message' => 'Stock updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

