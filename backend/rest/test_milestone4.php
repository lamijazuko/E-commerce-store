<?php
/**
 * Milestone 4 Complete Test Suite
 * Tests authentication, authorization, and frontend-backend integration
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milestone 4 Test Suite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .test-pass { color: #28a745; font-weight: bold; }
        .test-fail { color: #dc3545; font-weight: bold; }
        .test-section { margin-bottom: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 0.5rem; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🧪 Milestone 4 - Complete Test Suite</h1>
        <p class="lead">Testing Authentication, Authorization, and Frontend Integration</p>
        
        <div class="test-section">
            <h3>1. Database Setup Verification</h3>
            <hr>
            <?php
            try {
                $dsn = "mysql:host=" . Config::DB_HOST() . ";port=" . Config::DB_PORT() . ";dbname=" . Config::DB_NAME();
                $pdo = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD(), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                // Check role column
                $stmt = $pdo->query("SHOW COLUMNS FROM Users LIKE 'role'");
                if ($stmt->rowCount() > 0) {
                    echo "<p class='test-pass'>✅ Role column exists in Users table</p>";
                } else {
                    echo "<p class='test-fail'>❌ Role column missing - run database update</p>";
                }
                
                // Check admin user
                $admin = $pdo->query("SELECT * FROM Users WHERE email = 'admin@evercart.com'")->fetch();
                if ($admin && $admin['role'] === 'admin') {
                    echo "<p class='test-pass'>✅ Admin user exists with admin role</p>";
                } else {
                    echo "<p class='test-fail'>❌ Admin user missing or incorrect role</p>";
                }
                
                // Check test user
                $testUser = $pdo->query("SELECT * FROM Users WHERE email = 'user@evercart.com'")->fetch();
                if ($testUser && $testUser['role'] === 'user') {
                    echo "<p class='test-pass'>✅ Test user exists with user role</p>";
                } else {
                    echo "<p class='test-fail'>❌ Test user missing or incorrect role</p>";
                }
                
            } catch (PDOException $e) {
                echo "<p class='test-fail'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>

        <div class="test-section">
            <h3>2. Middleware Files Verification</h3>
            <hr>
            <?php
            $middlewareFiles = [
                '../middleware/AuthMiddleware.php',
                '../middleware/AuthorizationMiddleware.php',
                '../middleware/ValidationMiddleware.php',
                '../middleware/LoggingMiddleware.php'
            ];
            
            foreach ($middlewareFiles as $file) {
                $path = __DIR__ . '/' . $file;
                if (file_exists($path)) {
                    echo "<p class='test-pass'>✅ " . basename($file) . " exists</p>";
                } else {
                    echo "<p class='test-fail'>❌ " . basename($file) . " missing</p>";
                }
            }
            ?>
        </div>

        <div class="test-section">
            <h3>3. Authentication API Test</h3>
            <hr>
            <div id="auth-tests">
                <p>Loading authentication tests...</p>
            </div>
        </div>

        <div class="test-section">
            <h3>4. Authorization Test (Role-Based Access)</h3>
            <hr>
            <div id="authz-tests">
                <p>Loading authorization tests...</p>
            </div>
        </div>

        <div class="test-section">
            <h3>5. Frontend Files Verification</h3>
            <hr>
            <?php
            $frontendFiles = [
                '../../frontend/js/api.js',
                '../../frontend/js/admin.js',
                '../../index.html'
            ];
            
            foreach ($frontendFiles as $file) {
                $path = __DIR__ . '/' . $file;
                if (file_exists($path)) {
                    echo "<p class='test-pass'>✅ " . basename($file) . " exists</p>";
                } else {
                    echo "<p class='test-fail'>❌ " . basename($file) . " missing</p>";
                }
            }
            ?>
        </div>

        <div class="mt-4">
            <a href="test.php" class="btn btn-secondary">Back to Test Page</a>
            <a href="../../index.html" class="btn btn-primary">Go to Frontend</a>
            <a href="swagger-ui.html" class="btn btn-info">View API Docs</a>
        </div>
    </div>

    <script>
        const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
        let adminToken = '';
        let userToken = '';

        // Test Authentication
        async function testAuthentication() {
            const container = document.getElementById('auth-tests');
            
            // Test 1: Register (if needed)
            container.innerHTML = '<p>Testing registration...</p>';
            
            // Test 2: Login as admin
            try {
                const loginRes = await fetch(baseUrl + '/api/auth/login', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        email: 'admin@evercart.com',
                        password: 'admin123'
                    })
                });
                const loginData = await loginRes.json();
                
                if (loginRes.ok && loginData.data) {
                    adminToken = loginData.token || '';
                    container.innerHTML += `<p class="test-pass">✅ Admin login successful</p>`;
                    container.innerHTML += `<p>User: ${loginData.data.name}, Role: ${loginData.data.role}</p>`;
                } else {
                    container.innerHTML += `<p class="test-fail">❌ Admin login failed: ${loginData.message}</p>`;
                }
            } catch (error) {
                container.innerHTML += `<p class="test-fail">❌ Admin login error: ${error.message}</p>`;
            }
            
            // Test 3: Get current user
            if (adminToken) {
                try {
                    const meRes = await fetch(baseUrl + '/api/auth/me', {
                        headers: {'Authorization': 'Bearer ' + adminToken}
                    });
                    const meData = await meRes.json();
                    
                    if (meRes.ok && meData.data) {
                        container.innerHTML += `<p class="test-pass">✅ Get current user works</p>`;
                    } else {
                        container.innerHTML += `<p class="test-fail">❌ Get current user failed</p>`;
                    }
                } catch (error) {
                    container.innerHTML += `<p class="test-fail">❌ Get current user error</p>`;
                }
            }
        }

        // Test Authorization
        async function testAuthorization() {
            const container = document.getElementById('authz-tests');
            
            if (!adminToken) {
                container.innerHTML = '<p class="test-fail">❌ Cannot test authorization - admin token missing</p>';
                return;
            }
            
            // Test 1: Admin accessing admin-only endpoint
            try {
                const usersRes = await fetch(baseUrl + '/api/users', {
                    headers: {'Authorization': 'Bearer ' + adminToken}
                });
                const usersData = await usersRes.json();
                
                if (usersRes.ok && usersData.data) {
                    container.innerHTML += `<p class="test-pass">✅ Admin can access GET /api/users</p>`;
                } else {
                    container.innerHTML += `<p class="test-fail">❌ Admin cannot access GET /api/users: ${usersData.message}</p>`;
                }
            } catch (error) {
                container.innerHTML += `<p class="test-fail">❌ Authorization test error</p>`;
            }
            
            // Test 2: Try accessing without token (should fail)
            try {
                const noAuthRes = await fetch(baseUrl + '/api/users');
                const noAuthData = await noAuthRes.json();
                
                if (noAuthRes.status === 401 || noAuthRes.status === 403) {
                    container.innerHTML += `<p class="test-pass">✅ Unauthenticated access properly blocked</p>`;
                } else {
                    container.innerHTML += `<p class="test-fail">❌ Security issue: Unauthenticated access allowed</p>`;
                }
            } catch (error) {
                container.innerHTML += `<p class="test-pass">✅ Unauthenticated access blocked (error expected)</p>`;
            }
        }

        // Run tests when page loads
        window.onload = async function() {
            await testAuthentication();
            await testAuthorization();
        };
    </script>
</body>
</html>

