<?php
/**
 * Security helper functions for Tastebite
 */

/**
 * Sanitize input for output in HTML (prevents XSS)
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize input for database or general use
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
    } else {
        $data = trim($data);
        $data = stripslashes($data);
    }
    return $data;
}

/**
 * Centralized error logging (Issue #11)
 * Logs messages to BASE_PATH . 'storage/logs/app.log'
 */
function log_error($message, $level = 'ERROR') {
    $log_dir = BASE_PATH . 'storage/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0775, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    @error_log($log_entry, 3, $log_dir . '/app.log');
}
