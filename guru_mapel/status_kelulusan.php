<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'guru_mapel') {
    header('Location: ../login.php');
    exit();
}

$kode_mapel = $_SESSION['kode_mapel']; // Mendapatkan kode mata pelajaran dari session

// Mengambil data kelulusan siswa berdasarkan mata pelajaran
$sql = "SELECT siswa.nis, siswa.nama, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas, nilai.kkm 
        FROM siswa
        JOIN nilai ON siswa.nis = nilai.nis
        WHERE nilai.kode_mapel = '$kode_mapel'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Status Kelulusan per Mata Pelajaran</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Status Kelulusan</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    // Menghitung kelulusan berdasarkan KKM
                    $kelulusan = "Tidak Lulus";
                    if ($row['smt1'] >= $row['kkm'] && $row['smt2'] >= $row['kkm'] && $row['smt3'] >= $row['kkm'] && 
                        $row['smt4'] >= $row['kkm'] && $row['smt5'] >= $row['kkm'] && $row['uas'] >= $row['kkm']) {
                        $kelulusan = "Lulus";
                    }
                ?>
                <tr>
                    <td><?php echo $row['nis']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $kelulusan; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
