<?php
require_once __DIR__ . '/../rest/dao/OrderDao.php';
require_once __DIR__ . '/../rest/dao/ProductDao.php';
require_once __DIR__ . '/../rest/dao/CartDao.php';

class OrderService {
    private $orderDao;
    private $productDao;
    private $cartDao;
    
    public function __construct() {
        $this->orderDao = new OrderDao();
        $this->productDao = new ProductDao();
        $this->cartDao = new CartDao();
    }
    
    public function getAllOrders() {
        return $this->orderDao->getAll();
    }
    
    public function getOrderById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid order ID");
        }
        $order = $this->orderDao->getById($id);
        if (!$order) {
            throw new Exception("Order not found");
        }
        return $order;
    }
    
    public function getOrdersByUserId($userId) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new Exception("Invalid user ID");
        }
        return $this->orderDao->getByUserId($userId);
    }
    
    public function createOrder($orderData) {
        // Validation
        if (empty($orderData['user_id']) || !is_numeric($orderData['user_id'])) {
            throw new Exception("Valid user ID is required");
        }
        if (!isset($orderData['total_price']) || !is_numeric($orderData['total_price'])) {
            throw new Exception("Valid total price is required");
        }
        if ($orderData['total_price'] < 0) {
            throw new Exception("Total price cannot be negative");
        }
        
        // Set defaults
        if (!isset($orderData['status'])) {
            $orderData['status'] = 'Pending';
        }
        if (!isset($orderData['order_date'])) {
            $orderData['order_date'] = date('Y-m-d H:i:s');
        }
        
        // Validate status
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($orderData['status'], $validStatuses)) {
            throw new Exception("Invalid order status");
        }
        
        return $this->orderDao->add($orderData);
    }
    
    public function updateOrder($id, $orderData) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid order ID");
        }
        
        // Check if order exists
        $existingOrder = $this->orderDao->getById($id);
        if (!$existingOrder) {
            throw new Exception("Order not found");
        }
        
        // Validation
        if (isset($orderData['total_price']) && (!is_numeric($orderData['total_price']) || $orderData['total_price'] < 0)) {
            throw new Exception("Total price must be a non-negative number");
        }
        if (isset($orderData['status'])) {
            $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
            if (!in_array($orderData['status'], $validStatuses)) {
                throw new Exception("Invalid order status");
            }
        }
        
        // Don't update ID
        unset($orderData['order_id']);
        
        return $this->orderDao->update($orderData, $id);
    }
    
    public function deleteOrder($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid order ID");
        }
        
        $order = $this->orderDao->getById($id);
        if (!$order) {
            throw new Exception("Order not found");
        }
        
        // Prevent deletion of delivered orders
        if ($order['status'] === 'Delivered') {
            throw new Exception("Cannot delete delivered orders");
        }
        
        return $this->orderDao->delete($id);
    }
    
    public function updateOrderStatus($id, $status) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid order ID");
        }
        
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid order status");
        }
        
        return $this->updateOrder($id, ['status' => $status]);
    }
}

