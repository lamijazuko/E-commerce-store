<?php
require_once __DIR__ . '/../rest/dao/ProductDao.php';

class ProductService {
    private $productDao;
    
    public function __construct() {
        $this->productDao = new ProductDao();
    }
    
    public function getAllProducts() {
        return $this->productDao->getAll();
    }
    
    public function getProductById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid product ID");
        }
        $product = $this->productDao->getById($id);
        if (!$product) {
            throw new Exception("Product not found");
        }
        return $product;
    }
    
    public function getProductsByCategory($categoryId) {
        if (empty($categoryId) || !is_numeric($categoryId)) {
            throw new Exception("Invalid category ID");
        }
        return $this->productDao->getByCategory($categoryId);
    }
    
    public function createProduct($productData) {
        // Validation
        if (empty($productData['name'])) {
            throw new Exception("Product name is required");
        }
        if (strlen($productData['name']) > 100) {
            throw new Exception("Product name must be 100 characters or less");
        }
        if (!isset($productData['price']) || !is_numeric($productData['price'])) {
            throw new Exception("Valid price is required");
        }
        if ($productData['price'] < 0) {
            throw new Exception("Price cannot be negative");
        }
        // Remove fields that don't exist in database schema
        unset($productData['stock_quantity']);
        unset($productData['status']);
        
        return $this->productDao->add($productData);
    }
    
    public function updateProduct($id, $productData) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid product ID");
        }
        
        // Check if product exists
        $existingProduct = $this->productDao->getById($id);
        if (!$existingProduct) {
            throw new Exception("Product not found");
        }
        
        // Validation
        if (isset($productData['name']) && empty($productData['name'])) {
            throw new Exception("Product name cannot be empty");
        }
        if (isset($productData['name']) && strlen($productData['name']) > 100) {
            throw new Exception("Product name must be 100 characters or less");
        }
        if (isset($productData['price']) && (!is_numeric($productData['price']) || $productData['price'] < 0)) {
            throw new Exception("Price must be a non-negative number");
        }
        // Remove fields that don't exist in database schema
        unset($productData['stock_quantity']);
        unset($productData['status']);
        
        // Don't update ID
        unset($productData['product_id']);
        
        return $this->productDao->update($productData, $id);
    }
    
    public function deleteProduct($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid product ID");
        }
        
        $product = $this->productDao->getById($id);
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        return $this->productDao->delete($id);
    }
    
    public function updateStock($id, $quantity) {
        // Stock management not available - stock_quantity column doesn't exist in database
        throw new Exception("Stock management is not available. The database schema does not include stock_quantity column.");
    }
}

