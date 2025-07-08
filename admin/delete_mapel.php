<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['kode_mapel'])) {
    $kode_mapel = $_GET['kode_mapel'];

    // Hapus data mata pelajaran berdasarkan kode_mapel
    $sql = "DELETE FROM mapel WHERE kode_mapel = '$kode_mapel'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Mata pelajaran berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }

    header('Location: data_mapel.php');
}
?>
