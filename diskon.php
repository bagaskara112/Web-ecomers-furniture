<?php
require 'koneksi.php';

// Ambil promo aktif
$promo = $conn->query("SELECT promo.*, produk.nama_produk, produk.gambar, produk.harga FROM promo 
                       JOIN produk ON promo.produk_id = produk.id 
                       WHERE promo.status = 'Aktif'");

// Ambil flash sale aktif
$flash = $conn->query("SELECT flash_sale.*, produk.gambar FROM flash_sale 
                       JOIN produk ON flash_sale.produk_id = produk.id 
                       WHERE flash_sale.status = 'Aktif' LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Promo & Flash Sale - Furniture</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <style>
    .card img {
      height: 200px;
      object-fit: cover;
    }

    .badge-promo {
      font-size: 0.9rem;
      background-color: #e60023;
    }

    .countdown {
      font-size: 1.1rem;
      font-weight: bold;
      color: #dc3545;
    }
  </style>
</head>

<body class="bg-light">

  <!-- Header -->
  <div class="container py-4">
    <h2 class="mb-4 text-center">🎉 Promo Menarik Untuk Anda</h2>

    <!-- Flash Sale -->
    <?php if ($flash): ?>
      <div class="card mb-5 shadow">
        <div class="row g-0">
          <div class="col-md-4">
            <img src="<?= $flash['gambar'] ?>" class="img-fluid rounded-start" alt="<?= $flash['nama_produk'] ?>">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h4 class="card-title text-danger">🔥 Flash Sale: <?= $flash['nama_produk'] ?></h4>
              <p class="mb-1">Harga:
                <span class="text-decoration-line-through text-muted">Rp <?= number_format($flash['harga_awal'], 0, ',', '.') ?></span>
                <span class="fw-bold text-success">Rp <?= number_format($flash['harga_diskon'], 0, ',', '.') ?></span>
              </p>
              <p class="countdown" id="countdown">Menghitung waktu...</p>
              <a href="detail/detail_produk.php?id=<?= $flash['produk_id'] ?>" class="btn btn-danger btn-sm mt-2">Beli Sekarang</a>

            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Promo Produk -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php while ($p = $promo->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <img src="<?= $p['gambar'] ?>" class="card-img-top" alt="<?= $p['nama_produk'] ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $p['nama_produk'] ?></h5>
              <p class="mb-1">
                <span class="text-muted text-decoration-line-through">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
                <span class="text-success fw-bold"> <?= is_numeric($p['diskon']) ? 'Rp ' . number_format($p['harga'] - $p['diskon'], 0, ',', '.') : $p['diskon'] ?></span>
              </p>
              <p>
                <span class="badge badge-promo text-white">Promo: <?= $p['nama_promo'] ?></span>
              </p>
              <a href="detail/detail_produk.php?id=<?= $p['produk_id'] ?>" class="btn btn-outline-danger btn-sm w-100">Beli Sekarang</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Countdown Script -->
  <?php if ($flash): ?>
    <script>
      const countdownEl = document.getElementById("countdown");
      const saleEnd = new Date("<?= date('c', strtotime($flash['waktu_berakhir'])) ?>").getTime();

      function updateCountdown() {
        const now = new Date().getTime();
        const distance = saleEnd - now;

        if (distance <= 0) {
          countdownEl.innerText = "Flash Sale telah berakhir!";
          return;
        }

        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((distance / (1000 * 60)) % 60);
        const seconds = Math.floor((distance / 1000) % 60);

        countdownEl.innerText = `Berakhir dalam: ${hours}j ${minutes}m ${seconds}d`;
      }

      updateCountdown();
      setInterval(updateCountdown, 1000);
    </script>
  <?php endif; ?>

</body>

</html>