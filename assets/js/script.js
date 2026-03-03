/**
 * Skincare Recommendation Website - JavaScript
 * For Skripsi Project
 * 
 * This file handles all client-side functionality
 */

// =====================================================
// GLOBAL VARIABLES
// =====================================================

// Quiz state
let quizState = {
    currentQuestion: 0,
    answers: {
        age: null,
        skinType: null,
        skinConcern: null
    }
};

// Products data (loaded from PHP)
let productsData = [];

// Filter state
let currentFilters = {
    minPrice: '',
    maxPrice: '',
    age: '',
    skinType: '',
    skinConcern: ''
};

// =====================================================
// DOM CONTENT LOADED
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initMobileMenu();
    initFilters();
    initQuiz();
    initProductCards();
    initNotifications();
});

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const menu = document.getElementById('mobileMenu');
    
    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
            const icon = toggle.querySelector('i');
            if (menu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('active');
                const icon = toggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
}

// =====================================================
// FILTER FUNCTIONALITY
// =====================================================

/**
 * Initialize filters
 */
function initFilters() {
    const filterToggle = document.querySelector('.filter-toggle');
    const filterGrid = document.querySelector('.filter-grid');
    const filterForm = document.getElementById('filterForm');
    const resetButton = document.getElementById('resetFilters');
    
    // Toggle filter visibility on mobile
    if (filterToggle && filterGrid) {
        filterToggle.addEventListener('click', function() {
            filterGrid.classList.toggle('active');
            const text = filterGrid.classList.contains('active') ? 'Sembunyikan Filter' : 'Tampilkan Filter';
            filterToggle.textContent = text;
        });
    }
    
    // Handle filter form submission
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
    // Handle reset button
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            resetFilters();
        });
    }
    
    // Check for URL parameters (for pre-filled filters)
    loadFiltersFromURL();
}

/**
 * Load filters from URL parameters
 */
function loadFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const age = document.getElementById('filterAge');
    const skinType = document.getElementById('skinType');
    const skinConcern = document.getElementById('skinConcern');
    
    if (urlParams.has('min_price') && minPrice) {
        minPrice.value = urlParams.get('min_price');
    }
    if (urlParams.has('max_price') && maxPrice) {
        maxPrice.value = urlParams.get('max_price');
    }
    if (urlParams.has('age') && age) {
        age.value = urlParams.get('age');
    }
    if (urlParams.has('skin_type') && skinType) {
        skinType.value = urlParams.get('skin_type');
    }
    if (urlParams.has('skin_concern') && skinConcern) {
        skinConcern.value = urlParams.get('skin_concern');
    }
    
    // Apply filters if URL has parameters
    if (urlParams.toString()) {
        applyFilters();
    }
}

/**
 * Apply filters (AJAX)
 */
async function applyFilters() {
    const minPrice = document.getElementById('minPrice')?.value || '';
    const maxPrice = document.getElementById('maxPrice')?.value || '';
    const age = document.getElementById('filterAge')?.value || '';
    const skinType = document.getElementById('skinType')?.value || '';
    const skinConcern = document.getElementById('skinConcern')?.value || '';
    
    // Update current filters
    currentFilters = { minPrice, maxPrice, age, skinType, skinConcern };
    
    // Show loading
    showLoading();
    
    // Build query string
    const params = new URLSearchParams();
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);
    if (age) params.append('age', age);
    if (skinType) params.append('skin_type', skinType);
    if (skinConcern) params.append('skin_concern', skinConcern);
    
    try {
        const response = await fetch(`search.php?${params.toString()}`);
        const data = await response.json();
        
        if (data.success) {
            displayProducts(data.products);
        } else {
            showNotification('Gagal memuat produk', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memuat produk', 'error');
    }
    
    // Update URL without reload
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({}, '', newUrl);
}

/**
 * Reset filters
 */
function resetFilters() {
    // Clear form inputs
    const inputs = document.querySelectorAll('#filterForm input, #filterForm select');
    inputs.forEach(input => {
        input.value = '';
    });
    
    // Reset filters
    currentFilters = {
        minPrice: '',
        maxPrice: '',
        age: '',
        skinType: '',
        skinConcern: ''
    };
    
    // Load all products
    showLoading();
    
    fetch('search.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProducts(data.products);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        });
    
    // Clear URL
    window.history.pushState({}, '', window.location.pathname);
}

/**
 * Show loading spinner
 */
function showLoading() {
    const productsGrid = document.getElementById('productsGrid');
    const loading = document.getElementById('loading');
    
    if (productsGrid) productsGrid.style.display = 'none';
    if (loading) loading.classList.add('active');
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    const productsGrid = document.getElementById('productsGrid');
    const loading = document.getElementById('loading');
    
    if (loading) loading.classList.remove('active');
    if (productsGrid) productsGrid.style.display = 'grid';
}

/**
 * Display products in grid
 */
function displayProducts(products) {
    const productsGrid = document.getElementById('productsGrid');
    const productsCount = document.getElementById('productsCount');
    const loading = document.getElementById('loading');
    
    hideLoading();
    
    if (!productsGrid) return;
    
    // Update count
    if (productsCount) {
        productsCount.textContent = `${products.length} produk ditemukan`;
    }
    
    // Clear current products
    productsGrid.innerHTML = '';
    
    if (products.length === 0) {
        // Show empty state
        productsGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Tidak ada produk ditemukan</h3>
                <p>Coba sesuaikan filter Anda atau lihat produk lainnya</p>
                <button class="btn btn-gradient" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        `;
        return;
    }
    
    // Display products
    products.forEach((product, index) => {
        const card = createProductCard(product, index);
        productsGrid.innerHTML += card;
    });
    
    // Add animation delay
    const cards = productsGrid.querySelectorAll('.product-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
}

/**
 * Create product card HTML
 */
function createProductCard(product, index = 0) {
    // Format price
    const formattedPrice = formatPrice(product.price);
    
    // Generate rating stars
    const ratingStars = generateStars(product.rating);
    
    // Determine badge
    let badge = '';
    if (product.rating >= 4.8) {
        badge = '<span class="product-badge best">Best Seller</span>';
    } else if (product.skin_concern === 'Acne') {
        badge = '<span class="product-badge">Anti-Acne</span>';
    }
    
    return `
        <div class="product-card" onclick="viewProduct(${product.id})">
            ${badge}
            <div class="image-container">
                <img src="${product.image_url}" alt="${product.name}" class="product-image" 
                     onerror="this.src='https://via.placeholder.com/300x200?text=Skincare+Product'">
            </div>
            <div class="product-info">
                <div class="product-brand">${product.brand}</div>
                <h3 class="product-name">${product.name}</h3>
                <p class="product-description">${product.description || ''}</p>
                <div class="product-meta">
                    <div class="product-price">${formattedPrice}</div>
                    <div class="product-rating">
                        ${ratingStars}
                        <span>(${product.review_count})</span>
                    </div>
                </div>
                <div class="product-tags">
                    <span class="tag">${product.skin_type}</span>
                    <span class="tag">${product.skin_concern}</span>
                    <span class="tag">${product.target_age_min}-${product.target_age_max} tahun</span>
                </div>
                <button class="btn-detail">
                    <i class="fas fa-eye"></i> Lihat Detail
                </button>
            </div>
        </div>
    `;
}

// =====================================================
// QUIZ FUNCTIONALITY
// =====================================================

/**
 * Initialize quiz
 */
function initQuiz() {
    const quizContainer = document.getElementById('quizContainer');
    
    if (!quizContainer) return;
    
    // Show first question
    showQuestion(0);
    
    // Handle option selection
    document.querySelectorAll('.quiz-option').forEach(option => {
        option.addEventListener('click', function() {
            handleOptionClick(this);
        });
    });
}

/**
 * Show question by index
 */
function showQuestion(index) {
    const questions = document.querySelectorAll('.quiz-question');
    
    questions.forEach((question, i) => {
        if (i === index) {
            question.classList.add('active');
        } else {
            question.classList.remove('active');
        }
    });
    
    // Update progress bar
    updateProgressBar(index);
    
    // Update quiz state
    quizState.currentQuestion = index;
}

/**
 * Update progress bar
 */
function updateProgressBar(index) {
    const progressBar = document.getElementById('quizProgressBar');
    const totalQuestions = 3;
    const percentage = ((index + 1) / totalQuestions) * 100;
    
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
    }
}

/**
 * Handle option click
 */
function handleOptionClick(option) {
    const question = option.closest('.quiz-question');
    const questionIndex = Array.from(document.querySelectorAll('.quiz-question')).indexOf(question);
    const value = option.dataset.value;
    
    // Remove selected class from all options in this question
    question.querySelectorAll('.quiz-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    option.classList.add('selected');
    
    // Store answer
    switch (questionIndex) {
        case 0:
            quizState.answers.age = parseInt(value);
            break;
        case 1:
            quizState.answers.skinType = value;
            break;
        case 2:
            quizState.answers.skinConcern = value;
            break;
    }
    
    // Auto advance after selection
    setTimeout(() => {
        if (questionIndex < 2) {
            nextQuestion();
        } else {
            submitQuiz();
        }
    }, 500);
}

/**
 * Go to next question
 */
function nextQuestion() {
    const currentIndex = quizState.currentQuestion;
    if (currentIndex < 2) {
        showQuestion(currentIndex + 1);
    }
}

/**
 * Go to previous question
 */
function prevQuestion() {
    const currentIndex = quizState.currentQuestion;
    if (currentIndex > 0) {
        showQuestion(currentIndex - 1);
    }
}

/**
 * Submit quiz and get recommendations
 */
async function submitQuiz() {
    const { age, skinType, skinConcern } = quizState.answers;
    
    if (!age || !skinType || !skinConcern) {
        showNotification('Silakan jawab semua pertanyaan', 'error');
        return;
    }
    
    // Show loading
    const submitButton = document.querySelector('.btn-submit-quiz');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    }
    
    try {
        const response = await fetch(`quiz.php?action=recommend&age=${age}&skin_type=${skinType}&skin_concern=${skinConcern}`);
        const data = await response.json();
        
        if (data.success) {
            showQuizResult(data);
        } else {
            showNotification('Gagal mendapatkan rekomendasi', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
    
    // Reset button
    if (submitButton) {
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-check"></i> Lihat Rekomendasi';
    }
}

/**
 * Show quiz result
 */
function showQuizResult(data) {
    const questionsSection = document.getElementById('quizQuestions');
    const resultSection = document.getElementById('quizResult');
    
    if (questionsSection) questionsSection.style.display = 'none';
    if (resultSection) resultSection.classList.add('active');
    
    // Update result details
    const resultDetails = document.getElementById('resultDetails');
    if (resultDetails) {
        resultDetails.innerHTML = `
            <p>Usia: <span>${data.user.age} tahun</span></p>
            <p>Jenis Kulit: <span>${data.user.skin_type}</span></p>
            <p>Masalah Kulit: <span>${data.user.skin_concern}</span></p>
        `;
    }
    
    // Display recommended products
    const recommendedGrid = document.getElementById('recommendedProducts');
    if (recommendedGrid && data.products.length > 0) {
        recommendedGrid.innerHTML = '';
        data.products.forEach(product => {
            recommendedGrid.innerHTML += createProductCard(product);
        });
    } else if (recommendedGrid) {
        recommendedGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-sad-tear"></i>
                <h3>Tidak ada produk rekomendasi</h3>
                <p>Coba jawab pertanyaan lagi dengan pilihan berbeda</p>
            </div>
        `;
    }
    
    // Scroll to result
    resultSection.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restart quiz
 */
function restartQuiz() {
    // Reset state
    quizState = {
        currentQuestion: 0,
        answers: {
            age: null,
            skinType: null,
            skinConcern: null
        }
    };
    
    // Reset UI
    const questionsSection = document.getElementById('quizQuestions');
    const resultSection = document.getElementById('quizResult');
    
    if (questionsSection) questionsSection.style.display = 'block';
    if (resultSection) resultSection.classList.remove('active');
    
    // Reset options
    document.querySelectorAll('.quiz-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Show first question
    showQuestion(0);
}

// =====================================================
// PRODUCT DETAIL
// =====================================================

/**
 * View product detail
 */
function viewProduct(id) {
    window.location.href = `product.php?id=${id}`;
}

/**
 * Initialize product cards click
 */
function initProductCards() {
    // Already handled in createProductCard
}

// =====================================================
// NOTIFICATIONS
// =====================================================

/**
 * Initialize notifications
 */
function initNotifications() {
    // Auto-hide notifications after 5 seconds
    setTimeout(() => {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    }, 5000);
}

/**
 * Show notification
 */
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existing = document.querySelectorAll('.notification');
    existing.forEach(n => n.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    notification.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Add slideOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
`;
document.head.appendChild(style);

// =====================================================
// HELPER FUNCTIONS
// =====================================================

/**
 * Format price to Indonesian Rupiah
 */
function formatPrice(price) {
    return 'Rp ' + Number(price).toLocaleString('id-ID');
}

/**
 * Generate star rating HTML
 */
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = (rating - fullStars) >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let html = '';
    
    for (let i = 0; i < fullStars; i++) {
        html += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        html += '<i class="fas fa-star-half-alt"></i>';
    }
    
    for (let i = 0; i < emptyStars; i++) {
        html += '<i class="far fa-star"></i>';
    }
    
    return html;
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// =====================================================
// EXPORT FOR GLOBAL USE
// =====================================================

// Make functions available globally
window.viewProduct = viewProduct;
window.resetFilters = resetFilters;
window.restartQuiz = restartQuiz;
window.showNotification = showNotification;
window.nextQuestion = nextQuestion;
window.prevQuestion = prevQuestion;

