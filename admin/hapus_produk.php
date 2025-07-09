<?php
session_start();
require '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $hapus = $conn->query("DELETE FROM produk WHERE id = $id");

    if ($hapus) {
        $_SESSION['alert'] = [
            "type" => "success",
            "message" => "Produk berhasil dihapus!"
        ];
    } else {
        $_SESSION['alert'] = [
            "type" => "error",
            "message" => "Gagal menghapus produk!"
        ];
    }

    header("Location: index.php"); 
    exit();
}
