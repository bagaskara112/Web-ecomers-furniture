<?php
session_start(); // WAJIB
require '../koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  die("ID produk tidak ditemukan.");
}

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
  die("Produk tidak ditemukan.");
}

// Proses form jika POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $conn->real_escape_string($_POST['nama']);
  $harga = floatval($_POST['harga']);
  $deskripsi = $conn->real_escape_string($_POST['deskripsi']);

  // Gambar
  $gambar_path = $produk['gambar'];
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $gambar_name = basename($_FILES['gambar']['name']);
    $gambar_path = '../images/' . time() . "_" . $gambar_name;
    move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_path);
  }

  // Update ke DB
  $update_stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, deskripsi=?, gambar=? WHERE id=?");
  $update_stmt->bind_param("sdssi", $nama, $harga, $deskripsi, $gambar_path, $id);

  if ($update_stmt->execute()) {
    $_SESSION["alert"] = [
      "type" => "success",
      "message" => "Produk berhasil diedit!"
    ];
  } else {
    $_SESSION["alert"] = [
      "type" => "error",
      "message" => "Gagal mengedit produk. Coba lagi."
    ];
  }

  header("Location: index.php"); // redirect setelah submit
  exit();
}

// Cek apakah ada alert
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
  <title>Edit Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">
  <h2>Edit Produk</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Nama Produk</label>
      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Harga (Rp)</label>
      <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($produk['harga']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Gambar Produk</label><br>
      <?php if (!empty($produk['gambar'])): ?>
        <img src="<?= $produk['gambar'] ?>" alt="images" style="max-width:150px;"><br><br>
      <?php endif; ?>
      <input type="file" name="gambar" class="form-control">
      <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
    </div>

    <button type="submit" class="btn btn-primary">Update Produk</button>
    <a href="admin_produk.php" class="btn btn-secondary">Batal</a>
  </form>

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($alert)) echo $alert; ?>

</body>

</html>