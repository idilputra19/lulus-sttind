<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['nis'])) {
    $nis = $_GET['nis'];

    // Hapus data siswa berdasarkan NIS
    $sql = "DELETE FROM siswa WHERE nis = '$nis'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Data siswa berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }

    header('Location: data_siswa.php');
}
?>
