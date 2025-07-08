<?php
session_start();
if ($_SESSION['role'] != 'guru_mapel') {
    header('Location: ../login.php');
    exit();
}

$kode_mapel = $_SESSION['kode_mapel']; // Mendapatkan kode mata pelajaran dari session



?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Dashboard Guru Mapel</h3>

        <div class="row">
            <div class="col-md-4">
                <a href="input_nilai.php" class="btn btn-primary w-100 mb-3">Input Nilai</a>
            </div>
            <div class="col-md-4">
                <a href="rekap_nilai.php" class="btn btn-primary w-100 mb-3">Rekap Nilai Siswa</a>
            </div>
            <div class="col-md-4">
                <a href="status_kelulusan.php" class="btn btn-primary w-100 mb-3">Status Kelulusan</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a href="laporan_kelulusan.php" class="btn btn-primary w-100 mb-3">Laporan Kelulusan</a>
            </div>
            <div class="col-md-4">
                <a href="cek_nilai.php" class="btn btn-primary w-100 mb-3">Cek Nilai Siswa</a>
            </div>
            <div class="col-md-4">
                <a href="../logout.php" class="btn btn-danger w-100 mb-3">Logout</a>
            </div>
        </div>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>


<script>
    // Function to limit the input range to between 1 and 100
    function validateNilai() {
        var nilaiInput = document.getElementById('nilai');
        var nilai = nilaiInput.value;

        // Check if the nilai is within the range 1-100
        if (nilai < 1) {
            nilaiInput.value = 1; // Set to 1 if less than 1
        } else if (nilai > 100) {
            nilaiInput.value = 100; // Set to 100 if more than 100
        }
    }

    // Add event listener to the input field to call validateNilai when value changes
    window.onload = function () {
        var nilaiInput = document.getElementById('nilai');
        if (nilaiInput) {
            nilaiInput.addEventListener('input', validateNilai);
        }
    }
</script>
