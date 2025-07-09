<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produk - Furniture E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f6f1;
        }

        .content {
            padding: 40px 20px;
        }

        .content h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }

        .category-products {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }

        .product-card {
            width: 250px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            padding: 15px;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-card h4 {
            font-size: 18px;
            margin: 10px 0;
        }

        .product-card p {
            color: #333;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .detail-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #444;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .detail-btn:hover {
            background-color: #222;
        }

        @media (max-width: 768px) {
            .product-card {
                width: 100%;
                max-width: 90%;
            }
        }

        .badge-flash {
            background-color: #e60023;
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .badge-promo {
            background-color: #1e90ff;
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Furniture E-Commerce</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="beranda.html">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="topproduk.php">Top Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ulasan.php">Ulasan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentangkami.html">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kontakkami.php">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang/keranjang.php">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/project_joomla">Tips Perawatan (new)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container content">
        <h2>Produk Kami</h2>
        <div class="row mb-4">
            <div class="col-md-3">
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="sofa">Sofa</option>
                    <option value="meja">Meja</option>
                    <option value="kursi">Kursi</option>
                    <option value="lampu">Lampu</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" id="filterHarga" placeholder="Harga Maksimal">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterRating">
                    <option value="">Semua Rating</option>
                    <option value="4">4+ Bintang</option>
                    <option value="3">3+ Bintang</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="filterProduk()">Terapkan Filter</button>
            </div>
        </div>

        <section id="product-list">
            <div class="category-products">
                <?php
                require 'koneksi.php';

                // Ambil data produk
                $sql = "SELECT 
                                produk.*, 
                                IFNULL(AVG(ulasan.rating), 0) AS rata_rating,
                                promo.nama_promo, promo.diskon,
                                flash_sale.harga_diskon, flash_sale.waktu_berakhir
                                FROM produk 
                                LEFT JOIN ulasan ON produk.id = ulasan.produk_id
                                LEFT JOIN promo ON produk.id = promo.produk_id AND promo.status = 'Aktif'
                                LEFT JOIN flash_sale ON produk.id = flash_sale.produk_id AND flash_sale.status = 'Aktif'
                                GROUP BY produk.id 
                                ORDER BY nama_produk ASC";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card" 
                        data-kategori="' . strtolower($row['kategori']) . '" 
                        data-harga="' . $row['harga'] . '" 
                        data-rating="' . round($row['rata_rating']) . '">';

                        echo '<img src="' . htmlspecialchars($row['gambar']) . '" alt="' . htmlspecialchars($row['alt']) . '">';
                        // Promo Badge
                        if (!empty($row['nama_promo'])) {
                            echo '<span class="badge-promo">Promo: ' . htmlspecialchars($row['nama_promo']) . '</span><br>';
                        }

                        // Flash Sale Badge
                        if (!empty($row['harga_diskon'])) {
                            echo '<span class="badge-flash">Flash Sale</span><br>';
                        }

                        // Nama Produk
                        echo '<h4>' . htmlspecialchars($row['nama_produk']) . '</h4>';

                        // Harga
                        if (!empty($row['harga_diskon'])) {
                            echo '<p><del class="text-muted">Rp ' . number_format($row['harga'], 0, ',', '.') . '</del><br>
                            <span class="text-danger fw-bold">Rp ' . number_format($row['harga_diskon'], 0, ',', '.') . '</span></p>';
                        } elseif (!empty($row['diskon']) && is_numeric($row['diskon'])) {
                            $hargaPromo = $row['harga'] - $row['diskon'];
                            echo '<p><del class="text-muted">Rp ' . number_format($row['harga'], 0, ',', '.') . '</del><br>
                            <span class="text-primary fw-bold">Rp ' . number_format($hargaPromo, 0, ',', '.') . '</span></p>';
                        } else {
                            echo '<p>Harga: Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                        }

                        echo '<a href="detail/detail_produk.php?id=' . $row['id'] . '" class="detail-btn">Lihat Detail</a>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center'>Produk belum tersedia.</p>";
                }


                $conn->close();
                ?>
            </div>
        </section>
    </div>

    <!-- Footer Bootstrap -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Furniture E-Commerce</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/filter.js"></script>

</body>

</html>