<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Menangani aksi tambah mata pelajaran
if (isset($_POST['add_mapel'])) {
    $kode_mapel = $_POST['kode_mapel'];
    $nama_mapel = $_POST['nama_mapel'];

    $sql = "INSERT INTO mapel (kode_mapel, nama_mapel) VALUES ('$kode_mapel', '$nama_mapel')";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Mata pelajaran berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

// Mengambil data mata pelajaran
$sql = "SELECT * FROM mapel";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Kelola Mata Pelajaran</h3>

        <form method="POST" action="data_mapel.php">
            <div class="mb-3">
                <label for="kode_mapel" class="form-label">Kode Mata Pelajaran</label>
                <input type="text" class="form-control" id="kode_mapel" name="kode_mapel" required>
            </div>
            <div class="mb-3">
                <label for="nama_mapel" class="form-label">Nama Mata Pelajaran</label>
                <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" required>
            </div>
            <div class="text-center">
                <button type="submit" name="add_mapel" class="btn btn-success w-100">Tambah Mata Pelajaran</button>
            </div>
        </form>

        <h4 class="mt-5">Daftar Mata Pelajaran</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Kode Mata Pelajaran</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['kode_mapel']; ?></td>
                    <td><?php echo $row['nama_mapel']; ?></td>
                    <td>
                        <a href="edit_mapel.php?kode_mapel=<?php echo $row['kode_mapel']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_mapel.php?kode_mapel=<?php echo $row['kode_mapel']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
