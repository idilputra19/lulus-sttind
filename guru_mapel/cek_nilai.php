<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'guru_mapel') {
    header('Location: ../login.php');
    exit();
}

$kode_mapel = $_SESSION['kode_mapel']; // Mendapatkan kode mata pelajaran dari session

// Mengambil data nilai siswa berdasarkan mata pelajaran
$sql = "SELECT siswa.nis, siswa.nama, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas 
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
        <h3 class="text-center mb-4 text-gray-800">Cek Nilai Siswa per Mata Pelajaran</h3>

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
