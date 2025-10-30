<?php
require_once 'ProductDao.php';
require_once 'UserDao.php';
require_once 'OrderDao.php';
require_once 'CategoryDao.php';
require_once 'CartDao.php';

// --- ProductDao TESTS ---
$productDao = new ProductDao();
echo "\n--- ProductDao ---\n";
$newProduct = [
    'name' => 'Test Product',
    'description' => 'A test product',
    'price' => 99.99,
    'category_id' => 1,
    'image_url' => 'test.jpg'
];
$insProd = $productDao->add($newProduct);
var_dump($insProd);
var_dump($productDao->getAll());
var_dump($productDao->getById($insProd['product_id']));
var_dump($productDao->getByCategory($insProd['category_id']));
$insProd['name'] = 'Updated Product'; $updProd = $productDao->update($insProd, $insProd['product_id']); var_dump($updProd);
$productDao->delete($insProd['product_id']);

// --- UserDao TESTS ---
$userDao = new UserDao();
echo "--- UserDao ---\n";
$newUser = [
    'name' => 'Test User',
    'email' => 'user@example.com',
    'password' => 'secret',
    'address' => '101 Test Ave'
];
$insUser = $userDao->add($newUser);
var_dump($insUser);
var_dump($userDao->getAll());
var_dump($userDao->getById($insUser['user_id']));
var_dump($userDao->getByEmail('user@example.com'));
$insUser['name'] = 'Updated User'; $updUser = $userDao->update($insUser, $insUser['user_id']); var_dump($updUser);
$userDao->delete($insUser['user_id']);

// --- CategoryDao TESTS ---
$catDao = new CategoryDao();
echo "--- CategoryDao ---\n";
$newCat = ['name' => 'Gadgets'];
$insCat = $catDao->add($newCat);
var_dump($insCat);
var_dump($catDao->getAll());
var_dump($catDao->getById($insCat['category_id']));
var_dump($catDao->getByName('Gadgets'));
$insCat['name'] = 'Updated Gadgets'; $updCat = $catDao->update($insCat, $insCat['category_id']); var_dump($updCat);
$catDao->delete($insCat['category_id']);

// --- OrderDao TESTS ---
$orderDao = new OrderDao();
echo "--- OrderDao ---\n";
// Dummy insert: Needs existing user_id from actual users table!
$ordUserId = 1;
$newOrder = [
    'user_id' => $ordUserId,
    'total_price' => 129.99,
    'status' => 'Pending',
];
$insOrder = $orderDao->add($newOrder);
var_dump($insOrder);
var_dump($orderDao->getAll());
var_dump($orderDao->getById($insOrder['order_id']));
var_dump($orderDao->getByUserId($ordUserId));
$insOrder['status'] = 'Complete'; $updOrder = $orderDao->update($insOrder, $insOrder['order_id']); var_dump($updOrder);
$orderDao->delete($insOrder['order_id']);

// --- CartDao TESTS ---
$cartDao = new CartDao();
echo "--- CartDao ---\n";
// Dummy insert: Needs existing user_id, product_id from actual tables!
$cartUserId = 1; $cartProdId = 1;
$newCart = [
    'user_id' => $cartUserId,
    'product_id' => $cartProdId,
    'quantity' => 2
];
$insCart = $cartDao->add($newCart);
var_dump($insCart);
var_dump($cartDao->getAll());
var_dump($cartDao->getById($insCart['cart_id']));
var_dump($cartDao->getByUserId($cartUserId));
$insCart['quantity'] = 5; $updCart = $cartDao->update($insCart, $insCart['cart_id']); var_dump($updCart);
$cartDao->clearCart($cartUserId);
