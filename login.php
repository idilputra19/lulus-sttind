<?php
session_start();
include('includes/koneksi.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna di database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap']; // <-- ini tambahan
        $_SESSION['nis'] = $user['nis']; // Wajib jika kolom nis sudah ada di tabel users
        $nis = $_SESSION['nis'] ?? 'NIS TIDAK DITEMUKAN';

        
        // Menyimpan data pengguna ke sesi
        $_SESSION['user'] = $user;
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Menyimpan role ke sesi

        // Tambahkan ini jika user adalah guru_mapel
        if ($user['role'] == 'guru_mapel') {
            $_SESSION['kode_mapel'] = $user['kode_mapel'];
        }

        // Tambahkan juga untuk wali_kelas jika dibutuhkan
        if ($user['role'] == 'wali_kelas') {
            $_SESSION['kelas_wali'] = $user['kelas_wali'];
        }

        // Debug: Periksa role yang tersimpan di session
        echo 'Role pengguna: ' . $_SESSION['role']; // Debug

        // Arahkan berdasarkan peran pengguna
        if ($user['role'] == 'admin') {
            header('Location: admin/index.php');
            exit; // Pastikan untuk menambahkan exit setelah header
        } elseif ($user['role'] == 'guru_mapel') {
            header('Location: guru_mapel/guru_mapel_dashboard.php');
            exit;
        } elseif ($user['role'] == 'wali_kelas') {
            header('Location: wali_kelas/wali_kelas_dashboard.php');
            exit;
        } elseif ($user['role'] == 'siswa') {
            header('Location: siswa/siswa_dashboard.php');
            exit;
        }
    } else {
        echo "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('template_head.php'); ?>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 w-75 w-md-50 w-lg-25 shadow-lg">
            <h3 class="text-center mb-4 text-gray-800">Login Aplikasi Kelulusan</h3>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('template_foot.php'); ?>
</body>
</html>
