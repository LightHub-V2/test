<?php
/**
 * Skin Quiz Page
 * Skincare Recommendation Website
 * 
 * This page handles the interactive skin quiz for recommendations
 */

// Include database configuration
require_once 'config/database.php';

// Set page title
$pageTitle = 'Skin Quiz';
$currentPage = 'quiz';

// Handle AJAX request for recommendations
if (isset($_GET['action']) && $_GET['action'] === 'recommend') {
    header('Content-Type: application/json');
    
    $response = [
        'success' => false,
        'user' => [],
        'products' => [],
        'message' => ''
    ];
    
    try {
        $age = isset($_GET['age']) ? intval($_GET['age']) : null;
        $skin_type = isset($_GET['skin_type']) ? $_GET['skin_type'] : '';
        $skin_concern = isset($_GET['skin_concern']) ? $_GET['skin_concern'] : '';
        
        if ($age && $skin_type && $skin_concern) {
            // Get recommended products
            $products = getRecommendedProducts($skin_type, $skin_concern, $age);
            
            // Format products
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
                    'image_url' => htmlspecialchars($product['image_url']),
                    'rating' => floatval($product['rating']),
                    'review_count' => intval($product['review_count'])
                ];
            }, $products);
            
            $response['success'] = true;
            $response['user'] = [
                'age' => $age,
                'skin_type' => $skin_type,
                'skin_concern' => $skin_concern
            ];
            $response['products'] = $formattedProducts;
            
            if (count($formattedProducts) === 0) {
                $response['message'] = 'Tidak ada produk yang cocok ditemukan';
            }
        } else {
            $response['message'] = 'Parameter tidak lengkap';
        }
        
    } catch (Exception $e) {
        $response['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        error_log("Quiz Error: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit;
}

// Include header template
include 'includes/header.php';
?>

<!-- Quiz Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Skin Quiz - Temukan Kulit Terbaik Anda</h1>
        <p>Jawab beberapa pertanyaan singkat untuk mendapatkan rekomendasi skincare yang tepat untuk Anda.</p>
    </div>
</section>

<!-- Quiz Section -->
<section class="quiz-section" id="quiz">
    <!-- Quiz Intro -->
    <div class="quiz-intro" id="quizIntro">
        <h2><i class="fas fa-clipboard-check"></i> Mulai Skin Quiz</h2>
        <p>Quiz ini akan membantu Anda memahami jenis kulit dan mendapatkan rekomendasi produk yang tepat. Waktunya hanya 1 menit!</p>
        
        <div class="quiz-features">
            <div class="feature-item">
                <i class="fas fa-user-clock"></i>
                <span>1 Menit</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-question-circle"></i>
                <span>3 Pertanyaan</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-gift"></i>
                <span>Rekomendasi Akurat</span>
            </div>
        </div>
        
        <button class="btn btn-gradient" onclick="startQuiz()">
            <i class="fas fa-play"></i> Mulai Quiz
        </button>
    </div>
    
    <!-- Quiz Questions -->
    <div class="quiz-questions" id="quizQuestions" style="display: none;">
        <!-- Progress Bar -->
        <div class="quiz-progress">
            <div class="quiz-progress-bar" id="quizProgressBar" style="width: 33%;"></div>
        </div>
        
        <!-- Question 1: Age -->
        <div class="quiz-question active" data-question="0">
            <h3><i class="fas fa-birthday-cake"></i> Berapa usia Anda?</h3>
            <div class="quiz-options">
                <button class="quiz-option" data-value="10">Dibawah 15 tahun</button>
                <button class="quiz-option" data-value="15">15-20 tahun</button>
                <button class="quiz-option" data-value="21">21-25 tahun</button>
                <button class="quiz-option" data-value="26">26-30 tahun</button>
                <button class="quiz-option" data-value="31">31-40 tahun</button>
                <button class="quiz-option" data-value="41">41-50 tahun</button>
                <button class="quiz-option" data-value="51">50+ tahun</button>
            </div>
            <div class="quiz-buttons">
                <button class="btn btn-secondary" onclick="restartQuizIntro()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
        
        <!-- Question 2: Skin Type -->
        <div class="quiz-question" data-question="1">
            <h3><i class="fas fa-face-smile"></i> Apa jenis kulit Anda?</h3>
            <div class="quiz-options">
                <button class="quiz-option" data-value="Oily">
                    <i class="fas fa-oil-well"></i> Berminyak (Oily)
                </button>
                <button class="quiz-option" data-value="Dry">
                    <i class="fas fa-droplet"></i> Kering (Dry)
                </button>
                <button class="quiz-option" data-value="Combination">
                    <i class="fas fa-layer-group"></i> Kombinasi
                </button>
                <button class="quiz-option" data-value="Sensitive">
                    <i class="fas fa-heart-crack"></i> Sensitif
                </button>
                <button class="quiz-option" data-value="Normal">
                    <i class="fas fa-face-meh"></i> Normal
                </button>
            </div>
            <div class="quiz-buttons">
                <button class="btn btn-secondary" onclick="prevQuestion()">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
            </div>
        </div>
        
        <!-- Question 3: Skin Concern -->
        <div class="quiz-question" data-question="2">
            <h3><i class="fas fa-user-nurse"></i> Apa masalah kulit utama Anda?</h3>
            <div class="quiz-options">
                <button class="quiz-option" data-value="Acne">
                    <i class="fas fa-bacteria"></i> Jerawat
                </button>
                <button class="quiz-option" data-value="Dark Spot">
                    <i class="fas fa-circle-half-stroke"></i> Flek Hitam
                </button>
                <button class="quiz-option" data-value="Aging">
                    <i class="fas fa-hourglass-half"></i> Penuaan Dini
                </button>
                <button class="quiz-option" data-value="Dehydrated">
                    <i class="fas fa-water"></i> Dehidrasi
                </button>
            </div>
            <div class="quiz-buttons">
                <button class="btn btn-secondary" onclick="prevQuestion()">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button class="btn btn-gradient btn-submit-quiz" onclick="submitQuiz()">
                    <i class="fas fa-check"></i> Lihat Rekomendasi
                </button>
            </div>
        </div>
    </div>
    
    <!-- Quiz Result -->
    <div class="quiz-result" id="quizResult">
        <div class="result-header">
            <i class="fas fa-check-circle"></i>
            <h3>Hasil Rekomendasi Anda</h3>
            <p>Berdasarkan jawaban Anda, kami menemukan produk yang cocok!</p>
        </div>
        
        <div class="result-details" id="resultDetails">
            <!-- Will be populated by JavaScript -->
        </div>
        
        <div class="recommended-products">
            <h4><i class="fas fa-gift"></i> Produk Rekomendasi</h4>
            <div class="products-grid" id="recommendedProducts">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
        
        <div class="quiz-buttons" style="margin-top: 2rem;">
            <button class="btn btn-secondary" onclick="restartQuiz()">
                <i class="fas fa-redo"></i> Ikuti Quiz Lagi
            </button>
            <a href="index.php#products" class="btn btn-gradient">
                <i class="fas fa-store"></i> Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- Quiz Features Section -->
<section class="features-section">
    <div class="features-container">
        <div class="feature-box">
            <i class="fas fa-clock"></i>
            <h3>Cepat & Mudah</h3>
            <p>Selesai dalam kurang dari 1 menit</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-bullseye"></i>
            <h3>Akurat</h3>
            <p>Rekomendasi berdasarkan data ilmiah</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-heart"></i>
            <h3>Personal</h3>
            <p>Sesuai dengan kondisi kulit Anda</p>
        </div>
    </div>
</section>

<style>
.quiz-features {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    color: white;
    font-weight: 500;
}

.feature-item i {
    font-size: 1.2rem;
}

.features-section {
    padding: 4rem 5%;
    background: white;
    margin: 2rem 5%;
    border-radius: 20px;
    box-shadow: var(--shadow);
}

.features-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.feature-box {
    text-align: center;
    padding: 2rem;
}

.feature-box i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.feature-box h3 {
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.feature-box p {
    color: #666;
}

@media (max-width: 768px) {
    .quiz-features {
        flex-direction: column;
        align-items: center;
    }
    
    .feature-item {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
}
</style>

<?php
// Include footer template
include 'includes/footer.php';
?>

