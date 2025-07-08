<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit();
}

$nis = $_SESSION['username']; // Ambil NIS dari session

// Mengambil data nilai siswa berdasarkan NIS
$sql = "SELECT * FROM nilai WHERE nis = '$nis'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Rekap Nilai Anda</h3>
        
        <table class="table table-bordered">
            <tr>
                <th>Semester 1</th>
                <td><?php echo $row['smt1']; ?></td>
            </tr>
            <tr>
                <th>Semester 2</th>
                <td><?php echo $row['smt2']; ?></td>
            </tr>
            <tr>
                <th>Semester 3</th>
                <td><?php echo $row['smt3']; ?></td>
            </tr>
            <tr>
                <th>Semester 4</th>
                <td><?php echo $row['smt4']; ?></td>
            </tr>
            <tr>
                <th>Semester 5</th>
                <td><?php echo $row['smt5']; ?></td>
            </tr>
            <tr>
                <th>UAS</th>
                <td><?php echo $row['uas']; ?></td>
            </tr>
        </table>

        <h4>Status Kelulusan</h4>
        <?php
        // Menghitung kelulusan siswa berdasarkan KKM
        $kelulusan = "Tidak Lulus";
        if ($row['smt1'] >= $row['kkm'] && $row['smt2'] >= $row['kkm'] && $row['smt3'] >= $row['kkm'] && 
            $row['smt4'] >= $row['kkm'] && $row['smt5'] >= $row['kkm'] && $row['uas'] >= $row['kkm']) {
            $kelulusan = "Lulus";
        }
        ?>
        <p class="text-center <?php echo $kelulusan == 'Lulus' ? 'text-success' : 'text-danger'; ?>"><?php echo $kelulusan; ?></p>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
