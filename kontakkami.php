<?php
require 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $conn->real_escape_string($_POST["name"]);
  $email = $conn->real_escape_string($_POST["email"]);
  $message = $conn->real_escape_string($_POST["message"]);

  $sql = "INSERT INTO kontak (nama, email, pesan) VALUES ('$name', '$email', '$message')";

  if ($conn->query($sql) === TRUE) {
    $_SESSION["alert"] = [
      "type" => "success",
      "name" => $name
    ];
  } else {
    $_SESSION["alert"] = [
      "type" => "error"
    ];
  }

  header("Location: kontakkami.php");
  exit();
}

$alert = "";
if (isset($_SESSION["alert"])) {
  if ($_SESSION["alert"]["type"] === "success") {
    $name = $_SESSION["alert"]["name"];
    $alert = "
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Pesan Terkirim!',
          text: 'Terima kasih, $name. Kami akan segera menghubungi Anda.',
          confirmButtonColor: '#3085d6'
        });
      });
    </script>";
  } else {
    $alert = "
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Pesan tidak dapat dikirim. Silakan coba lagi.',
          confirmButtonColor: '#d33'
        });
      });
    </script>";
  }
  unset($_SESSION["alert"]);
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontak - Furniture E-Commerce</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Warna latar belakang keseluruhan */
    body {
      background-color: #f9f6f1;
    }

    /* Area konten utama */
    .content {
      max-width: 960px;
      margin: 3rem auto;
      padding: 2rem 2.5rem;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    /* Elemen formulir */
    form .form-label {
      font-weight: 600;
    }

    form .form-control,
    form .form-select {
      border-radius: 0.5rem;
      box-shadow: none;
      border: 1px solid #ccc;
      transition: border-color 0.3s ease;
    }

    form .form-control:focus,
    form .form-select:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
    }

    /* Tombol submit */
    form button[type="submit"] {
      margin-top: 1rem;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      background-color: #007bff;
      border: none;
      color: white;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    form button[type="submit"]:hover {
      background-color: #0056b3;
    }

    main {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 16px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    main h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #333;
    }

    main p {
      color: #555;
    }

    main form {
      margin-top: 1.5rem;
    }

    main .form-label {
      font-weight: 600;
      color: #444;
    }

    main .btn-primary {
      background-color: #4a90e2;
      border-color: #4a90e2;
      transition: all 0.3s ease;
    }

    main .btn-primary:hover {
      background-color: #357ab8;
      border-color: #357ab8;
    }
  </style>
</head>

<body>
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
          <li class="nav-item"><a class="nav-link active" href="kontakkami.php">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="keranjang/keranjang.php">Keranjang</a></li>
          <li class="nav-item"><a class="nav-link" href="http://localhost/project_joomla">Tips Perawatan (new)</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="content container-fluid my-5 px-4">
    <main>
      <h2>Kontak Kami</h2>
      <p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui formulir di bawah ini:</p>
      <form action="kontakkami.php" method="post">
        <div class="mb-3">
          <label for="name" class="form-label">Nama:</label>
          <input type="text" id="name" name="name" class="form-control" required />
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" id="email" name="email" class="form-control" required />
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Pesan:</label>
          <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
      </form>

      <hr class="my-4" />
      <h3>Informasi Kontak</h3>
      <p>Email: furniturejaya@gmaiil.com</p>
      <p>Telepon: +62 86245373687</p>
    </main>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 Furniture E-Commerce</p>
    </div>
  </footer>

  <?= $alert ?>
</body>

</html>