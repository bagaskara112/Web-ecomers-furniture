<?php
require 'koneksi.php';

// Ambil produk yang punya minimal 3 ulasan dengan rating 5
$sql = "
  SELECT p.id, p.nama_produk, p.harga, p.gambar
  FROM produk p
  JOIN ulasan u ON p.id = u.produk_id
  WHERE u.rating = 5
  GROUP BY p.id
  HAVING COUNT(u.id) >= 3
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Top Produk - Furniture E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Furniture E-Commerce</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="beranda.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
          <li class="nav-item"><a class="nav-link" href="topproduk.php">Top Produk</a></li>
          <li class="nav-item"><a class="nav-link" href="ulasan.php">Ulasan</a></li>
          <li class="nav-item"><a class="nav-link" href="tentangkami.html">Tentang Kami</a></li>
          <li class="nav-item"><a class="nav-link" href="kontakkami.php">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="keranjang/keranjang.php">Keranjang</a></li>
          <li class="nav-item">
            <a class="nav-link" href="http://localhost/project_joomla">Tips Perawatan (new)</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="container py-5">
    <h2 class="text-center mb-4">Top Produk Kami</h2>
    <p class="text-center mb-5">Produk berikut mendapatkan banyak ulasan positif dari pelanggan kami.</p>

    <div class="row justify-content-center">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 text-center">
              <img src="<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                <p class="card-text">Harga: Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                <p class="text-warning"><strong>⭐ Ulasan 5 Bintang bulan ini!</strong></p>
                <a href="detail/detail_produk.php?id=<?= $row['id'] ?>" class="btn btn-primary">Detail</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada produk dengan ulasan bintang 5 sebanyak 3 kali atau lebih.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
      <p class="mb-0">&copy; 2025 Furniture E-Commerce</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>