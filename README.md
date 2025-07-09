# 📦 Panduan Menjalankan Project Joomla 5 & Web Furniture E-Commerce

Dokumen ini berisi panduan untuk menjalankan dua proyek berikut di komputer lokal:

1. ✅ Website Joomla 5 ( project tips perawatan)
2. ✅ Website E-Commerce Furniture (dibuat dengan HTML,CSS,JS,BOOTSTRAP,PHP dan MySQL)

## 🔧 Persiapan Awal (Wajib)

### 1. Install Software Berikut:

- **XAMPP** atau **Laragon** (untuk menjalankan Apache + MySQL)
- Web browser (Chrome, Firefox, dsb)
- Teks editor (Visual Studio Code atau Notepad++)

### 2. Struktur Folder:

Letakkan kedua project di folder `htdocs` (jika menggunakan XAMPP):

## 🧩 Menjalankan Joomla 5 (`joomla_project`)

### Langkah-langkah:

1. **Salin folder** `joomla_project` ke dalam `htdocs`
2. **Salin file database** ( `joomla.sql`) ke PHPMyAdmin
3. Buka `http://localhost/phpmyadmin`, buat database baru ( `joomla_db`)
4. **Import** file `joomla_db.sql` ke database tersebut
5. Edit file `configuration.php` di folder Joomla Anda:
   ```php
   public $host = 'localhost';
   public $user = 'root';
   public $password = '';
   public $db = 'joomla_db'; // sesuaikan
   public $live_site = 'http://localhost/joomla_project';
   ```
6. jalankan di browser = `http://localhost/joomla_project`

## 🛒 Menjalankan Web Furniture E-Commerce (furniture_ecommerce)

## Langkah-langkah:

1. Salin folder furniture_ecommerce ke dalam htdocs
2. Salin file database ( furniture.sql) ke PHPMyAdmin
3. Buat database baru, furniture_db
4. Import furniture.sql ke database tersebut
5. Buka file koneksi.php dan sesuaikan:

   $conn = new mysqli("localhost", "root", "", "furniture_db");
   Jalankan di browser: http://localhost/furniture_ecommerce

## 🔗 Navigasi Terintegrasi

Jika ada navigasi yang saling terhubung antar dua project, pastikan:

- Menggunakan URL absolut: http://localhost/furniture_ecommerce/...
- Atau menggunakan link <a href="/furniture_ecommerce/index.php">

## 📝 Catatan Tambahan

- Default login Joomla:
  Username: admin
  Password: admin1234567

- Default login admin e-commerce:
  - admin:
    Username: admin
    Password: admin123
  - user:
    Username: user
    Password: user123

## 📂 File yang Wajib Dibawa

## Jenis Nama File/Folder

- Folder Joomla: joomla_project/
- DB Joomla: joomla.sql
- Folder Web: furniture_ecommerce/
- DB Web: furniture.sql
"# Web-ecomers-furniture" 
