<?php

// Set strict error handling
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Capture any output/errors
ob_start();

// Load configuration
$config_path = __DIR__ . '/../config/config.php';
if (!file_exists($config_path)) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Config not found']);
    exit;
}
require $config_path;

// Load autoloader
$autoload_path = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload_path)) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Autoloader not found']);
    exit;
}
require $autoload_path;

use Automattic\WooCommerce\Client;

try {
    if (empty($WC_API_URL) || empty($WC_CONSUMER_KEY) || empty($WC_CONSUMER_SECRET)) {
        throw new Exception('API credentials not configured.');
    }

    // Get product ID from query parameter
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    
    if ($product_id === 0) {
        throw new Exception('Product ID is required');
    }

    // Create WooCommerce client directly
    $client = new Client(
        $WC_API_URL,
        $WC_CONSUMER_KEY,
        $WC_CONSUMER_SECRET,
        ['version' => 'wc/v3']
    );

    // Fetch single product
    $product = $client->get('products/' . $product_id);

    if (!$product) {
        throw new Exception('Product not found');
    }

    // Return only inventory data
    $stockQuantity = $product->stock_quantity ?? 0;
    $stockStatus = $product->stock_status ?? 'outofstock';
    
    $stockClass = $stockStatus === 'instock' ? 'stock-instock' : 'stock-outofstock';
    $stockText = $stockStatus === 'instock' 
        ? sprintf('✓ In Stock (%d %s)', $stockQuantity, $stockQuantity === 1 ? 'item' : 'items')
        : '✗ Out of Stock';

    ob_end_clean();
    echo json_encode([
        'success' => true,
        'product_id' => $product_id,
        'stock_quantity' => $stockQuantity,
        'stock_status' => $stockStatus,
        'stock_html' => sprintf('<span class="product-stock %s">%s</span>', $stockClass, $stockText)
    ]);

} catch (Exception $e) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
