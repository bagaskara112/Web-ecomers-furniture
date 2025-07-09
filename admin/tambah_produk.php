<?php
session_start();
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $conn->real_escape_string($_POST['nama']);
  $harga = floatval($_POST['harga']);
  $deskripsi = $conn->real_escape_string($_POST['deskripsi']);

  $gambar_path = "";
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $gambar_name = basename($_FILES['gambar']['name']);
    $gambar_path = "images/" . time() . "_" . $gambar_name;
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../" . $gambar_path);
  }

  $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, gambar, deskripsi) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sdss", $nama, $harga, $gambar_path, $deskripsi);

  if ($stmt->execute()) {
    $_SESSION["alert"] = [
      "type" => "success",
      "message" => "Produk berhasil ditambahkan!"
    ];
  } else {
    $_SESSION["alert"] = [
      "type" => "error",
      "message" => "Gagal menambahkan produk. Coba lagi."
    ];
  }

  header("Location: index.php"); // Ganti sesuai file dashboard produk
  exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">
  <h2>Tambah Produk Baru</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="nama" class="form-label">Nama Produk</label>
      <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="harga" class="form-label">Harga (Rp)</label>
      <input type="number" name="harga" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="deskripsi" class="form-label">Deskripsi</label>
      <textarea name="deskripsi" rows="4" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar Produk</label>
      <input type="file" name="gambar" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-success">Simpan Produk</button>
    <a href="admin_produk.php" class="btn btn-secondary">Kembali</a>
  </form>
  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($alert)) echo $alert; ?>
</body>

</html>