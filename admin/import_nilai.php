<?php
session_start();
include('../includes/koneksi.php');
include('../vendor/autoload.php'); // Untuk menggunakan PhpSpreadsheet

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['import'])) {
    $file = $_FILES['file_import']['tmp_name'];

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    foreach ($data as $row) {
        $nis = $row[0];
        $kode_mapel = $row[1];
        $smt1 = $row[2];
        $smt2 = $row[3];
        $smt3 = $row[4];
        $smt4 = $row[5];
        $smt5 = $row[6];
        $uas = $row[7];
        $tahun_ajaran = $row[8];
        $kkm = $row[9];

        $sql = "INSERT INTO nilai (nis, kode_mapel, smt1, smt2, smt3, smt4, smt5, uas, tahun_ajaran, kkm) 
                VALUES ('$nis', '$kode_mapel', '$smt1', '$smt2', '$smt3', '$smt4', '$smt5', '$uas', '$tahun_ajaran', '$kkm')";
        mysqli_query($conn, $sql);
    }
    echo "<div class='alert alert-success'>Nilai berhasil diimpor!</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Impor Nilai Siswa</h3>
        <form method="POST" enctype="multipart/form-data" action="import_nilai.php">
            <div class="mb-3">
                <label for="file_import" class="form-label">Pilih File Excel</label>
                <input type="file" class="form-control" id="file_import" name="file_import" required>
            </div>
            <div class="text-center">
                <button type="submit" name="import" class="btn btn-success w-100">Impor Nilai</button>
            </div>
        </form>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
