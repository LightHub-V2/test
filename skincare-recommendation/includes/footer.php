<?php
/**
 * Footer Template
 * Skincare Recommendation Website
 * 
 * This file contains the footer HTML structure
 */
?>

    </main>
    <!-- Main Content End -->

    <!-- Footer Section -->
    <footer id="about">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Brand Info -->
                <div class="footer-brand">
                    <h3><i class="fas fa-spa"></i> SkincareRec</h3>
                    <p>Sistem Rekomendasi Skincare untuk menemukan produk yang tepat sesuai jenis kulit Anda.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="index.php#products">Produk</a></li>
                        <li><a href="quiz.php">Skin Quiz</a></li>
                        <li><a href="#about">Tentang</a></li>
                    </ul>
                </div>

                <!-- Skin Types Info -->
                <div class="footer-links">
                    <h4>Jenis Kulit</h4>
                    <ul>
                        <li><a href="index.php#products?skin_type=Oily">Kulit Berminyak</a></li>
                        <li><a href="index.php#products?skin_type=Dry">Kulit Kering</a></li>
                        <li><a href="index.php#products?skin_type=Combination">Kulit Kombinasi</a></li>
                        <li><a href="index.php#products?skin_type=Sensitive">Kulit Sensitif</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-contact">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-envelope"></i> info@skincarerec.com</p>
                    <p><i class="fas fa-phone"></i> +62 123 4567 890</p>
                    <p><i class="fas fa-map-marker-alt"></i> Indonesia</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> SkincareRec. All rights reserved.</p>
                <p>Dibuat untuk Skripsi - Sistem Rekomendasi Skincare</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>

