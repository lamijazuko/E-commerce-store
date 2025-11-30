<?php
/**
 * Request Validation Middleware
 * Handles input validation and sanitization
 */

class ValidationMiddleware {
    /**
     * Validate JSON request body
     */
    public static function validateJson($requiredFields = [], $rules = []) {
        $request = Flight::request();
        $data = json_decode($request->getBody(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Flight::json([
                'error' => true,
                'message' => 'Invalid JSON format'
            ], 400);
            Flight::stop();
            return null;
        }
        
        // Check required fields
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                Flight::json([
                    'error' => true,
                    'message' => "Field '{$field}' is required"
                ], 400);
                Flight::stop();
                return null;
            }
        }
        
        // Apply validation rules
        foreach ($rules as $field => $ruleSet) {
            if (!isset($data[$field])) {
                continue; // Skip if field not present
            }
            
            foreach ($ruleSet as $rule => $value) {
                switch ($rule) {
                    case 'email':
                        if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            Flight::json([
                                'error' => true,
                                'message' => "Field '{$field}' must be a valid email"
                            ], 400);
                            Flight::stop();
                            return null;
                        }
                        break;
                        
                    case 'min':
                        if (strlen($data[$field]) < $value) {
                            Flight::json([
                                'error' => true,
                                'message' => "Field '{$field}' must be at least {$value} characters"
                            ], 400);
                            Flight::stop();
                            return null;
                        }
                        break;
                        
                    case 'max':
                        if (strlen($data[$field]) > $value) {
                            Flight::json([
                                'error' => true,
                                'message' => "Field '{$field}' must not exceed {$value} characters"
                            ], 400);
                            Flight::stop();
                            return null;
                        }
                        break;
                        
                    case 'numeric':
                        if (!is_numeric($data[$field])) {
                            Flight::json([
                                'error' => true,
                                'message' => "Field '{$field}' must be numeric"
                            ], 400);
                            Flight::stop();
                            return null;
                        }
                        break;
                        
                    case 'positive':
                        if ($data[$field] < 0) {
                            Flight::json([
                                'error' => true,
                                'message' => "Field '{$field}' must be positive"
                            ], 400);
                            Flight::stop();
                            return null;
                        }
                        break;
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Validate and sanitize integer ID parameter
     */
    public static function validateId($id) {
        if (empty($id) || !is_numeric($id) || $id < 1) {
            Flight::json([
                'error' => true,
                'message' => 'Invalid ID parameter'
            ], 400);
            Flight::stop();
            return null;
        }
        return (int)$id;
    }
}

