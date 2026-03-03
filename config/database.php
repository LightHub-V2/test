<?php
/**
 * Database Configuration
 * Skincare Recommendation System
 * 
 * This file handles database connection for the skincare recommendation website
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'skincare_db');

/**
 * Get database connection
 * @return mysqli|null
 */
function getDBConnection() {
    $conn = null;
    
    try {
        // Create database connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Set charset to UTF-8
        $conn->set_charset("utf8mb4");
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
    } catch (Exception $e) {
        // Log error (in production, log to file)
        error_log("Database Error: " . $e->getMessage());
        return null;
    }
    
    return $conn;
}

/**
 * Close database connection
 * @param mysqli $conn
 */
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

/**
 * Execute query with prepared statement
 * @param string $query
 * @param array $params
 * @return mysqli_result|bool
 */
function executeQuery($query, $params = []) {
    $conn = getDBConnection();
    
    if (!$conn) {
        return false;
    }
    
    try {
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        // Bind parameters if any
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // All strings for simplicity
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stmt->close();
        closeDBConnection($conn);
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Query Error: " . $e->getMessage());
        closeDBConnection($conn);
        return false;
    }
}

/**
 * Fetch all rows from query result
 * @param mysqli_result $result
 * @return array
 */
function fetchAll($result) {
    $rows = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    
    return $rows;
}

/**
 * Fetch single row from query result
 * @param mysqli_result $result
 * @return array|null
 */
function fetchOne($result) {
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Get all products with optional filters
 * @param array $filters
 * @return array
 */
function getProducts($filters = []) {
    $query = "SELECT * FROM products WHERE 1=1";
    $params = [];
    
    // Filter by price range
    if (isset($filters['min_price']) && $filters['min_price'] !== '') {
        $query .= " AND price >= ?";
        $params[] = $filters['min_price'];
    }
    
    if (isset($filters['max_price']) && $filters['max_price'] !== '') {
        $query .= " AND price <= ?";
        $params[] = $filters['max_price'];
    }
    
    // Filter by age
    if (isset($filters['age']) && $filters['age'] !== '') {
        $query .= " AND target_age_min <= ? AND target_age_max >= ?";
        $params[] = $filters['age'];
        $params[] = $filters['age'];
    }
    
    // Filter by skin type
    if (isset($filters['skin_type']) && $filters['skin_type'] !== '' && $filters['skin_type'] !== 'All') {
        $query .= " AND (skin_type = ? OR skin_type = 'All')";
        $params[] = $filters['skin_type'];
    }
    
    // Filter by skin concern
    if (isset($filters['skin_concern']) && $filters['skin_concern'] !== '' && $filters['skin_concern'] !== 'All') {
        $query .= " AND (skin_concern = ? OR skin_concern = 'All')";
        $params[] = $filters['skin_concern'];
    }
    
    // Order by
    $query .= " ORDER BY rating DESC, review_count DESC";
    
    $result = executeQuery($query, $params);
    
    if ($result) {
        return fetchAll($result);
    }
    
    return [];
}

/**
 * Get single product by ID
 * @param int $id
 * @return array|null
 */
function getProductById($id) {
    $query = "SELECT * FROM products WHERE id = ?";
    $result = executeQuery($query, [$id]);
    
    if ($result) {
        return fetchOne($result);
    }
    
    return null;
}

/**
 * Get recommended products based on user preferences
 * @param string $skin_type
 * @param string $skin_concern
 * @param int|null $age
 * @return array
 */
function getRecommendedProducts($skin_type, $skin_concern, $age = null) {
    $query = "SELECT * FROM products WHERE 
              (skin_type = ? OR skin_type = 'All') 
              AND (skin_concern = ? OR skin_concern = 'All')";
    
    $params = [$skin_type, $skin_concern];
    
    // Filter by age if provided
    if ($age !== null) {
        $query .= " AND target_age_min <= ? AND target_age_max >= ?";
        $params[] = $age;
        $params[] = $age;
    }
    
    $query .= " ORDER BY rating DESC, review_count DESC LIMIT 6";
    
    $result = executeQuery($query, $params);
    
    if ($result) {
        return fetchAll($result);
    }
    
    return [];
}

/**
 * Save user quiz results
 * @param array $userData
 * @return int|bool
 */
function saveUserResults($userData) {
    $query = "INSERT INTO users (name, email, age, skin_type, skin_concern) VALUES (?, ?, ?, ?, ?)";
    
    $result = executeQuery($query, [
        $userData['name'],
        $userData['email'],
        $userData['age'],
        $userData['skin_type'],
        $userData['skin_concern']
    ]);
    
    if ($result) {
        return true;
    }
    
    return false;
}

/**
 * Get all skin types for dropdown
 * @return array
 */
function getSkinTypes() {
    return ['Oily', 'Dry', 'Combination', 'Sensitive', 'Normal', 'All'];
}

/**
 * Get all skin concerns for dropdown
 * @return array
 */
function getSkinConcerns() {
    return ['Acne', 'Dark Spot', 'Aging', 'Dehydrated', 'All'];
}

/**
 * Format price to Indonesian Rupiah
 * @param float $price
 * @return string
 */
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

/**
 * Generate star rating HTML
 * @param float $rating
 * @return string
 */
function generateStarRating($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    $html = '';
    
    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '★';
    }
    
    // Half star
    if ($halfStar) {
        $html .= '☆';
    }
    
    // Empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $html .= '☆';
    }
    
    return $html;
}

