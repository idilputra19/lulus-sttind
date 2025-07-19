<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('add/header.php'); ?>
        <?php include('add/footer.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Dashboard Admin</h3>

        <div class="row">
            <div class="col-md-3">
                <a href="data_siswa.php" class="btn btn-primary w-100 mb-3">Kelola Data Siswa</a>
            </div>
            <div class="col-md-3">
                <a href="data_user.php" class="btn btn-primary w-100 mb-3">Kelola Data Pengguna</a>
            </div>
            <div class="col-md-3">
                <a href="data_mapel.php" class="btn btn-primary w-100 mb-3">Kelola Mata Pelajaran</a>
            </div>
            <div class="col-md-3">
                <a href="laporan_kelulusan.php" class="btn btn-primary w-100 mb-3">Laporan Kelulusan</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <a href="rekap_kelas_mapel.php" class="btn btn-primary w-100 mb-3">Rekap Nilai Mata Pelajaran</a>
            </div>
            <div class="col-md-3">
                <a href="laporan_rekapitulasi.php" class="btn btn-primary w-100 mb-3">Rekap Nilai Siswa</a>
            </div>
            <div class="col-md-3">
                <a href="settings.php" class="btn btn-primary w-100 mb-3">Pengaturan Sistem</a>
            </div>
            <div class="col-md-3">
                <a href="../logout.php" class="btn btn-danger w-100 mb-3">Logout</a>
            </div>
        </div>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
