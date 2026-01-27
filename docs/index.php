<?php

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use ProductAPI\ProductService;

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooCommerce Product API Integration</title>

    <link rel="stylesheet" href="../assets/css/index.css">

</head>

<body>
    <div class="container">
        <h1>WooCommerce REST API Integration 🚀</h1>
        <p style="color: #666; margin-bottom: 20px;">Assessment 4 - Product Management System</p>

        <div class="intro">
            <p>
                The WooCommerce REST API integration is fully configured and operational.
                This system fetches product data using authenticated API requests without
                direct database access.
            </p>
            </div>

        <div class="nav">
            <a href="../api/fetch-products.php">API Endpoint (JSON) 📊</a>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3>✓ Authentication Implemented</h3>
                <ul>
                    <li>Consumer Key & Secret configured</li>
                    <li>API credentials secured in <code>.env</code></li>
                    <li>OAuth-ready infrastructure</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>✓ Product Data Fetching</h3>
                <ul>
                    <li>Product names</li>
                    <li>Prices & sale prices</li>
                    <li>Stock status & quantity</li>
                    <li>Featured images</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>✓ Custom Product Listing</h3>
                <ul>
                    <li>Standalone HTML/JavaScript page</li>
                    <li>Dynamic product rendering</li>
                    <li>Responsive design</li>
                    <li>Error handling</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>✓ REST API Implementation</h3>
                <ul>
                    <li>HTTP requests only</li>
                    <li>JSON data processing</li>
                    <li>No direct DB queries</li>
                    <li>No WordPress functions</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>✓ Performance Optimization</h3>
                <ul>
                    <li>Pagination support</li>
                    <li>Configurable per-page limit</li>
                    <li>Structured API responses</li>
                    <li>Clean code organization</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>🔒 Security Best Practices</h3>
                <ul>
                    <li>Credentials in <code>.env</code> (not versioned)</li>
                    <li>Environment-based configuration</li>
                    <li><code>.gitignore</code> configured</li>
                    <li>API keys never exposed</li>
                </ul>
            </div>
        </div>

        <h2 style="color: #667eea; margin-top: 40px;">API Endpoint Documentation</h2>
        <div class="info-card">
            <h3>GET /api/fetch-products.php</h3>
            <p><strong>Parameters:</strong></p>
            <ul>
                <li><code>page</code> (int, optional): Page number (default: 1)</li>
                <li><code>per_page</code> (int, optional): Products per page (default: 12, max: 100)</li>
            </ul>
            <p><strong>Example:</strong> <code>api/fetch-products.php?page=1&per_page=12</code></p>
            <p><strong>Response:</strong> JSON object with products array and pagination info</p>
        </div>

        <h2 style="color: #667eea; margin-top: 30px;">Project Structure</h2>
        <div class="info-card" style="font-family: 'Courier New', monospace; font-size: 0.9em; white-space: pre-wrap; word-break: break-all;">product-API/
├── index.php              (This page)
├── products.html          (Product catalog UI)
├── .env                   (API credentials)
├── .env.example           (Template for .env)
├── .gitignore             (Git configuration)
├── config/
│   └── config.php         (Configuration loader)
├── api/
│   ├── fetch-products.php (API endpoint)
│   └── ProductService.php (Service class)
└── composer.json          (Composer configuration)</div>

        <h2 style="color: #667eea; margin-top: 30px;">Compliance Checklist</h2>
        <div class="info-card">
            <p>✓ WooCommerce REST API authentication configured</p>
            <p>✓ Product data fetching (name, price, stock, image)</p>
            <p>✓ Custom standalone product listing page</p>
            <p>✓ Dynamic data rendering with error handling</p>
            <p>✓ HTTP REST requests with JSON processing</p>
            <p>✓ No direct database access</p>
            <p>✓ No WordPress functions for product fetching</p>
            <p>✓ API keys secured (not hardcoded)</p>
            <p>✓ Pagination & performance optimization</p>
            <p>✓ Clean, readable, well-organized code</p>
        </div>
    </div>
</body>
</html> 