<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'wali_kelas') {
    header('Location: ../login.php');
    exit();
}

$kelas_wali = $_SESSION['kelas_wali']; // Kelas yang diawasi oleh wali kelas

// Mengambil data nilai siswa di kelas yang diawasi
$sql = "SELECT siswa.nis, siswa.nama, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas 
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
        <h3 class="text-center mb-4 text-gray-800">Rekap Nilai Kelas <?php echo $kelas_wali; ?></h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Smt 1</th>
                    <th>Smt 2</th>
                    <th>Smt 3</th>
                    <th>Smt 4</th>
                    <th>Smt 5</th>
                    <th>UAS</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['nis']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['smt1']; ?></td>
                    <td><?php echo $row['smt2']; ?></td>
                    <td><?php echo $row['smt3']; ?></td>
                    <td><?php echo $row['smt4']; ?></td>
                    <td><?php echo $row['smt5']; ?></td>
                    <td><?php echo $row['uas']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
