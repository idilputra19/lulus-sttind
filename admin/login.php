<?php
session_start();
include('../includes/koneksi.php'); // Pastikan file ini mendefinisikan $conn

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Siapkan query untuk menghindari SQL Injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password); // password polos, tanpa hash
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Simpan informasi user ke session
        $_SESSION['id_user'] = $user['id']; // atau sesuaikan dengan nama kolom
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['nis'] = $user['nis'] ?? null;

        if ($user['role'] == 'guru_mapel') {
            $_SESSION['kode_mapel'] = $user['kode_mapel'] ?? null;
        }

        if ($user['role'] == 'wali_kelas') {
            $_SESSION['kelas_wali'] = $user['kelas_wali'] ?? null;
        }

        // Arahkan pengguna ke dashboard masing-masing
        switch ($user['role']) {
            case 'admin':
                header('Location: index.php');
                break;
            case 'guru_mapel':
                header('Location: ../guru_mapel/guru_mapel_dashboard.php');
                break;
            case 'wali_kelas':
                header('Location: ../wali_kelas/wali_kelas_dashboard.php');
                break;
            case 'siswa':
                header('Location: ../siswa/siswa_dashboard.php');
                break;
            default:
                echo "Peran pengguna tidak dikenal!";
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 w-75 w-md-50 w-lg-25 shadow-lg">
            <h3 class="text-center mb-4 text-gray-800">Login Aplikasi Kelulusan</h3>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
