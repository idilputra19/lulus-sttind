<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['kode_mapel'])) {
    $kode_mapel = $_GET['kode_mapel'];

    // Mengambil data mata pelajaran berdasarkan kode_mapel
    $sql = "SELECT * FROM mapel WHERE kode_mapel = '$kode_mapel'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

// Menangani aksi update mata pelajaran
if (isset($_POST['update_mapel'])) {
    $kode_mapel = $_POST['kode_mapel'];
    $nama_mapel = $_POST['nama_mapel'];

    $sql = "UPDATE mapel SET nama_mapel = '$nama_mapel' WHERE kode_mapel = '$kode_mapel'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Data mata pelajaran berhasil diperbarui!</div>";
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
        <h3 class="text-center mb-4 text-gray-800">Edit Mata Pelajaran</h3>
        <form method="POST" action="edit_mapel.php">
            <input type="hidden" name="kode_mapel" value="<?php echo $row['kode_mapel']; ?>">
            <div class="mb-3">
                <label for="nama_mapel" class="form-label">Nama Mata Pelajaran</label>
                <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" value="<?php echo $row['nama_mapel']; ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" name="update_mapel" class="btn btn-success w-100">Update Mata Pelajaran</button>
            </div>
        </form>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
