<?php
require '../../koneksi.php';

// Nonaktifkan promo
if (isset($_GET['nonaktifkan'])) {
  $id = intval($_GET['nonaktifkan']);
  $conn->query("UPDATE promo SET status='Nonaktif' WHERE id=$id");
  header("Location: promo.php");
  exit;
}

// Nonaktifkan flash sale
if (isset($_GET['nonaktif_flash'])) {
  $id = intval($_GET['nonaktif_flash']);
  $conn->query("UPDATE flash_sale SET status='Nonaktif' WHERE id=$id");
  header("Location: promo.php");
  exit;
}

// Ambil data promo + produk
$promo = $conn->query("SELECT promo.*, produk.nama_produk FROM promo 
                       JOIN produk ON promo.produk_id = produk.id
                       WHERE promo.status = 'Aktif'");


// Ambil flash sale aktif
$flash = $conn->query("SELECT * FROM flash_sale WHERE status='Aktif' LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Furniture</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-header">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Furniture Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../wishlist.php">Wishlist</a></li>
          <li class="nav-item"><a class="nav-link active" href="promo.php">Promo</a></li>
          <li class="nav-item"><a class="nav-link" href="../ulasan_admin.php">Ulasan</a></li>
          <li class="nav-item"><a class="nav-link" href="../detail_pesanan.php">Pesanan</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Promo -->
  <div class="container mt-5">
    <h2>Manajemen Promo</h2>
    <a href="tambah_promo.php" class="btn btn-success btn-sm mb-3">+ Tambah Promo</a>

    <!-- Daftar Promo -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">Promo Produk</div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Promo</th>
              <th>Produk</th>
              <th>Diskon</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            while ($row = $promo->fetch_assoc()): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_promo'] ?></td>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['diskon'] ?></td>
                <td>
                  <span class="badge bg-<?= $row['status'] == 'Aktif' ? 'success' : 'secondary' ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
                <td>
                  <a href="edit_promo.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                  <?php if ($row['status'] == 'Aktif'): ?>
                    <a href="?nonaktifkan=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Nonaktifkan</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Flash Sale -->
    <div class="card">
      <div class="card-header bg-danger text-white">Flash Sale Aktif</div>
      <div class="card-body">
        <?php if ($flash): ?>
          <h5 class="card-title"><?= $flash['nama_produk'] ?> - Flash Sale</h5>
          <p>Harga: <strong>Rp <?= number_format($flash['harga_diskon'], 0, ',', '.') ?></strong>
            (dari Rp <?= number_format($flash['harga_awal'], 0, ',', '.') ?>)</p>
          <p id="countdown" class="text-danger fw-bold"></p>
          <a href="edit_flash.php?id=<?= $flash['id'] ?>" class="btn btn-warning btn-sm">Edit Flash Sale</a>
          <a href="?nonaktif_flash=<?= $flash['id'] ?>" class="btn btn-secondary btn-sm">Nonaktifkan</a>
        <?php else: ?>
          <p>Tidak ada flash sale aktif.</p>
          <a href="tambah_flash.php" class="btn btn-danger btn-sm">+ Tambah Flash Sale</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Furniture E-Commerce Admin
  </footer>

  <!-- Countdown Script -->
  <script>
    const countdownEl = document.getElementById("countdown");

    <?php if ($flash): ?>
      // Format ke ISO agar akurat secara timezone
      const saleEnd = new Date("<?= date('c', strtotime($flash['waktu_berakhir'])) ?>").getTime();

      const updateCountdown = () => {
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
      };

      updateCountdown(); // Tampilkan awal
      setInterval(updateCountdown, 1000);
    <?php endif; ?>
  </script>

</body>

</html>