<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['nis'])) {
    $nis = $_GET['nis'];

    // Mengambil data siswa berdasarkan NIS
    $sql = "SELECT * FROM siswa WHERE nis = '$nis'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

// Menangani aksi update data siswa
if (isset($_POST['update_siswa'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $sql = "UPDATE siswa SET nama = '$nama', kelas = '$kelas', tahun_ajaran = '$tahun_ajaran' WHERE nis = '$nis'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Data siswa berhasil diperbarui!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
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
        <h3 class="text-center mb-4 text-gray-800">Edit Data Siswa</h3>
        <form method="POST" action="edit_siswa.php">
            <input type="hidden" name="nis" value="<?php echo $row['nis']; ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Siswa</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $row['kelas']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo $row['tahun_ajaran']; ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" name="update_siswa" class="btn btn-success w-100">Update Data</button>
            </div>
        </form>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
