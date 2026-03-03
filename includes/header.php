<?php
/**
 * Header Template
 * Skincare Recommendation Website
 * 
 * This file contains the header HTML structure
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Rekomendasi Skincare - Temukan produk skincare yang tepat untuk jenis kulit Anda">
    <meta name="keywords" content="skincare, rekomendasi, produk kulit, jenis kulit, acne, aging">
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Skincare Recommendation</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🧴</text></svg>">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="logo">
                <i class="fas fa-spa"></i> SkincareRec
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Beranda
                </a></li>
                <li><a href="index.php#products" class="<?php echo $currentPage === 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-store"></i> Produk
                </a></li>
                <li><a href="quiz.php" class="<?php echo $currentPage === 'quiz' ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-check"></i> Skin Quiz
                </a></li>
                <li><a href="#about" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">
                    <i class="fas fa-info-circle"></i> Tentang
                </a></li>
            </ul>
            
            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <ul class="mobile-nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="index.php#products"><i class="fas fa-store"></i> Produk</a></li>
                <li><a href="quiz.php"><i class="fas fa-clipboard-check"></i> Skin Quiz</a></li>
                <li><a href="#about"><i class="fas fa-info-circle"></i> Tentang</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Start -->
    <main>

