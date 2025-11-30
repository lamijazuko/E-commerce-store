<?php
/**
 * Authentication Middleware
 * Handles user authentication and session management
 */

class AuthMiddleware {
    /**
     * Check if user is authenticated
     * Returns user data if authenticated, null otherwise
     */
    public static function authenticate() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
            return [
                'user_id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'name' => $_SESSION['user_name'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user'
            ];
        }
        
        // Check for API token in Authorization header
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            return self::validateToken($token);
        }
        
        return null;
    }
    
    /**
     * Validate API token (simple implementation)
     * In production, use JWT or similar secure tokens
     */
    private static function validateToken($token) {
        // For now, decode token (could be JWT in future)
        // Simple base64 encoded JSON
        $decoded = json_decode(base64_decode($token), true);
        if ($decoded && isset($decoded['user_id']) && isset($decoded['email'])) {
            return $decoded;
        }
        return null;
    }
    
    /**
     * Require authentication - middleware function
     * Use with Flight::before() filter
     */
    public static function requireAuth() {
        $user = self::authenticate();
        if (!$user) {
            Flight::json([
                'error' => true,
                'message' => 'Authentication required'
            ], 401);
            Flight::stop();
        }
        // Store user in Flight for route access
        Flight::set('user', $user);
        return true;
    }
    
    /**
     * Login user and create session
     */
    public static function login($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'] ?? '';
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        
        return true;
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_unset();
        session_destroy();
        
        return true;
    }
    
    /**
     * Generate API token for user
     */
    public static function generateToken($user) {
        $payload = [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'name' => $user['name'] ?? '',
            'role' => $user['role'] ?? 'user',
            'exp' => time() + (24 * 60 * 60) // 24 hours
        ];
        return base64_encode(json_encode($payload));
    }
}

