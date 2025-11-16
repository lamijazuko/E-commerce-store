<?php
require_once __DIR__ . '/../rest/dao/CartDao.php';
require_once __DIR__ . '/../rest/dao/ProductDao.php';

class CartService {
    private $cartDao;
    private $productDao;
    
    public function __construct() {
        $this->cartDao = new CartDao();
        $this->productDao = new ProductDao();
    }
    
    public function getCartByUserId($userId) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new Exception("Invalid user ID");
        }
        return $this->cartDao->getByUserId($userId);
    }
    
    public function addToCart($cartData) {
        // Validation
        if (empty($cartData['user_id']) || !is_numeric($cartData['user_id'])) {
            throw new Exception("Valid user ID is required");
        }
        if (empty($cartData['product_id']) || !is_numeric($cartData['product_id'])) {
            throw new Exception("Valid product ID is required");
        }
        if (!isset($cartData['quantity']) || !is_numeric($cartData['quantity']) || $cartData['quantity'] <= 0) {
            throw new Exception("Valid quantity is required");
        }
        
        // Check if product exists
        $product = $this->productDao->getById($cartData['product_id']);
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        // Note: Stock quantity check removed - stock_quantity column doesn't exist in database
        
        // Check if item already in cart
        $existingItems = $this->cartDao->getByUserId($cartData['user_id']);
        foreach ($existingItems as $item) {
            if ($item['product_id'] == $cartData['product_id']) {
                // Update quantity
                $newQuantity = $item['quantity'] + $cartData['quantity'];
                // Note: Stock quantity check removed - stock_quantity column doesn't exist in database
                return $this->cartDao->update(['quantity' => $newQuantity], $item['cart_id']);
            }
        }
        
        // Add new item
        return $this->cartDao->add($cartData);
    }
    
    public function updateCartItem($cartId, $quantity) {
        if (empty($cartId) || !is_numeric($cartId)) {
            throw new Exception("Invalid cart ID");
        }
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new Exception("Valid quantity is required");
        }
        
        $cartItem = $this->cartDao->getById($cartId);
        if (!$cartItem) {
            throw new Exception("Cart item not found");
        }
        
        // Check if product exists
        $product = $this->productDao->getById($cartItem['product_id']);
        if (!$product) {
            throw new Exception("Product not found");
        }
        // Note: Stock quantity check removed - stock_quantity column doesn't exist in database
        
        return $this->cartDao->update(['quantity' => $quantity], $cartId);
    }
    
    public function removeFromCart($cartId) {
        if (empty($cartId) || !is_numeric($cartId)) {
            throw new Exception("Invalid cart ID");
        }
        
        $cartItem = $this->cartDao->getById($cartId);
        if (!$cartItem) {
            throw new Exception("Cart item not found");
        }
        
        return $this->cartDao->delete($cartId);
    }
    
    public function clearCart($userId) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new Exception("Invalid user ID");
        }
        return $this->cartDao->clearCart($userId);
    }
    
    public function getCartTotal($userId) {
        $cartItems = $this->getCartByUserId($userId);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $product = $this->productDao->getById($item['product_id']);
            if ($product) {
                $total += $product['price'] * $item['quantity'];
            }
        }
        
        return $total;
    }
}

