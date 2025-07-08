<?php
session_start();
include('../includes/koneksi.php');
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil data pengguna berdasarkan ID
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

// Menangani aksi update pengguna
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Menggunakan hashing untuk password
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    $kode_mapel = $_POST['kode_mapel'] ?? null;
    $kelas_wali = $_POST['kelas_wali'] ?? null;

    $sql = "UPDATE users SET username = '$username', password = '$password', nama_lengkap = '$nama_lengkap', role = '$role', kode_mapel = '$kode_mapel', kelas_wali = '$kelas_wali' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Data pengguna berhasil diperbarui!</div>";
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
        <h3 class="text-center mb-4 text-gray-800">Edit Pengguna</h3>
        <form method="POST" action="edit_user.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nama_lengkap']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="guru_mapel" <?php echo $row['role'] == 'guru_mapel' ? 'selected' : ''; ?>>Guru Mapel</option>
                    <option value="wali_kelas" <?php echo $row['role'] == 'wali_kelas' ? 'selected' : ''; ?>>Wali Kelas</option>
                    <option value="siswa" <?php echo $row['role'] == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                </select>
            </div>
            <div class="mb-3" id="kode_mapel_div" style="display: <?php echo $row['role'] == 'guru_mapel' ? 'block' : 'none'; ?>;">
                <label for="kode_mapel" class="form-label">Kode Mata Pelajaran</label>
                <input type="text" class="form-control" id="kode_mapel" name="kode_mapel" value="<?php echo $row['kode_mapel']; ?>">
            </div>
            <div class="mb-3" id="kelas_wali_div" style="display: <?php echo $row['role'] == 'wali_kelas' ? 'block' : 'none'; ?>;">
                <label for="kelas_wali" class="form-label">Kelas Wali</label>
                <input type="text" class="form-control" id="kelas_wali" name="kelas_wali" value="<?php echo $row['kelas_wali']; ?>">
            </div>
            <div class="text-center">
                <button type="submit" name="update_user" class="btn btn-success w-100">Update Pengguna</button>
            </div>
        </form>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
