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

// Menghitung kelulusan siswa berdasarkan KKM
$kelulusan = "Tidak Lulus";
if ($row['smt1'] >= $row['kkm'] && $row['smt2'] >= $row['kkm'] && $row['smt3'] >= $row['kkm'] && 
    $row['smt4'] >= $row['kkm'] && $row['smt5'] >= $row['kkm'] && $row['uas'] >= $row['kkm']) {
    $kelulusan = "Lulus";
}

if ($kelulusan == "Lulus") {
    // Mendownload laporan kelulusan
    if (isset($_GET['download'])) {
        // Membuat dokumen PDF menggunakan library atau membuat format lain seperti Excel
        // Bisa menggunakan fpdf atau library lainnya untuk membuat dokumen PDF
        echo "<script>alert('Laporan SKL Anda sedang diproses untuk diunduh');</script>";
        // Redirect atau proses pembuatan PDF
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Surat Keterangan Lulus (SKL)</h3>

        <p>Status Kelulusan Anda: 
            <span class="text-xl <?php echo $kelulusan == 'Lulus' ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $kelulusan; ?>
            </span>
        </p>

        <?php if ($kelulusan == "Lulus") { ?>
            <p class="text-center">
                <a href="?download=true" class="btn btn-success">Unduh SKL</a>
            </p>
        <?php } else { ?>
            <p class="text-center text-danger">Maaf, Anda tidak memenuhi syarat kelulusan.</p>
        <?php } ?>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
