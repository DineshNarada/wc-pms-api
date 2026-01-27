<?php

namespace ProductAPI;

use Automattic\WooCommerce\Client;
use Exception;

/**
 * WooCommerce Product Service
 * Handles API communication with WooCommerce REST API
 */
class ProductService
{
    private $client;
    private $perPage = 12; // Default products per page

    /**
     * Initialize the WooCommerce API client
     */
    public function __construct($apiUrl, $consumerKey, $consumerSecret, $perPage = 12)
    {
        if (empty($apiUrl) || empty($consumerKey) || empty($consumerSecret)) {
            throw new Exception('WooCommerce API credentials are not configured properly.');
        }

        $this->client = new Client(
            $apiUrl,
            $consumerKey,
            $consumerSecret,
            ['version' => 'wc/v3']
        );

        $this->perPage = $perPage;
    }

    /**
     * Fetch products from WooCommerce API with pagination
     * 
     * @param int $page Current page number
     * @param int $perPage Products per page
     * @return array Array with 'products', 'total', 'totalPages', 'currentPage'
     */
    public function getProducts($page = 1, $perPage = null)
    {
        try {
            if ($perPage === null) {
                $perPage = $this->perPage;
            }

            $products = $this->client->get('products', [
                'page' => $page,
                'per_page' => $perPage,
            ]);

            // Products count as total (WooCommerce will return fewer if we're on last page)
            $totalProducts = count($products);
            
            // If we got fewer products than requested, we're on the last page
            // Calculate approximate total based on pagination
            $isLastPage = $totalProducts < $perPage;

            return [
                'success' => true,
                'products' => $this->formatProducts($products),
                'total' => $totalProducts,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => $isLastPage ? $page : $page + 1,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch products: ' . $e->getMessage(),
                'products' => [],
            ];
        }
    }

    /**
     * Format product data for display
     * 
     * @param mixed $products Raw products from API (array or object)
     * @return array Formatted products
     */
    private function formatProducts($products)
    {
        // Convert object to array if needed
        if (is_object($products)) {
            $products = json_decode(json_encode($products), true);
        }

        if (!is_array($products)) {
            return [];
        }

        return array_map(function ($product) {
            // Handle both array and object products
            if (is_object($product)) {
                $product = json_decode(json_encode($product), true);
            }

            return [
                'id' => $product['id'] ?? null,
                'name' => $product['name'] ?? 'No name',
                'price' => $product['price'] ?? '0',
                'regular_price' => $product['regular_price'] ?? '0',
                'sale_price' => $product['sale_price'] ?? null,
                'stock_status' => $product['stock_status'] ?? 'unknown',
                'stock_quantity' => $product['stock_quantity'] ?? 0,
                'featured_image' => $this->getFeaturedImage($product),
                'url' => $product['permalink'] ?? '#',
            ];
        }, $products);
    }

    /**
     * Extract featured image URL from product data
     * 
     * @param mixed $product Product data (array or object)
     * @return string Image URL or placeholder
     */
    private function getFeaturedImage($product)
    {
        // Convert object to array if needed
        if (is_object($product)) {
            $product = json_decode(json_encode($product), true);
        }

        if (!empty($product['images']) && is_array($product['images'])) {
            return $product['images'][0]['src'] ?? $this->getPlaceholderImage();
        }
        return $this->getPlaceholderImage();
    }

    /**
     * Get placeholder image URL
     */
    private function getPlaceholderImage()
    {
        return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E';
    }
}
