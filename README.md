d# Skincare Recommendation Website

Sistem Rekomendasi Skincare untuk Skripsi

## 📋 Deskripsi

Website ini adalah sistem rekomendasi skincare berbasis web yang membantu pengguna menemukan produk skincare yang sesuai dengan jenis kulit dan masalah kulit mereka. Dibuat menggunakan PHP Native, HTML, CSS, dan JavaScript.

## 🛠️ Teknologi yang Digunakan

- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Backend:** PHP (Native)
- **Database:** MySQL
- **Styling:** Custom CSS dengan desain modern

## 📁 Struktur Folder

```
skincare-recommendation/
├── assets/
│   ├── css/
│   │   └── style.css          # Stylesheet lengkap
│   ├── js/
│   │   └── script.js          # JavaScript interaktif
│   └── images/                 # Folder untuk gambar
├── config/
│   └── database.php           # Konfigurasi database
├── includes/
│   ├── header.php             # Template header
│   └── footer.php             # Template footer
├── index.php                  # Halaman utama
├── search.php                 # Handler pencarian/filter
├── quiz.php                   # Skin Quiz
├── product.php                # Detail produk
└── database.sql               # Schema & data dummy
```

## 🚀 Cara Instalasi

### 1. Persiapan Database

1. Pastikan XAMPP/WAMP/Laragon sudah terinstall
2. Buka phpMyAdmin (http://localhost/phpmyadmin)
3. Buat database baru dengan nama: `skincare_db`
4. Import file `database.sql` ke dalam database tersebut

### 2. Konfigurasi Database

Buka file `config/database.php` dan sesuaikan jika diperlukan:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');      // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'skincare_db');
```

### 3. Menjalankan Aplikasi

#### Cara A: XAMPP
1. Copy folder `skincare-recommendation` ke `htdocs`
2. Buka browser, akses: `http://localhost/skincare-recommendation`

#### Cara B: PHP Built-in Server
```bash
cd skincare-recommendation
php -S localhost:8000
```
Buka browser, akses: `http://localhost:8000`

## ✨ Fitur Utama

### 1. Halaman Utama
- Hero section menarik dengan gradient
- Filter produk multi-kriteria
- Tampilan grid produk yang responsive
- Loading animation saat filter

### 2. Filter Produk
- **Harga:** Range minimum dan maksimum
- **Umur:** 10-50+ tahun
- **Jenis Kulit:** Oily, Dry, Combination, Sensitive, Normal
- **Masalah Kulit:** Acne, Dark Spot, Aging, Dehydrated

### 3. Skin Quiz
- Quiz interaktif dengan 3 pertanyaan
- Rekomendasi otomatis berbasis jawaban
- Tampilan hasil yang menarik

### 4. Detail Produk
- Informasi lengkap produk
- Rating dan review
- Spesifikasi (jenis kulit, target usia, ingredients)
- Produk terkait

## 📊 Data Dummy

Website ini menyediakan 15 data dummy produk skincare:

1. CeraVe Hydrating Cleanser
2. The Ordinary Niacinamide 10%
3. Laneige Water Sleeping Mask
4. Sk-II Facial Treatment Essence
5. La Roche-Posay Effaclar Duo
6. Cetaphil Moisturizing Cream
7. Paula's Choice BHA Exfoliant
8. Cosrx Advanced Snail Mucin
9. Neutrogena Rapid Clear
10. Olay Regenerist Serum
11. Vichy Mineralizing Water
12. Innisfree Green Tea Seed Hyaluronic
13. Avene Cleanance Comedomed
14. Hada Labo Gokujyun Premium
15. Some By Mi Yuja Niacinamide

## 🎨 Desain

- **Primary Color:** #6C63FF
- **Secondary Color:** #FF6584
- **Font:** Poppins (Google Fonts)
- **Responsive:** Mobile, Tablet, Desktop
- **Animasi:** Hover effects, transitions

## 📝 Catatan untuk Skripsi

### Kelebihan Sistem:
1. UI/UX yang modern dan responsif
2. Sistem filter yang lengkap
3. Skin Quiz untuk rekomendasi personal
4. Kode yang terdokumentasi dengan baik
5. Menggunakan database MySQL

### Saran Pengembangan:
1. Menambahkan fitur login/register user
2. Menambahkan fitur keranjang belanja
3. Mengintegrasikan payment gateway
4. Menambahkan fitur review dari user
5. Menggunakan framework (Laravel/CodeIgniter)

## 📄 Lisensi

Dibuat untuk keperluan skripsi.

## 👨‍🎓 Info Pembuat

- **Project:** Skripsi Sistem Rekomendasi Skincare
- **Teknologi:** PHP Native, MySQL, HTML, CSS, JavaScript
- **Tahun:** 2024/2025

