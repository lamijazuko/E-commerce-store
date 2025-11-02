<?php
require_once 'ProductDao.php';
require_once 'UserDao.php';
require_once 'OrderDao.php';
require_once 'CategoryDao.php';
require_once 'CartDao.php';


$cartDao = new CartDao();
$orderDao = new OrderDao();
$productDao = new ProductDao();
$catDao = new CategoryDao();
$userDao = new UserDao();

$cartDao->execRaw("DELETE FROM cart");
$orderDao->execRaw("DELETE FROM orders");
$productDao->execRaw("DELETE FROM products");
$catDao->execRaw("DELETE FROM categories");
$userDao->execRaw("DELETE FROM users");


$newUser = [
    'name' => 'Test User',
    'email' => 'user@example.com',
    'password' => 'secret'
];
$insUser = $userDao->add($newUser);
$testUserId = $insUser['user_id'];

$newCat = ['name' => 'Test Category for Product'];
$insCat = $catDao->add($newCat);
$testCategoryId = $insCat['category_id'];

$newProduct = [
    'name' => 'Test Product',
    'description' => 'A test product',
    'price' => 99.99,
    'category_id' => $testCategoryId
];
$insProd = $productDao->add($newProduct);
$testProductId = $insProd['product_id'];

echo "\n--- ProductDao ---\n";
var_dump($insProd);
var_dump($productDao->getAll());
var_dump($productDao->getById($testProductId));
var_dump($productDao->getByCategory($testCategoryId));
$insProd['name'] = 'Updated Product'; $updProd = $productDao->update($insProd, $testProductId); var_dump($updProd);
//$productDao->delete($testProductId);

echo "--- UserDao ---\n";
var_dump($insUser);
var_dump($userDao->getAll());
var_dump($userDao->getById($testUserId));
var_dump($userDao->getByEmail('user@example.com'));
$insUser['name'] = 'Updated User'; $updUser = $userDao->update($insUser, $testUserId); var_dump($updUser);
// Do NOT delete user here!

echo "--- CategoryDao ---\n";
$catTestRow = ['name' => 'Gadgets'];
$insCat2 = $catDao->add($catTestRow);
var_dump($insCat2);
var_dump($catDao->getAll());
var_dump($catDao->getById($insCat2['category_id']));
var_dump($catDao->getByName('Gadgets'));
$insCat2['name'] = 'Updated Gadgets'; $updCat = $catDao->update($insCat2, $insCat2['category_id']); var_dump($updCat);
//$catDao->delete($insCat2['category_id']);

echo "--- OrderDao ---\n";
$newOrder = [
    'user_id' => $testUserId,
    'total' => 129.99,
    'status' => 'Pending',
];
$insOrder = $orderDao->add($newOrder);
var_dump($insOrder);
var_dump($orderDao->getAll());
var_dump($orderDao->getById($insOrder['order_id']));
var_dump($orderDao->getByUserId($testUserId));
$insOrder['status'] = 'Complete'; $updOrder = $orderDao->update($insOrder, $insOrder['order_id']); var_dump($updOrder);
//$orderDao->delete($insOrder['order_id']);

echo "--- CartDao ---\n";
$newCart = [
    'user_id' => $testUserId,
    'product_id' => $testProductId,
    'quantity' => 2
];
$insCart = $cartDao->add($newCart);
var_dump($insCart);
var_dump($cartDao->getAll());
var_dump($cartDao->getById($insCart['cart_id']));
var_dump($cartDao->getByUserId($testUserId));
$insCart['quantity'] = 5; $updCart = $cartDao->update($insCart, $insCart['cart_id']); var_dump($updCart);
$cartDao->clearCart($testUserId);

$productDao->delete($testProductId);
$catDao->delete($testCategoryId);
$userDao->delete($testUserId);
