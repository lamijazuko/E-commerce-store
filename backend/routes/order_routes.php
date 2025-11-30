<?php
// Order Routes

// GET /api/orders - Get all orders (Admin only)
Flight::route('GET /api/orders', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $service = new OrderService();
        $orders = $service->getAllOrders();
        Flight::json(['data' => $orders], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/orders/:id - Get order by ID (Admin or owner)
Flight::route('GET /api/orders/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $currentUser = AuthMiddleware::authenticate();
        
        if (!$currentUser) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        $service = new OrderService();
        $order = $service->getOrderById($id);
        
        // Admin can view any order, users can only view their own
        if ($currentUser['role'] !== 'admin' && $order['user_id'] != $currentUser['user_id']) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        Flight::json(['data' => $order], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// GET /api/orders/user/:userId - Get orders by user ID (Owner or Admin)
Flight::route('GET /api/orders/user/@userId', function($userId) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $currentUser = AuthMiddleware::authenticate();
        
        if (!$currentUser) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Admin can view any user's orders, users can only view their own
        if ($currentUser['role'] !== 'admin' && $currentUser['user_id'] != $userId) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service = new OrderService();
        $orders = $service->getOrdersByUserId($userId);
        Flight::json(['data' => $orders], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/orders - Create new order (Authenticated users only)
Flight::route('POST /api/orders', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        Flight::set('user', $user);
        
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        $data = ValidationMiddleware::validateJson(
            ['total_price'],
            ['total_price' => ['numeric' => true, 'positive' => true]]
        );
        
        if ($data === null) {
            return;
        }
        
        // Set user_id from authenticated user
        $data['user_id'] = $user['user_id'];
        
        $service = new OrderService();
        $order = $service->createOrder($data);
        Flight::json(['data' => $order, 'message' => 'Order created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/orders/:id - Update order (Admin only - users cannot modify orders after creation)
Flight::route('PUT /api/orders/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new OrderService();
        $order = $service->updateOrder($id, $data);
        Flight::json(['data' => $order, 'message' => 'Order updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/orders/:id/status - Update order status (Admin only)
Flight::route('PUT /api/orders/@id/status', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $data = json_decode(Flight::request()->getBody(), true);
        $status = $data['status'] ?? '';
        $service = new OrderService();
        $order = $service->updateOrderStatus($id, $status);
        Flight::json(['data' => $order, 'message' => 'Order status updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/orders/:id - Delete order (Admin only)
Flight::route('DELETE /api/orders/:id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $service = new OrderService();
        $service->deleteOrder($id);
        Flight::json(['message' => 'Order deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

