<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Menangani aksi tambah pengguna
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Menggunakan hashing untuk password
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    $kode_mapel = $_POST['kode_mapel'] ?? null;
    $kelas_wali = $_POST['kelas_wali'] ?? null;

    $sql = "INSERT INTO users (username, password, nama_lengkap, role, kode_mapel, kelas_wali) 
            VALUES ('$username', '$password', '$nama_lengkap', '$role', '$kode_mapel', '$kelas_wali')";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Pengguna berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

// Mengambil data pengguna
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Kelola Pengguna</h3>
        <!-- Form untuk menambah pengguna -->
        <form method="POST" action="data_user.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="guru_mapel">Guru Mapel</option>
                    <option value="wali_kelas">Wali Kelas</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>
            <div class="mb-3" id="kode_mapel_div" style="display: none;">
                <label for="kode_mapel" class="form-label">Kode Mata Pelajaran</label>
                <input type="text" class="form-control" id="kode_mapel" name="kode_mapel">
            </div>
            <div class="mb-3" id="kelas_wali_div" style="display: none;">
                <label for="kelas_wali" class="form-label">Kelas Wali</label>
                <input type="text" class="form-control" id="kelas_wali" name="kelas_wali">
            </div>
            <div class="text-center">
                <button type="submit" name="add_user" class="btn btn-success w-100">Tambah Pengguna</button>
            </div>
        </form>

        <h4 class="mt-5">Daftar Pengguna</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['nama_lengkap']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>

<script>
// Tampilkan field kode_mapel atau kelas_wali berdasarkan role
document.getElementById('role').addEventListener('change', function() {
    var role = this.value;
    if (role === 'guru_mapel') {
        document.getElementById('kode_mapel_div').style.display = 'block';
        document.getElementById('kelas_wali_div').style.display = 'none';
    } else if (role === 'wali_kelas') {
        document.getElementById('kode_mapel_div').style.display = 'none';
        document.getElementById('kelas_wali_div').style.display = 'block';
    } else {
        document.getElementById('kode_mapel_div').style.display = 'none';
        document.getElementById('kelas_wali_div').style.display = 'none';
    }
});
</script>
