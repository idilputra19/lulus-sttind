<?php
include('../includes/koneksi.php');

if (isset($_POST['edit_mapel'])) {
    $kode_mapel = $_POST['kode_mapel'];
    $nama_mapel = $_POST['nama_mapel'];

    // Pastikan query ini benar
    $sql = "UPDATE mapel SET nama_mapel = '$nama_mapel' WHERE kode_mapel = '$kode_mapel'";

    if (mysqli_query($conn, $sql)) {
        // Redirect ke halaman utama setelah sukses update
        header("Location: data_mapel.php");
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}
?>
