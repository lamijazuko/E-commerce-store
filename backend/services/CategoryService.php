<?php
require_once __DIR__ . '/../rest/dao/CategoryDao.php';

class CategoryService {
    private $categoryDao;
    
    public function __construct() {
        $this->categoryDao = new CategoryDao();
    }
    
    public function getAllCategories() {
        return $this->categoryDao->getAll();
    }
    
    public function getCategoryById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid category ID");
        }
        $category = $this->categoryDao->getById($id);
        if (!$category) {
            throw new Exception("Category not found");
        }
        return $category;
    }
    
    public function getCategoryByName($name) {
        if (empty($name)) {
            throw new Exception("Category name is required");
        }
        return $this->categoryDao->getByName($name);
    }
    
    public function createCategory($categoryData) {
        // Validation
        if (empty($categoryData['name'])) {
            throw new Exception("Category name is required");
        }
        if (strlen($categoryData['name']) > 100) {
            throw new Exception("Category name must be 100 characters or less");
        }
        
        // Check if name already exists
        $existingCategory = $this->categoryDao->getByName($categoryData['name']);
        if ($existingCategory) {
            throw new Exception("Category name already exists");
        }
        
        // Remove fields that don't exist in database schema
        unset($categoryData['status']);
        unset($categoryData['parent_id']);
        unset($categoryData['image_url']);
        unset($categoryData['description']);
        
        return $this->categoryDao->add($categoryData);
    }
    
    public function updateCategory($id, $categoryData) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid category ID");
        }
        
        // Check if category exists
        $existingCategory = $this->categoryDao->getById($id);
        if (!$existingCategory) {
            throw new Exception("Category not found");
        }
        
        // Validation
        if (isset($categoryData['name']) && empty($categoryData['name'])) {
            throw new Exception("Category name cannot be empty");
        }
        if (isset($categoryData['name']) && strlen($categoryData['name']) > 100) {
            throw new Exception("Category name must be 100 characters or less");
        }
        
        // Check if new name conflicts with existing category
        if (isset($categoryData['name'])) {
            $nameCategory = $this->categoryDao->getByName($categoryData['name']);
            if ($nameCategory && $nameCategory['category_id'] != $id) {
                throw new Exception("Category name already exists");
            }
        }
        
        // Remove fields that don't exist in database schema
        unset($categoryData['status']);
        unset($categoryData['parent_id']);
        unset($categoryData['image_url']);
        unset($categoryData['description']);
        
        // Don't update ID
        unset($categoryData['category_id']);
        
        return $this->categoryDao->update($categoryData, $id);
    }
    
    public function deleteCategory($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid category ID");
        }
        
        $category = $this->categoryDao->getById($id);
        if (!$category) {
            throw new Exception("Category not found");
        }
        
        // Check if category has products (would need ProductDao for this)
        // For now, we'll allow deletion and let database constraints handle it
        
        return $this->categoryDao->delete($id);
    }
}

