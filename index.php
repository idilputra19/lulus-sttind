<?php
session_start();
include 'koneksi.php'; // pastikan file koneksi ini sesuai dengan konfigurasi Anda

if (isset($_POST['login'])) {
    $nis = $_POST['nis'];

    // Cek apakah NIS ada di tabel siswa
    $query = "SELECT * FROM siswa WHERE nis = '$nis'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['nis'] = $data['nis'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = 'siswa';
        
        // Redirect ke dashboard siswa
        header("Location: siswa/siswa_dashboard.php");
        exit();
    } else {
        $error = "NIS tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Login Siswa</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="nis" class="form-label">Masukkan NIS</label>
            <input type="text" name="nis" id="nis" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>
</body>
</html>
