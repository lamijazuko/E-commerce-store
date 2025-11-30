<?php
/**
 * Create Admin User Script
 * Creates an admin user for testing the admin panel
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../services/UserService.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User - Milestone 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">👤 Create Admin User</h1>
        <p class="lead">Create an admin user for testing the admin panel</p>
        
        <div class="card">
            <div class="card-body">
                <h3>Admin User Creation:</h3>
                <hr>
                
                <?php
                try {
                    // Default admin credentials
                    $adminName = 'Admin User';
                    $adminEmail = 'admin@evercart.com';
                    $adminPassword = 'admin123';
                    
                    // Check if admin already exists
                    $service = new UserService();
                    
                    try {
                        $existingAdmin = $service->getUserByEmail($adminEmail);
                        if ($existingAdmin) {
                            echo "<p class='warning'>⚠️ Admin user with email '{$adminEmail}' already exists!</p>";
                            echo "<p>User ID: {$existingAdmin['user_id']}</p>";
                            echo "<p>Name: {$existingAdmin['name']}</p>";
                            echo "<p>Role: " . ($existingAdmin['role'] ?? 'not set') . "</p>";
                            
                            // Check if role needs to be updated
                            if (!isset($existingAdmin['role']) || $existingAdmin['role'] !== 'admin') {
                                echo "<p class='info'>Updating user role to 'admin'...</p>";
                                
                                // Direct database update to set role
                                require_once __DIR__ . '/dao/UserDao.php';
                                $userDao = new UserDao();
                                $updated = $userDao->update(['role' => 'admin'], $existingAdmin['user_id']);
                                echo "<p class='success'>✅ Updated user role to 'admin'</p>";
                            } else {
                                echo "<p class='success'>✅ User already has admin role</p>";
                            }
                            
                            echo "<hr>";
                            echo "<p><strong>Admin Login Credentials:</strong></p>";
                            echo "<ul>";
                            echo "<li><strong>Email:</strong> {$adminEmail}</li>";
                            echo "<li><strong>Password:</strong> {$adminPassword}</li>";
                            echo "<li><strong>Role:</strong> admin</li>";
                            echo "</ul>";
                        } else {
                            throw new Exception("Admin not found");
                        }
                    } catch (Exception $e) {
                        // Admin doesn't exist, create it
                        echo "<p>Creating new admin user...</p>";
                        
                        $adminData = [
                            'name' => $adminName,
                            'email' => $adminEmail,
                            'password' => $adminPassword,
                            'address' => 'Admin Address'
                        ];
                        
                        // Create user first (will have default 'user' role)
                        $admin = $service->createUser($adminData);
                        echo "<p class='success'>✅ Created user: {$admin['name']} ({$admin['email']})</p>";
                        
                        // Update role to admin (bypassing service validation)
                        require_once __DIR__ . '/dao/UserDao.php';
                        $userDao = new UserDao();
                        $updated = $userDao->update(['role' => 'admin'], $admin['user_id']);
                        echo "<p class='success'>✅ Set user role to 'admin'</p>";
                        
                        echo "<hr>";
                        echo "<p class='success'><strong>✅ Admin user created successfully!</strong></p>";
                        echo "<p><strong>Admin Login Credentials:</strong></p>";
                        echo "<ul>";
                        echo "<li><strong>Email:</strong> {$adminEmail}</li>";
                        echo "<li><strong>Password:</strong> {$adminPassword}</li>";
                        echo "<li><strong>Role:</strong> admin</li>";
                        echo "</ul>";
                    }
                    
                    // Also create a test regular user
                    echo "<hr>";
                    echo "<h4>Test Regular User:</h4>";
                    
                    $testEmail = 'user@evercart.com';
                    try {
                        $existingUser = $service->getUserByEmail($testEmail);
                        if ($existingUser) {
                            echo "<p class='info'>ℹ️ Test user already exists: {$testEmail}</p>";
                        } else {
                            $testUserData = [
                                'name' => 'Test User',
                                'email' => $testEmail,
                                'password' => 'user123',
                                'address' => 'Test Address'
                            ];
                            $testUser = $service->createUser($testUserData);
                            echo "<p class='success'>✅ Created test user: {$testUser['email']}</p>";
                            echo "<p><strong>Test User Credentials:</strong></p>";
                            echo "<ul>";
                            echo "<li><strong>Email:</strong> {$testEmail}</li>";
                            echo "<li><strong>Password:</strong> user123</li>";
                            echo "<li><strong>Role:</strong> user</li>";
                            echo "</ul>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='warning'>⚠️ Could not create test user: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                    
                } catch (Exception $e) {
                    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "<p><strong>Make sure:</strong></p>";
                    echo "<ul>";
                    echo "<li>Database is running</li>";
                    echo "<li>Users table exists with role column</li>";
                    echo "<li>UserService is working correctly</li>";
                    echo "</ul>";
                }
                ?>
                
                <hr>
                <div class="mt-4">
                    <h5>Next Steps:</h5>
                    <ol>
                        <li>Login to the frontend with admin credentials</li>
                        <li>Access the Admin Panel from the account dropdown</li>
                        <li>Test user management, product management, etc.</li>
                    </ol>
                </div>
                
                <div class="mt-4">
                    <a href="test.php" class="btn btn-secondary">Back to Test Page</a>
                    <a href="../../index.html" class="btn btn-primary">Go to Frontend</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

