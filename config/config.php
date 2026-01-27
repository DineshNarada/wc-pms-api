<?php

// Load environment variables from .env file
$dotenv_path = __DIR__ . '/../.env';
if (file_exists($dotenv_path)) {
    $lines = file($dotenv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$WC_API_URL = $_ENV['WC_API_URL'] ?? '';
$WC_CONSUMER_KEY = $_ENV['WC_CONSUMER_KEY'] ?? '';
$WC_CONSUMER_SECRET = $_ENV['WC_CONSUMER_SECRET'] ?? '';

