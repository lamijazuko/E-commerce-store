<?php
/**
 * Verify Admin User in Database
 * Quick script to check if admin user exists and show details
 */

require_once 'backend/rest/config.php';

$dsn = "mysql:host=" . Config::DB_HOST() . ";port=" . Config::DB_PORT() . ";dbname=" . Config::DB_NAME();
$pdo = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD());

echo "=== Admin User Verification ===\n\n";

// Check admin user
$admin = $pdo->query("SELECT user_id, name, email, role FROM Users WHERE email = 'admin@evercart.com'")->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "✅ Admin user EXISTS in database:\n";
    echo "   User ID: {$admin['user_id']}\n";
    echo "   Name: {$admin['name']}\n";
    echo "   Email: {$admin['email']}\n";
    echo "   Role: {$admin['role']}\n\n";
} else {
    echo "❌ Admin user NOT FOUND\n\n";
    echo "To create admin user, run:\n";
    echo "   http://localhost:8080/lamija_e_commerce_store/backend/rest/create_admin_user.php\n";
    echo "OR use the script directly:\n";
    echo "   php backend/rest/create_admin_user.php\n\n";
}

// Check test user
$testUser = $pdo->query("SELECT user_id, name, email, role FROM Users WHERE email = 'user@evercart.com'")->fetch(PDO::FETCH_ASSOC);

if ($testUser) {
    echo "✅ Test user EXISTS:\n";
    echo "   User ID: {$testUser['user_id']}\n";
    echo "   Email: {$testUser['email']}\n";
    echo "   Role: {$testUser['role']}\n\n";
}

// Show all users with roles
echo "=== All Users in Database ===\n";
$users = $pdo->query("SELECT user_id, name, email, role FROM Users ORDER BY user_id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    echo sprintf("ID: %-3d | %-20s | %-30s | Role: %s\n", 
        $user['user_id'], 
        $user['name'], 
        $user['email'], 
        $user['role'] ?? 'user'
    );
}

echo "\n=== Admin Credentials ===\n";
echo "Email: admin@evercart.com\n";
echo "Password: admin123\n";
echo "Role: admin\n";

