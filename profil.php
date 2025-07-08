<?php
session_start();
include('includes/koneksi.php');

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Mengambil data pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Menangani update profil pengguna
if (isset($_POST['update_profil'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $password = $_POST['password'];

    // Jika password diubah, hash password terlebih dahulu
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update = "UPDATE users SET nama_lengkap = '$nama_lengkap', password = '$password' WHERE username = '$username'";
    } else {
        $sql_update = "UPDATE users SET nama_lengkap = '$nama_lengkap' WHERE username = '$username'";
    }

    if (mysqli_query($conn, $sql_update)) {
        echo "<div class='alert alert-success'>Profil berhasil diperbarui!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Pengaturan Profil</h3>

        <form method="POST" action="profil.php">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $user['nama_lengkap']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru (opsional)">
            </div>
            <div class="text-center">
                <button type="submit" name="update_profil" class="btn btn-success w-100">Update Profil</button>
            </div>
        </form>
    </div>

    <?php include('template_foot.php'); ?>
</body>
</html>
