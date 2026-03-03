<?php
/**
 * Product Detail Page
 * Skincare Recommendation Website
 * 
 * This page displays detailed information about a product
 */

// Include database configuration
require_once 'config/database.php';

// Set page title
$pageTitle = 'Detail Produk';
$currentPage = 'products';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get product from database
$product = null;
if ($productId > 0) {
    $product = getProductById($productId);
}

// If product not found, redirect to index
if (!$product) {
    header('Location: index.php');
    exit;
}

// Get related products (same skin type or concern)
$relatedFilters = [
    'skin_type' => $product['skin_type'],
    'skin_concern' => $product['skin_concern']
];
$relatedProducts = getProducts($relatedFilters);
// Remove current product from related
$relatedProducts = array_filter($relatedProducts, function($p) use ($productId) {
    return $p['id'] != $productId;
});
// Limit to 4 products
$relatedProducts = array_slice($relatedProducts, 0, 4);

// Include header template
include 'includes/header.php';
?>

<!-- Product Detail Section -->
<section class="product-detail">
    <!-- Back Button -->
    <a href="index.php#products" class="back-button">
        <i class="fas fa-arrow-left"></i> Kembali ke Produk
    </a>
    
    <?php if ($product): ?>
    <div class="product-detail-grid">
        <!-- Product Image -->
        <div class="detail-image-container">
            <?php if ($product['rating'] >= 4.8): ?>
                <span class="product-badge best">Best Seller</span>
            <?php endif; ?>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="detail-image"
                 onerror="this.src='https://via.placeholder.com/400x300?text=Skincare+Product'">
        </div>
        
        <!-- Product Information -->
        <div class="detail-info">
            <div class="detail-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <!-- Rating -->
            <div class="detail-rating">
                <?php 
                $ratingStars = generateStarRating($product['rating']);
                echo $ratingStars;
                ?>
                <span><?php echo number_format($product['rating'], 1); ?> / 5.0</span>
                <span>(<?php echo $product['review_count']; ?> review)</span>
            </div>
            
            <!-- Price -->
            <div class="detail-price"><?php echo formatPrice($product['price']); ?></div>
            
            <!-- Description -->
            <p class="detail-description"><?php echo htmlspecialchars($product['description']); ?></p>
            
            <!-- Product Specifications -->
            <div class="detail-specs">
                <h4><i class="fas fa-info-circle"></i> Spesifikasi Produk</h4>
                <div class="spec-item">
                    <span class="spec-label">Jenis Kulit</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['skin_type']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Masalah Kulit</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['skin_concern']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Target Usia</span>
                    <span class="spec-value"><?php echo $product['target_age_min']; ?> - <?php echo $product['target_age_max']; ?> tahun</span>
                </div>
            </div>
            
            <!-- Ingredients -->
            <?php if (!empty($product['ingredients'])): ?>
            <div class="detail-specs">
                <h4><i class="fas fa-flask"></i> Ingredients</h4>
                <p style="color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($product['ingredients']); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="detail-actions">
                <button class="btn btn-gradient" onclick="addToCart(<?php echo $product['id']; ?>)">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </button>
                <button class="btn btn-secondary" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                    <i class="fas fa-heart"></i> Wishlist
                </button>
            </div>
            
            <!-- Tags -->
            <div class="product-tags" style="margin-top: 1.5rem;">
                <span class="tag"><?php echo htmlspecialchars($product['skin_type']); ?></span>
                <span class="tag"><?php echo htmlspecialchars($product['skin_concern']); ?></span>
                <span class="tag">Usia <?php echo $product['target_age_min']; ?>-<?php echo $product['target_age_max']; ?></span>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (count($relatedProducts) > 0): ?>
    <section class="related-products" style="margin-top: 4rem;">
        <h2 style="margin-bottom: 2rem; color: var(--dark);">
            <i class="fas fa-thumbs-up"></i> Produk Terkait
        </h2>
        <div class="products-grid">
            <?php foreach ($relatedProducts as $related): ?>
                <?php 
                $badge = '';
                if ($related['rating'] >= 4.8) {
                    $badge = '<span class="product-badge best">Best Seller</span>';
                }
                $ratingStars = generateStarRating($related['rating']);
                ?>
                <div class="product-card" onclick="viewProduct(<?php echo $related['id']; ?>)">
                    <?php echo $badge; ?>
                    <div class="image-container">
                        <img src="<?php echo htmlspecialchars($related['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($related['name']); ?>" 
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Skincare+Product'">
                    </div>
                    <div class="product-info">
                        <div class="product-brand"><?php echo htmlspecialchars($related['brand']); ?></div>
                        <h3 class="product-name"><?php echo htmlspecialchars($related['name']); ?></h3>
                        <div class="product-meta">
                            <div class="product-price"><?php echo formatPrice($related['price']); ?></div>
                            <div class="product-rating">
                                <?php echo $ratingStars; ?>
                                <span>(<?php echo $related['review_count']; ?>)</span>
                            </div>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <?php else: ?>
    <!-- Product Not Found -->
    <div class="empty-state">
        <i class="fas fa-exclamation-circle"></i>
        <h3>Produk Tidak Ditemukan</h3>
        <p>Produk yang Anda cari tidak dapat ditemukan.</p>
        <a href="index.php#products" class="btn btn-gradient">
            <i class="fas fa-store"></i> Lihat Produk Lainnya
        </a>
    </div>
    <?php endif; ?>
</section>

<style>
.detail-image-container {
    position: relative;
}

.detail-image-container .product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 1;
}

.detail-specs {
    background: var(--light);
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 1.5rem;
}

.detail-specs h4 {
    color: var(--dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.spec-item:last-child {
    border-bottom: none;
}

.spec-label {
    color: #666;
}

.spec-value {
    font-weight: 600;
    color: var(--primary);
}

.detail-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.detail-actions .btn {
    flex: 1;
    justify-content: center;
}

@media (max-width: 768px) {
    .detail-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Add to cart function
function addToCart(productId) {
    // Show notification
    showNotification('Produk ditambahkan ke keranjang!', 'success');
    // In a real app, this would add to cart
    console.log('Add to cart:', productId);
}

// Add to wishlist function
function addToWishlist(productId) {
    // Show notification
    showNotification('Produk ditambahkan ke wishlist!', 'success');
    // In a real app, this would add to wishlist
    console.log('Add to wishlist:', productId);
}
</script>

<?php
// Include footer template
include 'includes/footer.php';
?>

