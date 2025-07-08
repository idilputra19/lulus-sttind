<?php
$host = 'localhost'; // Atur sesuai dengan host database Anda
$username = 'root';  // Username database
$password = '';      // Password database
$dbname = 'db_kelulusan'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
