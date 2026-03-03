-- =====================================================
-- DATABASE SKINCARE RECOMMENDATION SYSTEM
-- For Skripsi Project
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS skincare_db;
USE skincare_db;

-- =====================================================
-- TABLE: users
-- =====================================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    age INT,
    skin_type ENUM('Oily', 'Dry', 'Combination', 'Sensitive', 'Normal') DEFAULT NULL,
    skin_concern ENUM('Acne', 'Dark Spot', 'Aging', 'Dehydrated', 'None') DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE: products
-- =====================================================
DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    target_age_min INT NOT NULL,
    target_age_max INT NOT NULL,
    skin_type ENUM('Oily', 'Dry', 'Combination', 'Sensitive', 'Normal', 'All') NOT NULL,
    skin_concern ENUM('Acne', 'Dark Spot', 'Aging', 'Dehydrated', 'All') NOT NULL,
    description TEXT,
    ingredients TEXT,
    image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/300x200?text=Skincare+Product',
    rating DECIMAL(3,2) DEFAULT 4.0,
    review_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DUMMY DATA: 15 Skincare Products
-- =====================================================

INSERT INTO products (name, brand, price, target_age_min, target_age_max, skin_type, skin_concern, description, ingredients, rating, review_count) VALUES
('CeraVe Hydrating Cleanser', 'CeraVe', 150000, 15, 50, 'All', 'Dehydrated', 'Gentle hydrating cleanser that removes dirt and oil without disrupting the skin barrier', 'Ceramides, Hyaluronic Acid, Glycerin', 4.8, 245),
('The Ordinary Niacinamide 10%', 'The Ordinary', 95000, 18, 45, 'Oily', 'Acne', 'High-strength vitamin B3 formula to minimize pores and reduce skin blemishes', 'Niacinamide, Zinc PCA', 4.5, 512),
('Laneige Water Sleeping Mask', 'Laneige', 285000, 18, 50, 'All', 'Dehydrated', 'Overnight hydrating mask that deeply moisturizes while you sleep', 'Hydro Ionized Mineral Water, Evening Primrose Extract', 4.7, 389),
('Sk-II Facial Treatment Essence', 'Sk-II', 850000, 25, 50, 'All', 'Aging', 'Iconic essence that promotes visibly radiant, toned, textured, and replenished skin', 'Pitera, Galactomyces Ferment Filtrate', 4.9, 178),
('La Roche-Posay Effaclar Duo', 'La Roche-Posay', 320000, 15, 40, 'Oily', 'Acne', 'Dual action acne treatment that unclogs pores and reduces redness', 'Ceramide, Salicylic Acid, Glycerin', 4.6, 423),
('Cetaphil Moisturizing Cream', 'Cetaphil', 175000, 10, 60, 'Dry', 'Dehydrated', 'Intense moisturizing cream for dry to very dry skin', 'Sweet Almond Oil, Glycerin, Vitamin E', 4.7, 567),
('Paula\'s Choice BHA Exfoliant', 'Paula\'s Choice', 295000, 18, 50, 'Combination', 'Acne', 'Liquid exfoliant that fights blackheads and shrinks pores', 'Salicylic Acid, Green Tea Extract, Methylpropanediol', 4.8, 634),
('Cosrx Advanced Snail Mucin', 'Cosrx', 220000, 15, 50, 'All', 'Aging', 'Snail secretion filtrate that hydrates and repairs damaged skin', 'Snail Mucin 96%, Hyaluronic Acid, EGF', 4.9, 891),
('Neutrogena Rapid Clear', 'Neutrogena', 165000, 12, 35, 'Oily', 'Acne', 'Fast-acting acne fighting leave-on treatment', 'Salicylic Acid 2%, Benzoyl Peroxide', 4.4, 312),
('Olay Regenerist Serum', 'Olay', 380000, 30, 60, 'Normal', 'Aging', 'Anti-aging micro-sculpting serum that reduces fine lines', 'Vitamin B3, Amino Peptides, Hyaluronic Acid', 4.6, 445),
(' Vichy Mineralizing Water', 'Vichy', 195000, 20, 50, 'Sensitive', 'Dehydrated', 'Thermal spa water that strengthens skin barrier', 'Vichy Volcanic Water, Mineralizing Salts', 4.5, 267),
('Innisfree Green Tea Seed Hyaluronic', 'Innisfree', 245000, 18, 45, 'Dry', 'Dehydrated', 'Hydrating serum with green tea and hyaluronic acid', 'Green Tea Extract, Hyaluronic Acid, Jeju Green Tea Seed Oil', 4.7, 378),
('Avene Cleanance Comedomed', 'Avene', 265000, 15, 35, 'Oily', 'Acne', 'Anti-blemish care that reduces imperfections and prevents recurrence', 'Comedoclastin, Salicylic Acid, Avene Thermal Spring Water', 4.6, 198),
('Hada Labo Gokujyun Premium', 'Hada Labo', 135000, 15, 50, 'All', 'Dehydrated', 'Super hyaluronic acid lotion that deeply hydrates', 'Super Hyaluronic Acid, Collagen, Nano Hyaluronic Acid', 4.8, 723),
('Some By Mi Yuja Niacinamide', 'Some By Mi', 275000, 18, 45, 'Normal', 'Dark Spot', 'Brightening serum with 82% yuja extract and niacinamide', 'Yuja Extract, Niacinamide 12%, Glutathione', 4.7, 456);

-- =====================================================
-- DUMMY DATA: Test User
-- =====================================================

INSERT INTO users (name, email, password, age, skin_type, skin_concern) VALUES
('Test User', 'test@skincare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 25, 'Combination', 'Acne'),
('Admin User', 'admin@skincare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 30, 'Dry', 'Aging');

-- =====================================================
-- VIEW: Products filtered by criteria (for reference)
-- =====================================================

-- Example query to get products matching user preferences:
-- SELECT * FROM products 
-- WHERE price BETWEEN ? AND ?
-- AND target_age_min <= ? AND target_age_max >= ?
-- AND (skin_type = ? OR skin_type = 'All')
-- AND (skin_concern = ? OR skin_concern = 'All');

-- =====================================================
-- END OF DATABASE SCRIPT
-- =====================================================

