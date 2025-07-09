<?php
$host = "localhost";
$user = "root";
$password = ""; // default XAMPP
$database = "furniture_db";

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
