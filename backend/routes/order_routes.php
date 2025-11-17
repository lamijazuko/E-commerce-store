<?php
// Order Routes

// GET /api/orders - Get all orders
Flight::route('GET /api/orders', function() {
    try {
        $service = new OrderService();
        $orders = $service->getAllOrders();
        Flight::json(['data' => $orders], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/orders/:id - Get order by ID
Flight::route('GET /api/orders/@id', function($id) {
    try {
        $service = new OrderService();
        $order = $service->getOrderById($id);
        Flight::json(['data' => $order], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// GET /api/orders/user/:userId - Get orders by user ID
Flight::route('GET /api/orders/user/@userId', function($userId) {
    try {
        $service = new OrderService();
        $orders = $service->getOrdersByUserId($userId);
        Flight::json(['data' => $orders], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/orders - Create new order
Flight::route('POST /api/orders', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new OrderService();
        $order = $service->createOrder($data);
        Flight::json(['data' => $order, 'message' => 'Order created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/orders/:id - Update order
Flight::route('PUT /api/orders/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new OrderService();
        $order = $service->updateOrder($id, $data);
        Flight::json(['data' => $order, 'message' => 'Order updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/orders/:id/status - Update order status
Flight::route('PUT /api/orders/@id/status', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $status = $data['status'] ?? '';
        $service = new OrderService();
        $order = $service->updateOrderStatus($id, $status);
        Flight::json(['data' => $order, 'message' => 'Order status updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/orders/:id - Delete order
Flight::route('DELETE /api/orders/@id', function($id) {
    try {
        $service = new OrderService();
        $service->deleteOrder($id);
        Flight::json(['message' => 'Order deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

