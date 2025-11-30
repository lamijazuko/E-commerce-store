<?php
/**
 * Basic Logging Middleware (Optional)
 * Logs API requests and responses
 */

class LoggingMiddleware {
    private static $logFile = __DIR__ . '/../../logs/api.log';
    
    /**
     * Log request before processing
     */
    public static function logRequest() {
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $request = Flight::request();
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request->method,
            'url' => $request->url,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        // Add user info if authenticated
        $user = AuthMiddleware::authenticate();
        if ($user) {
            $logData['user_id'] = $user['user_id'];
            $logData['user_email'] = $user['email'];
        }
        
        $logLine = json_encode($logData) . "\n";
        file_put_contents(self::$logFile, $logLine, FILE_APPEND);
    }
    
    /**
     * Log error
     */
    public static function logError($message, $context = []) {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'ERROR',
            'message' => $message,
            'context' => $context
        ];
        
        $logLine = json_encode($logData) . "\n";
        file_put_contents(self::$logFile, $logLine, FILE_APPEND);
    }
}

