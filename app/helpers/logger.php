<?php

function logRequest($action, $data) {
    $logFile = BASE_PATH . '/logs/requests.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $message = "[{$timestamp}] {$action}: " . json_encode($data) . "\n";
    
    file_put_contents($logFile, $message, FILE_APPEND);
}

function logError($error, $context = []) {
    $logFile = BASE_PATH . '/logs/errors.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $message = "[{$timestamp}] ERROR: {$error}\n";
    if (!empty($context)) {
        $message .= "Context: " . json_encode($context) . "\n";
    }
    
    file_put_contents($logFile, $message, FILE_APPEND);
}
?>
