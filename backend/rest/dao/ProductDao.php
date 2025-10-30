<?php
require_once 'BaseDao.php';
class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct('products', 'product_id');
    }
    public function getByCategory($category_id) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
