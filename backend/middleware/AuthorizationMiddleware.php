<?php
/**
 * Authorization Middleware
 * Handles role-based access control (RBAC)
 */

class AuthorizationMiddleware {
    /**
     * Require admin role
     */
    public static function requireAdmin() {
        // Require AuthMiddleware to be loaded
        require_once __DIR__ . '/AuthMiddleware.php';
        
        // First check authentication
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json([
                'error' => true,
                'message' => 'Authentication required'
            ], 401);
            Flight::stop();
            return false;
        }
        
        // Check if user is admin
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            Flight::json([
                'error' => true,
                'message' => 'Admin access required'
            ], 403);
            Flight::stop();
            return false;
        }
        
        Flight::set('user', $user);
        return true;
    }
    
    /**
     * Require specific role(s)
     * @param string|array $roles - Single role or array of allowed roles
     */
    public static function requireRole($roles) {
        // Require AuthMiddleware to be loaded
        require_once __DIR__ . '/AuthMiddleware.php';
        
        // First check authentication
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json([
                'error' => true,
                'message' => 'Authentication required'
            ], 401);
            Flight::stop();
            return false;
        }
        
        // Normalize roles to array
        $allowedRoles = is_array($roles) ? $roles : [$roles];
        $userRole = $user['role'] ?? 'user';
        
        // Check if user has required role
        if (!in_array($userRole, $allowedRoles)) {
            Flight::json([
                'error' => true,
                'message' => 'Insufficient permissions'
            ], 403);
            Flight::stop();
            return false;
        }
        
        Flight::set('user', $user);
        return true;
    }
    
    /**
     * Require authentication but allow any authenticated user
     */
    public static function requireAnyAuth() {
        return AuthMiddleware::requireAuth();
    }
    
    /**
     * Check if current user owns resource
     * @param int $resourceUserId - User ID of resource owner
     */
    public static function requireOwnership($resourceUserId) {
        // Require AuthMiddleware to be loaded
        require_once __DIR__ . '/AuthMiddleware.php';
        
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json([
                'error' => true,
                'message' => 'Authentication required'
            ], 401);
            Flight::stop();
            return false;
        }
        
        // Admin can access anything
        if (isset($user['role']) && $user['role'] === 'admin') {
            Flight::set('user', $user);
            return true;
        }
        
        // Check ownership
        if ($user['user_id'] != $resourceUserId) {
            Flight::json([
                'error' => true,
                'message' => 'Access denied - resource belongs to another user'
            ], 403);
            Flight::stop();
            return false;
        }
        
        Flight::set('user', $user);
        return true;
    }
}

