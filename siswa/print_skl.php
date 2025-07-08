<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit();
}

$nis = $_SESSION['username']; // Ambil NIS dari username yang sudah login

// Mengambil data siswa dan nilai berdasarkan NIS
$sql = "SELECT * FROM siswa JOIN nilai ON siswa.nis = nilai.nis WHERE siswa.nis = '$nis'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Menilai kelulusan berdasarkan KKM
$kelulusan = "Tidak Lulus";
if ($row['smt1'] >= $row['kkm'] && $row['smt2'] >= $row['kkm'] && $row['smt3'] >= $row['kkm'] && 
    $row['smt4'] >= $row['kkm'] && $row['smt5'] >= $row['kkm'] && $row['uas'] >= $row['kkm']) {
    $kelulusan = "Lulus";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Lulus (SKL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Surat Keterangan Lulus</h3>
        <table class="table table-bordered">
            <tr>
                <td>Nama Siswa</td>
                <td><?php echo $row['nama']; ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td><?php echo $row['kelas']; ?></td>
            </tr>
            <tr>
                <td>Status Kelulusan</td>
                <td><?php echo $kelulusan; ?></td>
            </tr>
        </table>
        <div class="text-center">
            <button onclick="window.print()" class="btn btn-primary">Cetak SKL</button>
        </div>
    </div>
</body>
</html>
