<?php
require_once __DIR__ . '/../rest/dao/ReviewDao.php';
require_once __DIR__ . '/../rest/dao/ProductDao.php';
require_once __DIR__ . '/../rest/dao/UserDao.php';

class ReviewService {
    private $reviewDao;
    private $productDao;
    private $userDao;
    
    public function __construct() {
        $this->reviewDao = new ReviewDao();
        $this->productDao = new ProductDao();
        $this->userDao = new UserDao();
    }
    
    public function getAllReviews() {
        return $this->reviewDao->getAll();
    }
    
    public function getReviewById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid review ID");
        }
        $review = $this->reviewDao->getById($id);
        if (!$review) {
            throw new Exception("Review not found");
        }
        return $review;
    }
    
    public function getReviewsByProductId($productId) {
        if (empty($productId) || !is_numeric($productId)) {
            throw new Exception("Invalid product ID");
        }
        return $this->reviewDao->getByProductId($productId);
    }
    
    public function getReviewsByUserId($userId) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new Exception("Invalid user ID");
        }
        return $this->reviewDao->getByUserId($userId);
    }
    
    public function getAverageRating($productId) {
        if (empty($productId) || !is_numeric($productId)) {
            throw new Exception("Invalid product ID");
        }
        return $this->reviewDao->getAverageRating($productId);
    }
    
    public function createReview($reviewData) {
        // Validation
        if (empty($reviewData['product_id']) || !is_numeric($reviewData['product_id'])) {
            throw new Exception("Valid product ID is required");
        }
        if (empty($reviewData['user_id']) || !is_numeric($reviewData['user_id'])) {
            throw new Exception("Valid user ID is required");
        }
        if (!isset($reviewData['rating']) || !is_numeric($reviewData['rating'])) {
            throw new Exception("Rating is required");
        }
        if ($reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
            throw new Exception("Rating must be between 1 and 5");
        }
        
        // Check if product exists
        $product = $this->productDao->getById($reviewData['product_id']);
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        // Check if user exists
        $user = $this->userDao->getById($reviewData['user_id']);
        if (!$user) {
            throw new Exception("User not found");
        }
        
        // Check if user already reviewed this product
        $existingReview = $this->reviewDao->getByProductAndUser($reviewData['product_id'], $reviewData['user_id']);
        if ($existingReview) {
            throw new Exception("User has already reviewed this product");
        }
        
        // Set defaults
        if (!isset($reviewData['created_at'])) {
            $reviewData['created_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->reviewDao->add($reviewData);
    }
    
    public function updateReview($id, $reviewData) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid review ID");
        }
        
        // Check if review exists
        $existingReview = $this->reviewDao->getById($id);
        if (!$existingReview) {
            throw new Exception("Review not found");
        }
        
        // Validation
        if (isset($reviewData['rating']) && (!is_numeric($reviewData['rating']) || $reviewData['rating'] < 1 || $reviewData['rating'] > 5)) {
            throw new Exception("Rating must be between 1 and 5");
        }
        
        // Don't update ID, product_id, or user_id
        unset($reviewData['review_id']);
        unset($reviewData['product_id']);
        unset($reviewData['user_id']);
        
        return $this->reviewDao->update($reviewData, $id);
    }
    
    public function deleteReview($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid review ID");
        }
        
        $review = $this->reviewDao->getById($id);
        if (!$review) {
            throw new Exception("Review not found");
        }
        
        return $this->reviewDao->delete($id);
    }
}

