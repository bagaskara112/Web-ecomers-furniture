<?php
$pesanan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Furniture E-Commerce</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .success-message {
            text-align: center;
            padding: 2rem;
            background-color: #e6ffed;
            border: 1px solid #a1e8af;
            border-radius: 8px;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="site-header">
            <h1>Pesanan Berhasil!</h1>
        </header>

        <main>
            <div class="success-message">
                <h2>Terima kasih telah berbelanja di Furniture E-Commerce!</h2>
                <p>Pesanan Anda telah berhasil dikonfirmasi.</p>
                <p>Kami akan segera memproses dan mengirimkan barang ke alamat Anda.</p>
                <p><strong>ID Pesanan:</strong> <?= htmlspecialchars("ORD" . str_pad($pesanan_id, 6, "0", STR_PAD_LEFT)) ?></p>
                <br>
                <a href="../beranda.html"><button>Kembali ke Beranda</button></a>
            </div>
        </main>
    </div>

    <footer class="site-footer">
        <p>&copy; 2025 Furniture E-Commerce</p>
    </footer>
</body>
</html>
