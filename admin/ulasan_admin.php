<?php
session_start();
require '../koneksi.php';

// Proses penghapusan ulasan (via request dari SweetAlert konfirmasi)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['hapus_id'])) {
  $id = intval($_POST['hapus_id']);
  if ($conn->query("DELETE FROM ulasan WHERE id = $id") === TRUE) {
    $_SESSION["alert"] = [
      "type" => "success",
      "message" => "Ulasan berhasil dihapus!"
    ];
  } else {
    $_SESSION["alert"] = [
      "type" => "error",
      "message" => "Gagal menghapus ulasan."
    ];
  }
  header("Location: ulasan_admin.php");
  exit();
}

// Ambil data ulasan dan produk
$result = $conn->query("SELECT ulasan.*, produk.nama_produk FROM ulasan 
                        JOIN produk ON ulasan.produk_id = produk.id 
                        ORDER BY ulasan.tanggal DESC");

// Alert jika ada
$alert = "";
if (isset($_SESSION["alert"])) {
  $type = $_SESSION["alert"]["type"];
  $message = $_SESSION["alert"]["message"];
  $icon = $type === "success" ? "success" : "error";
  $title = $type === "success" ? "Berhasil" : "Gagal";

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
  <meta charset="UTF-8">
  <title>Manajemen Ulasan - Furniture Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & SweetAlert -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Custom Style -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }

    .page-title {
      font-weight: 600;
      color: #212529;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    table img {
      border-radius: 6px;
      max-height: 60px;
    }

    .table th {
      background-color: #343a40;
      color: white;
    }

    .btn-danger {
      background-color: #dc3545;
    }

    footer {
      font-size: 0.9rem;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Furniture Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
          <li class="nav-item"><a class="nav-link" href="promo/promo.php">Promo</a></li>
          <li class="nav-item"><a class="nav-link active" href="ulasan_admin.php">Ulasan</a></li>
          <li class="nav-item"><a class="nav-link" href="detail_pesanan.php">Pesanan</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="page-title">📢 Manajemen Ulasan Pelanggan</h2>
    </div>

    <div class="card p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="text-center">
            <tr>
              <th>Produk</th>
              <th>Rating</th>
              <th>Komentar</th>
              <th>Gambar</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td class="text-center"><span class="badge bg-warning text-dark"><?= $row['rating'] ?>/5</span></td>
                <td><?= htmlspecialchars($row['komentar']) ?></td>
                <td class="text-center">
                  <?php if ($row['gambar']): ?>
                    <img src="../<?= $row['gambar'] ?>" alt="gambar ulasan">
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-danger" onclick="hapusUlasan(<?= $row['id'] ?>)">
                    Hapus
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Hidden Delete Form -->
  <form id="formHapus" method="POST" style="display:none;">
    <input type="hidden" name="hapus_id" id="hapus_id">
  </form>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; <?= date("Y") ?> Furniture E-Commerce Admin
  </footer>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Delete -->
  <script>
    function hapusUlasan(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Ulasan yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('hapus_id').value = id;
          document.getElementById('formHapus').submit();
        }
      });
    }
  </script>

  <!-- SweetAlert Notification -->
  <?php if (!empty($alert)) echo $alert; ?>

</body>

</html>