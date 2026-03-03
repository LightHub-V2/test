<?php
/**
 * Search/Filter Handler
 * Skincare Recommendation Website
 * 
 * This file handles AJAX requests for filtering products
 */

// Include database configuration
require_once 'config/database.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Start session if needed
session_start();

// Initialize response
$response = [
    'success' => true,
    'products' => [],
    'count' => 0,
    'message' => ''
];

try {
    // Get filter parameters from GET request
    $filters = [
        'min_price' => isset($_GET['min_price']) ? floatval($_GET['min_price']) : null,
        'max_price' => isset($_GET['max_price']) ? floatval($_GET['max_price']) : null,
        'age' => isset($_GET['age']) ? intval($_GET['age']) : null,
        'skin_type' => isset($_GET['skin_type']) ? $_GET['skin_type'] : null,
        'skin_concern' => isset($_GET['skin_concern']) ? $_GET['skin_concern'] : null
    ];
    
    // Remove null values
    $filters = array_filter($filters, function($value) {
        return $value !== null && $value !== '';
    });
    
    // Get products with filters
    $products = getProducts($filters);
    
    // Format products for JSON response
    $formattedProducts = array_map(function($product) {
        return [
            'id' => intval($product['id']),
            'name' => htmlspecialchars($product['name']),
            'brand' => htmlspecialchars($product['brand']),
            'price' => floatval($product['price']),
            'target_age_min' => intval($product['target_age_min']),
            'target_age_max' => intval($product['target_age_max']),
            'skin_type' => htmlspecialchars($product['skin_type']),
            'skin_concern' => htmlspecialchars($product['skin_concern']),
            'description' => htmlspecialchars($product['description'] ?? ''),
            'ingredients' => htmlspecialchars($product['ingredients'] ?? ''),
            'image_url' => htmlspecialchars($product['image_url']),
            'rating' => floatval($product['rating']),
            'review_count' => intval($product['review_count'])
        ];
    }, $products);
    
    // Set response data
    $response['products'] = $formattedProducts;
    $response['count'] = count($formattedProducts);
    
    if (count($formattedProducts) === 0) {
        $response['message'] = 'Tidak ada produk yang cocok dengan filter Anda';
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
    error_log("Search Error: " . $e->getMessage());
}

// Return JSON response
echo json_encode($response);
exit;

