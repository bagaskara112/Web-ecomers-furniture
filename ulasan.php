<?php
require 'koneksi.php';

$produk_result = $conn->query("SELECT id, nama_produk, gambar FROM produk");

// Proses form jika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $produk_id = $_POST['produk_id'];
  $rating = intval($_POST['star']);
  $komentar = $conn->real_escape_string($_POST['komentar']);

  // Upload gambar jika ada
  $gambar = "";
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $gambar = 'uploads/' . basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
  }

  // Simpan ke database
  $stmt = $conn->prepare("INSERT INTO ulasan (produk_id, rating, komentar, gambar, tanggal) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("iiss", $produk_id, $rating, $komentar, $gambar);
  $stmt->execute();

  // Redirect untuk mencegah duplikasi saat refresh
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Ulasan Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css">
  <style>
    body {
      background-color: var(--color-bg);
    }

    main {
      padding: 40px 0;
    }

    main .form-review {
      flex: 1;
      min-width: 300px;
      position: sticky;
      top: 100px;
      align-self: flex-start;
    }


    .review-section {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
    }

    .review-item {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .form-review,
    .reviews {
      flex: 1;
      min-width: 300px;
    }

    .reviews {
      border-left: 1px solid #dee2e6;
      padding-left: 20px;
    }

    .stars {
      display: flex;
      flex-direction: row-reverse;
      justify-content: flex-end;
    }

    .stars input[type="radio"] {
      display: none;
    }

    .stars label {
      font-size: 24px;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
    }

    .stars input:checked~label,
    .stars label:hover,
    .stars label:hover~label {
      color: gold;
    }

    .review-item {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .review-item img {
      margin-top: 10px;
      max-width: 100px;
      max-height: 100px;
      border-radius: 8px;
      object-fit: cover;
    }

    .review-item small {
      display: block;
      margin-bottom: 5px;
      color: #6c757d;
    }

    @media (max-width: 768px) {
      .review-section {
        flex-direction: column;
      }

      .reviews {
        border-left: none;
        padding-left: 0;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Furniture E-Commerce</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

  <main class="container">
    <div class="review-section">
      <div class="form-review">
        <h2 class="mb-4">Berikan Ulasan Produk</h2>
        <form method="POST" enctype="multipart/form-data">
          <!-- Select Produk -->
          <div class="mb-3">
            <label for="produk_id" class="form-label">Pilih Produk</label>
            <select name="produk_id" id="produkSelect" class="form-select" required onchange="updateGambarProduk()">
              <option value="" disabled selected>-- Pilih Produk --</option>
              <?php while ($produk = $produk_result->fetch_assoc()): ?>
                <option value="<?= $produk['id'] ?>" data-img="<?= htmlspecialchars($produk['gambar']) ?>">
                  <?= htmlspecialchars($produk['nama_produk']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Gambar Produk Preview -->
          <div class="mb-3" id="gambarProdukPreview" style="display:none;">
            <label class="form-label">Gambar Produk yang Dipilih:</label><br>
            <img id="previewImg" src="" style="max-width: 150px; border-radius: 10px;">
          </div>

          <!-- Rating -->
          <div class="mb-3">
            <label class="form-label">Rating</label>
            <div class="stars">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="star" id="star<?= $i ?>" value="<?= $i ?>" required />
                <label for="star<?= $i ?>">&#9733;</label>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Komentar -->
          <div class="mb-3">
            <label class="form-label">Komentar</label>
            <textarea name="komentar" class="form-control" rows="3" required></textarea>
          </div>

          <!-- Upload Gambar -->
          <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
          </div>

          <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
        </form>
      </div>

      <div class="reviews">
        <h3>Ulasan Pengguna</h3>
        <?php
        $result = $conn->query("SELECT ulasan.*, produk.nama_produk FROM ulasan 
                      JOIN produk ON ulasan.produk_id = produk.id 
                      ORDER BY ulasan.tanggal DESC");

        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
            <div class="review-item">
              <strong><?= str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) ?></strong><br>
              <small class="text-muted">Produk: <?= htmlspecialchars($row['nama_produk']) ?></small>
              <p><?= htmlspecialchars($row['komentar']) ?></p>
              <?php if (!empty($row['gambar'])): ?>
                <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Produk">
              <?php endif; ?>
              <small class="text-muted"><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></small>
            </div>
        <?php
          endwhile;
        else:
          echo "<p>Belum ada ulasan.</p>";
        endif;
        ?>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 Furniture E-Commerce</p>
    </div>
  </footer>

  <script>
    function updateGambarProduk() {
      const select = document.getElementById("produkSelect");
      const imgSrc = select.options[select.selectedIndex].getAttribute("data-img");

      const preview = document.getElementById("gambarProdukPreview");
      const previewImg = document.getElementById("previewImg");

      if (imgSrc) {
        previewImg.src = imgSrc;
        preview.style.display = "block";
      } else {
        preview.style.display = "none";
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>