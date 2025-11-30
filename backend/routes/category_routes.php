<?php
// Category Routes

// GET /api/categories - Get all categories
Flight::route('GET /api/categories', function() {
    try {
        $service = new CategoryService();
        $categories = $service->getAllCategories();
        Flight::json(['data' => $categories], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/categories/:id - Get category by ID
Flight::route('GET /api/categories/@id', function($id) {
    try {
        $service = new CategoryService();
        $category = $service->getCategoryById($id);
        Flight::json(['data' => $category], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// POST /api/categories - Create new category (Admin only)
Flight::route('POST /api/categories', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        $data = ValidationMiddleware::validateJson(['name'], []);
        
        if ($data === null) {
            return;
        }
        
        $service = new CategoryService();
        $category = $service->createCategory($data);
        Flight::json(['data' => $category, 'message' => 'Category created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/categories/:id - Update category (Admin only)
Flight::route('PUT /api/categories/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new CategoryService();
        $category = $service->updateCategory($id, $data);
        Flight::json(['data' => $category, 'message' => 'Category updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/categories/:id - Delete category (Admin only)
Flight::route('DELETE /api/categories/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $service = new CategoryService();
        $service->deleteCategory($id);
        Flight::json(['message' => 'Category deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

