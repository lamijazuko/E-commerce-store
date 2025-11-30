<?php
/**
 * Comprehensive API Protection Test Suite
 * Tests authentication, authorization, and route protection
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Protection Test Suite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .test-pass { color: #28a745; font-weight: bold; }
        .test-fail { color: #dc3545; font-weight: bold; }
        .test-warning { color: #ffc107; font-weight: bold; }
        .test-section { margin-bottom: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 0.5rem; }
        .test-result { margin: 0.5rem 0; padding: 0.5rem; background: #f8f9fa; border-radius: 0.25rem; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🧪 API Protection Test Suite</h1>
        <p class="lead">Comprehensive testing of authentication, authorization, and route protection</p>
        
        <div class="test-section">
            <h3>1. Database & Setup Verification</h3>
            <hr>
            <div id="setup-tests"></div>
        </div>

        <div class="test-section">
            <h3>2. Authentication Tests</h3>
            <hr>
            <div id="auth-tests"></div>
        </div>

        <div class="test-section">
            <h3>3. Protected Route Tests</h3>
            <hr>
            <div id="protection-tests"></div>
        </div>

        <div class="test-section">
            <h3>4. Role-Based Access Tests</h3>
            <hr>
            <div id="role-tests"></div>
        </div>

        <div class="test-section">
            <h3>5. Fixed Routes Verification</h3>
            <hr>
            <div id="fixed-routes-tests"></div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary" onclick="runAllTests()">🔄 Run All Tests</button>
            <a href="test.php" class="btn btn-secondary">Back to Test Page</a>
            <a href="../../index.html" class="btn btn-info">Go to Frontend</a>
        </div>
    </div>

    <script>
        const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
        let adminToken = '';
        let userToken = '';
        let adminUser = null;
        let regularUser = null;

        // Test Helper Functions
        async function makeRequest(endpoint, method = 'GET', data = null, token = null) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            const config = {
                method: method,
                headers: headers
            };
            
            if (data) {
                config.body = JSON.stringify(data);
            }
            
            try {
                const response = await fetch(`${baseUrl}${endpoint}`, config);
                const responseData = await response.json();
                return {
                    ok: response.ok,
                    status: response.status,
                    data: responseData
                };
            } catch (error) {
                return {
                    ok: false,
                    status: 0,
                    error: error.message,
                    data: null
                };
            }
        }

        function addTestResult(containerId, testName, passed, message) {
            const container = document.getElementById(containerId);
            const result = document.createElement('div');
            result.className = 'test-result';
            result.innerHTML = `<span class="${passed ? 'test-pass' : 'test-fail'}">${passed ? '✅' : '❌'}</span> <strong>${testName}:</strong> ${message}`;
            container.appendChild(result);
        }

        // 1. Setup Tests
        async function testSetup() {
            const container = document.getElementById('setup-tests');
            container.innerHTML = '<p>Testing setup...</p>';
            
            try {
                const response = await makeRequest('/api/products', 'GET', null, null);
                if (response.ok) {
                    addTestResult('setup-tests', 'API Server', true, 'Server is running and accessible');
                } else {
                    addTestResult('setup-tests', 'API Server', false, 'Server not accessible');
                }
            } catch (error) {
                addTestResult('setup-tests', 'API Server', false, 'Error: ' + error.message);
            }
        }

        // 2. Authentication Tests
        async function testAuthentication() {
            const container = document.getElementById('auth-tests');
            container.innerHTML = '<p>Testing authentication...</p>';
            
            // Test admin login
            const adminLogin = await makeRequest('/api/auth/login', 'POST', {
                email: 'admin@evercart.com',
                password: 'admin123'
            });
            
            if (adminLogin.ok && adminLogin.data.token) {
                adminToken = adminLogin.data.token;
                adminUser = adminLogin.data.data;
                addTestResult('auth-tests', 'Admin Login', true, 'Admin logged in successfully');
            } else {
                addTestResult('auth-tests', 'Admin Login', false, adminLogin.data?.message || 'Failed to login');
            }
            
            // Test regular user login
            const userLogin = await makeRequest('/api/auth/login', 'POST', {
                email: 'user@evercart.com',
                password: 'user123'
            });
            
            if (userLogin.ok && userLogin.data.token) {
                userToken = userLogin.data.token;
                regularUser = userLogin.data.data;
                addTestResult('auth-tests', 'User Login', true, 'Regular user logged in successfully');
            } else {
                addTestResult('auth-tests', 'User Login', false, userLogin.data?.message || 'Failed to login (user may not exist)');
            }
            
            // Test get current user
            if (adminToken) {
                const me = await makeRequest('/api/auth/me', 'GET', null, adminToken);
                if (me.ok) {
                    addTestResult('auth-tests', 'Get Current User', true, 'Token authentication working');
                } else {
                    addTestResult('auth-tests', 'Get Current User', false, 'Token authentication failed');
                }
            }
        }

        // 3. Protected Route Tests
        async function testProtectedRoutes() {
            const container = document.getElementById('protection-tests');
            container.innerHTML = '<p>Testing route protection...</p>';
            
            // Test 1: Access admin endpoint without token (should fail)
            const noAuthUsers = await makeRequest('/api/users', 'GET', null, null);
            if (noAuthUsers.status === 401 || noAuthUsers.status === 403) {
                addTestResult('protection-tests', 'Unauthenticated Access Blocked', true, 'Unauthenticated requests properly rejected');
            } else {
                addTestResult('protection-tests', 'Unauthenticated Access Blocked', false, `Security issue: Got status ${noAuthUsers.status} instead of 401/403`);
            }
            
            // Test 2: Access admin endpoint with user token (should fail)
            if (userToken) {
                const userAccessUsers = await makeRequest('/api/users', 'GET', null, userToken);
                if (userAccessUsers.status === 403) {
                    addTestResult('protection-tests', 'Non-Admin Access Blocked', true, 'Regular users cannot access admin endpoints');
                } else {
                    addTestResult('protection-tests', 'Non-Admin Access Blocked', false, `Security issue: Got status ${userAccessUsers.status} instead of 403`);
                }
            }
            
            // Test 3: Access admin endpoint with admin token (should succeed)
            if (adminToken) {
                const adminAccessUsers = await makeRequest('/api/users', 'GET', null, adminToken);
                if (adminAccessUsers.ok) {
                    addTestResult('protection-tests', 'Admin Access Allowed', true, 'Admins can access admin endpoints');
                } else {
                    addTestResult('protection-tests', 'Admin Access Allowed', false, adminAccessUsers.data?.message || 'Admin access denied');
                }
            }
        }

        // 4. Role-Based Access Tests
        async function testRoleBasedAccess() {
            const container = document.getElementById('role-tests');
            container.innerHTML = '<p>Testing role-based access...</p>';
            
            if (!adminToken || !userToken) {
                addTestResult('role-tests', 'Role Tests', false, 'Need both admin and user tokens - login failed');
                return;
            }
            
            // Test: User accessing own profile (should work)
            if (regularUser) {
                const ownProfile = await makeRequest(`/api/users/${regularUser.user_id}`, 'GET', null, userToken);
                if (ownProfile.ok) {
                    addTestResult('role-tests', 'User Own Profile Access', true, 'Users can access their own profile');
                } else {
                    addTestResult('role-tests', 'User Own Profile Access', false, 'Users cannot access own profile');
                }
            }
            
            // Test: User accessing another user's profile (should fail)
            if (adminUser && regularUser) {
                const otherProfile = await makeRequest(`/api/users/${adminUser.user_id}`, 'GET', null, userToken);
                if (otherProfile.status === 403) {
                    addTestResult('role-tests', 'User Other Profile Blocked', true, 'Users cannot access other users\' profiles');
                } else {
                    addTestResult('role-tests', 'User Other Profile Blocked', false, 'Security issue: Users can access other profiles');
                }
            }
            
            // Test: Admin accessing any profile (should work)
            if (regularUser && adminToken) {
                const adminProfileAccess = await makeRequest(`/api/users/${regularUser.user_id}`, 'GET', null, adminToken);
                if (adminProfileAccess.ok) {
                    addTestResult('role-tests', 'Admin Any Profile Access', true, 'Admins can access any user profile');
                } else {
                    addTestResult('role-tests', 'Admin Any Profile Access', false, 'Admin cannot access user profiles');
                }
            }
        }

        // 5. Fixed Routes Verification
        async function testFixedRoutes() {
            const container = document.getElementById('fixed-routes-tests');
            container.innerHTML = '<p>Testing fixed routes...</p>';
            
            // Test 1: Product stock update (should require admin)
            if (!adminToken) {
                addTestResult('fixed-routes-tests', 'Product Stock Protection', false, 'Cannot test - admin token missing');
            } else {
                // Try without token
                const noAuthStock = await makeRequest('/api/products/1/stock', 'PUT', { quantity: 10 }, null);
                if (noAuthStock.status === 401 || noAuthStock.status === 403) {
                    addTestResult('fixed-routes-tests', 'Product Stock Protection', true, 'Product stock route protected (requires admin)');
                } else {
                    addTestResult('fixed-routes-tests', 'Product Stock Protection', false, `Security issue: Got status ${noAuthStock.status}`);
                }
            }
            
            // Test 2: Review update (should require owner or admin)
            // First, we need a review - try to get one
            const reviews = await makeRequest('/api/reviews', 'GET');
            if (reviews.ok && reviews.data.data && reviews.data.data.length > 0) {
                const reviewId = reviews.data.data[0].review_id;
                
                // Try without token
                const noAuthReviewUpdate = await makeRequest(`/api/reviews/${reviewId}`, 'PUT', { rating: 5 }, null);
                if (noAuthReviewUpdate.status === 401) {
                    addTestResult('fixed-routes-tests', 'Review Update Protection', true, 'Review update route protected (requires authentication)');
                } else {
                    addTestResult('fixed-routes-tests', 'Review Update Protection', false, `Security issue: Got status ${noAuthReviewUpdate.status}`);
                }
                
                // Try delete without token
                const noAuthReviewDelete = await makeRequest(`/api/reviews/${reviewId}`, 'DELETE', null, null);
                if (noAuthReviewDelete.status === 401) {
                    addTestResult('fixed-routes-tests', 'Review Delete Protection', true, 'Review delete route protected (requires authentication)');
                } else {
                    addTestResult('fixed-routes-tests', 'Review Delete Protection', false, `Security issue: Got status ${noAuthReviewDelete.status}`);
                }
            } else {
                addTestResult('fixed-routes-tests', 'Review Routes', false, 'Cannot test - no reviews found in database');
            }
        }

        // Run all tests
        async function runAllTests() {
            // Clear previous results
            document.getElementById('setup-tests').innerHTML = '';
            document.getElementById('auth-tests').innerHTML = '';
            document.getElementById('protection-tests').innerHTML = '';
            document.getElementById('role-tests').innerHTML = '';
            document.getElementById('fixed-routes-tests').innerHTML = '';
            
            // Run tests sequentially
            await testSetup();
            await testAuthentication();
            await testProtectedRoutes();
            await testRoleBasedAccess();
            await testFixedRoutes();
            
            // Show completion message
            setTimeout(() => {
                alert('✅ All tests completed! Check results above.');
            }, 500);
        }

        // Auto-run tests on page load
        window.onload = function() {
            runAllTests();
        };
    </script>
</body>
</html>

