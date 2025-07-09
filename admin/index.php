<?php
session_start();
$alert = "";
if (isset($_SESSION["alert"])) {
  $type = $_SESSION["alert"]["type"];
  $message = $_SESSION["alert"]["message"];
  $icon = ($type === "success") ? "success" : "error";
  $title = ($type === "success") ? "Berhasil" : "Gagal";

  $alert = "
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: '$icon',
        title: '$title',
        text: '$message',
        confirmButtonColor: '#3085d6'
      });
    });
  </script>";

  unset($_SESSION["alert"]);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Furniture</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-header">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Furniture Admin</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
          <li class="nav-item">
            <a class="nav-link" href="wishlist.php">Wishlist</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="promo/promo.php">Promo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ulasan_admin.php">Ulasan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="detail_pesanan.php">Pesanan</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="bg-info text-white text-center py-5">
    <h1>Selamat Datang di Admin Panel</h1>
    <p>Kelola produk, kategori, dan layanan pelanggan dengan mudah</p>
  </div>

  <!-- Filter Section -->
  <div class="container my-4">
    <div class="row">
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
        <input
          type="number"
          class="form-control"
          id="filterHarga"
          placeholder="Harga Maksimal" />
      </div>
      <div class="col-md-3">
        <select class="form-select" id="filterRating">
          <option value="">Semua Rating</option>
          <option value="4">4+ Bintang</option>
          <option value="3">3+ Bintang</option>
        </select>
      </div>
      <div class="col-md-3">
        <button class="btn btn-success w-100" onclick="filterProduk()">
          Terapkan Filter
        </button>
      </div>
    </div>
  </div>

  <section class="container my-10">
    <div class="container">
      <h2 id="dashboard">Manajemen Produk</h2>
      <div class="col-md-4  col-lg-3 mb-4">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <button class="btn btn-outline-primary mt-auto">
              <a href="tambah_produk.php" class="btn btn-outline-primary mt-auto">Tambah Produk</a>
            </button>
          </div>
        </div>
      </div>
      <?php
      require '../koneksi.php';
      $result = $conn->query("SELECT produk.*, IFNULL(AVG(ulasan.rating), 0) AS rata_rating FROM produk LEFT JOIN ulasan ON produk.id = ulasan.produk_id GROUP BY produk.id ORDER BY nama_produk ASC");
      ?>

      <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 col-lg-3 mb-4 produk-item"
            data-kategori="<?= strtolower($row['kategori']) ?>"
            data-harga="<?= $row['harga'] ?>"
            data-rating="<?= round($row['rata_rating']) ?>">
            <div class="card h-100">
              <img src="../<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="Produk" style="height:200px; object-fit:cover;">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                <p class="card-text">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
              </div>
            </div>
          </div>

        <?php endwhile; ?>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Furniture E-Commerce Admin
  </footer>

  <script src="js/main.js"></script>
  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($alert)) echo $alert; ?>
  <script src="js/filter.js"></script>

</body>

</html>