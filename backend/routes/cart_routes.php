<?php
// Cart Routes

// GET /api/cart/user/:userId - Get cart by user ID
Flight::route('GET /api/cart/user/@userId', function($userId) {
    try {
        $service = new CartService();
        $cart = $service->getCartByUserId($userId);
        Flight::json(['data' => $cart], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/cart/user/:userId/total - Get cart total
Flight::route('GET /api/cart/user/@userId/total', function($userId) {
    try {
        $service = new CartService();
        $total = $service->getCartTotal($userId);
        Flight::json(['data' => ['total' => $total]], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/cart - Add item to cart
Flight::route('POST /api/cart', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new CartService();
        $cartItem = $service->addToCart($data);
        Flight::json(['data' => $cartItem, 'message' => 'Item added to cart successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/cart/:id - Update cart item quantity
Flight::route('PUT /api/cart/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $quantity = $data['quantity'] ?? 0;
        $service = new CartService();
        $cartItem = $service->updateCartItem($id, $quantity);
        Flight::json(['data' => $cartItem, 'message' => 'Cart item updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/cart/:id - Remove item from cart
Flight::route('DELETE /api/cart/@id', function($id) {
    try {
        $service = new CartService();
        $service->removeFromCart($id);
        Flight::json(['message' => 'Item removed from cart successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/cart/user/:userId - Clear user's cart
Flight::route('DELETE /api/cart/user/@userId', function($userId) {
    try {
        $service = new CartService();
        $service->clearCart($userId);
        Flight::json(['message' => 'Cart cleared successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

