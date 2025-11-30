<?php
// Cart Routes

// GET /api/cart/user/:userId - Get cart by user ID (Authenticated - own cart only)
Flight::route('GET /api/cart/user/@userId', function($userId) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        
        // Users can only access their own cart, admins can access any
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        if ($user['role'] !== 'admin' && $user['user_id'] != $userId) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        Flight::set('user', $user);
        
        $service = new CartService();
        $cart = $service->getCartByUserId($userId);
        Flight::json(['data' => $cart], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/cart/user/:userId/total - Get cart total (Owner or Admin)
Flight::route('GET /api/cart/user/@userId/total', function($userId) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Admin can view any cart total, users can only view their own
        if ($user['role'] !== 'admin' && $user['user_id'] != $userId) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service = new CartService();
        $total = $service->getCartTotal($userId);
        Flight::json(['data' => ['total' => $total]], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/cart - Add item to cart (Authenticated users only)
Flight::route('POST /api/cart', function() {
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
            ['product_id', 'quantity'],
            [
                'product_id' => ['numeric' => true],
                'quantity' => ['numeric' => true, 'positive' => true]
            ]
        );
        
        if ($data === null) {
            return;
        }
        
        // Set user_id from authenticated user
        $data['user_id'] = $user['user_id'];
        
        $service = new CartService();
        $cartItem = $service->addToCart($data);
        Flight::json(['data' => $cartItem, 'message' => 'Item added to cart successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/cart/:id - Update cart item quantity (Owner or Admin)
Flight::route('PUT /api/cart/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Check ownership
        require_once __DIR__ . '/../rest/dao/CartDao.php';
        $cartDao = new CartDao();
        $cartItem = $cartDao->getById($id);
        
        if (!$cartItem) {
            Flight::json(['error' => true, 'message' => 'Cart item not found'], 404);
            return;
        }
        
        // Admin can update any cart, users can only update their own
        if ($user['role'] !== 'admin' && $cartItem['user_id'] != $user['user_id']) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $data = json_decode(Flight::request()->getBody(), true);
        $quantity = $data['quantity'] ?? 0;
        $service = new CartService();
        $cartItem = $service->updateCartItem($id, $quantity);
        Flight::json(['data' => $cartItem, 'message' => 'Cart item updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/cart/:id - Remove item from cart (Owner or Admin)
Flight::route('DELETE /api/cart/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Check ownership
        require_once __DIR__ . '/../rest/dao/CartDao.php';
        $cartDao = new CartDao();
        $cartItem = $cartDao->getById($id);
        
        if (!$cartItem) {
            Flight::json(['error' => true, 'message' => 'Cart item not found'], 404);
            return;
        }
        
        // Admin can delete any cart item, users can only delete their own
        if ($user['role'] !== 'admin' && $cartItem['user_id'] != $user['user_id']) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service = new CartService();
        $service->removeFromCart($id);
        Flight::json(['message' => 'Item removed from cart successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/cart/user/:userId - Clear user's cart (Owner or Admin)
Flight::route('DELETE /api/cart/user/:userId', function($userId) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Admin can clear any cart, users can only clear their own
        if ($user['role'] !== 'admin' && $user['user_id'] != $userId) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service = new CartService();
        $service->clearCart($userId);
        Flight::json(['message' => 'Cart cleared successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

