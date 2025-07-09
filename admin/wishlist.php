<?php
session_start();
require '../koneksi.php';

// Hapus data jika ada permintaan delete
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);
  if ($conn->query("DELETE FROM kontak WHERE id = $id") === TRUE) {
    $_SESSION["alert"] = [
      "type" => "success",
      "message" => "Pesan berhasil dihapus!"
    ];
  } else {
    $_SESSION["alert"] = [
      "type" => "error",
      "message" => "Gagal menghapus pesan!"
    ];
  }
  header("Location: wishlist.php");
  exit();
}

// Ambil semua data dari tabel kontak
$result = $conn->query("SELECT * FROM kontak ORDER BY id DESC");

// Alert jika ada
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
  <title>Admin - Wishlist Kontak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-person-lines-fill"></i> Furniture Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="wishlist.php">Wishlist</a></li>
          <li class="nav-item"><a class="nav-link" href="promo/promo.php">Promo</a></li>
          <li class="nav-item"><a class="nav-link" href="ulasan_admin.php">Ulasan</a></li>
          <li class="nav-item"><a class="nav-link" href="detail_pesanan.php">Pesanan</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Container -->
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold"><i class="bi bi-chat-dots-fill text-primary"></i> Pesan dari Form Kontak</h2>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 'sukses'): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Pesan berhasil dihapus.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <div class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="list-group-item list-group-item-action mb-2 shadow-sm rounded">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1 text-primary"><?= htmlspecialchars($row['nama']) ?></h5>
              <a href="wishlist.php?hapus=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                <i class="bi bi-trash3-fill"></i> Hapus
              </a>
            </div>
            <p class="mb-1"><strong>Email:</strong> <em><?= htmlspecialchars($row['email']) ?></em></p>
            <p class="text-muted"><strong>Pesan:</strong> <?= nl2br(htmlspecialchars($row['pesan'])) ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> Belum ada pesan yang masuk.
      </div>
    <?php endif; ?>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-auto shadow-lg">
    &copy; 2025 Furniture E-Commerce Admin
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($alert)) echo $alert; ?>
</body>

</html>