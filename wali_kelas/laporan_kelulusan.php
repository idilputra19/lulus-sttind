<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'wali_kelas') {
    header('Location: ../login.php');
    exit();
}

$kelas_wali = $_SESSION['kelas_wali']; // Mendapatkan kelas dari session pengguna

// Mengambil data kelulusan siswa berdasarkan kelas yang diawasi
$sql = "SELECT siswa.nis, siswa.nama, siswa.kelas, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas, nilai.kkm, nilai.tahun_ajaran 
        FROM siswa
        JOIN nilai ON siswa.nis = nilai.nis
        WHERE siswa.kelas = '$kelas_wali'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Laporan Kelulusan Siswa Kelas <?php echo $kelas_wali; ?></h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Status Kelulusan</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    // Menghitung kelulusan siswa berdasarkan KKM
                    $kelulusan = "Tidak Lulus";
                    if ($row['smt1'] >= $row['kkm'] && $row['smt2'] >= $row['kkm'] && $row['smt3'] >= $row['kkm'] && 
                        $row['smt4'] >= $row['kkm'] && $row['smt5'] >= $row['kkm'] && $row['uas'] >= $row['kkm']) {
                        $kelulusan = "Lulus";
                    }
                ?>
                <tr>
                    <td><?php echo $row['nis']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['kelas']; ?></td>
                    <td><?php echo $kelulusan; ?></td>
                    <td><?php echo $row['tahun_ajaran']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
