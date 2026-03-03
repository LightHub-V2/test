<?php
/**
 * Main Index Page
 * Skincare Recommendation Website
 * 
 * This is the main landing page with hero section, filters, and product grid
 */

// Include database configuration
require_once 'config/database.php';

// Set page title
$pageTitle = 'Beranda';
$currentPage = 'home';

// Get products from database
$products = getProducts();

// Include header template
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Temukan Skincare yang Tepat untuk Kulit Anda</h1>
        <p>Sistem rekomendasi skincare berbasis智能 untuk membantu Anda menemukan produk yang sesuai dengan jenis kulit dan masalah kulit Anda.</p>
        <div class="hero-buttons">
            <a href="#products" class="btn btn-primary">
                <i class="fas fa-store"></i> Lihat Produk
            </a>
            <a href="quiz.php" class="btn btn-secondary">
                <i class="fas fa-clipboard-check"></i> Ikuti Skin Quiz
            </a>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="filter-section" id="filter">
    <div class="filter-header">
        <h2><i class="fas fa-filter"></i> Filter Produk</h2>
        <button class="filter-toggle" onclick="toggleFilter()">Tampilkan Filter</button>
    </div>
    
    <form id="filterForm" class="filter-grid">
        <!-- Price Range -->
        <div class="filter-group">
            <label for="minPrice">Harga Minimum</label>
            <input type="number" id="minPrice" name="min_price" placeholder="Rp 0" min="0">
        </div>
        
        <div class="filter-group">
            <label for="maxPrice">Harga Maksimum</label>
            <input type="number" id="maxPrice" name="max_price" placeholder="Rp 1.000.000" min="0">
        </div>
        
        <!-- Age Filter -->
        <div class="filter-group">
            <label for="filterAge">Umur Anda</label>
            <select id="filterAge" name="age">
                <option value="">Semua Umur</option>
                <option value="10">10-15 tahun</option>
                <option value="16">16-20 tahun</option>
                <option value="21">21-25 tahun</option>
                <option value="26">26-30 tahun</option>
                <option value="31">31-40 tahun</option>
                <option value="41">41-50 tahun</option>
                <option value="51">50+ tahun</option>
            </select>
        </div>
        
        <!-- Skin Type Filter -->
        <div class="filter-group">
            <label for="skinType">Jenis Kulit</label>
            <select id="skinType" name="skin_type">
                <option value="">Semua Jenis Kulit</option>
                <option value="Oily">Kulit Berminyak (Oily)</option>
                <option value="Dry">Kulit Kering (Dry)</option>
                <option value="Combination">Kulit Kombinasi</option>
                <option value="Sensitive">Kulit Sensitif</option>
                <option value="Normal">Kulit Normal</option>
            </select>
        </div>
        
        <!-- Skin Concern Filter -->
        <div class="filter-group">
            <label for="skinConcern">Masalah Kulit</label>
            <select id="skinConcern" name="skin_concern">
                <option value="">Semua Masalah Kulit</option>
                <option value="Acne">Jerawat (Acne)</option>
                <option value="Dark Spot">Fleok Hitam</option>
                <option value="Aging">Penuaan Dini</option>
                <option value="Dehydrated">Kulit Dehidrasi</option>
            </select>
        </div>
        
        <!-- Filter Actions -->
        <div class="filter-actions">
            <button type="button" class="btn btn-reset" id="resetFilters">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-gradient">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</section>

<!-- Products Section -->
<section class="products-section" id="products">
    <div class="products-header">
        <h2><i class="fas fa-box-open"></i> Produk Skincare</h2>
        <span class="products-count" id="productsCount"><?php echo count($products); ?> produk ditemukan</span>
    </div>
    
    <!-- Loading Spinner -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <p class="loading-text">Memuat produk...</p>
    </div>
    
    <!-- Products Grid -->
    <div class="products-grid" id="productsGrid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $index => $product): ?>
                <?php 
                // Determine badge
                $badge = '';
                if ($product['rating'] >= 4.8) {
                    $badge = '<span class="product-badge best">Best Seller</span>';
                } elseif ($product['skin_concern'] === 'Acne') {
                    $badge = '<span class="product-badge">Anti-Acne</span>';
                }
                
                // Generate rating stars
                $ratingStars = generateStarRating($product['rating']);
                ?>
                
                <div class="product-card" onclick="viewProduct(<?php echo $product['id']; ?>)" style="animation-delay: <?php echo $index * 0.1; ?>s">
                    <?php echo $badge; ?>
                    <div class="image-container">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Skincare+Product'">
                    </div>
                    <div class="product-info">
                        <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
                        <div class="product-meta">
                            <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                            <div class="product-rating">
                                <?php echo $ratingStars; ?>
                                <span>(<?php echo $product['review_count']; ?>)</span>
                            </div>
                        </div>
                        <div class="product-tags">
                            <span class="tag"><?php echo htmlspecialchars($product['skin_type']); ?></span>
                            <span class="tag"><?php echo htmlspecialchars($product['skin_concern']); ?></span>
                            <span class="tag"><?php echo $product['target_age_min']; ?>-<?php echo $product['target_age_max']; ?> tahun</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Tidak ada produk ditemukan</h3>
                <p>Silakan coba sesuaikan filter Anda</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quiz CTA Section -->
<section class="quiz-section">
    <div class="quiz-intro">
        <h2><i class="fas fa-clipboard-check"></i> Tidak Yakin Produk Mana yang Cocok?</h2>
        <p>Ikuti Skin Quiz kami untuk mendapatkan rekomendasi produk yang disesuaikan dengan jenis kulit dan masalah kulit Anda.</p>
        <a href="quiz.php" class="btn btn-gradient">
            <i class="fas fa-play"></i> Mulai Skin Quiz
        </a>
    </div>
</section>

<?php
// Include footer template
include 'includes/footer.php';
?>

