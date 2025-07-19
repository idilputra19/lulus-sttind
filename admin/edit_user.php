<?php
session_start();
include('../includes/koneksi.php');

// Hanya admin yang boleh akses
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// === FUNGSI: Ambil data pengguna (AJAX) ===
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'"));
    echo json_encode($user);
    exit();
}

// === FUNGSI: Proses update data pengguna ===
if (isset($_POST['update_user'])) {
    $id          = $_POST['id'];
    $username    = $_POST['username'];
    $nama_lengkap= $_POST['nama_lengkap'];
    $role        = $_POST['role'];
    $kode_mapel  = $_POST['kode_mapel'] ?? null;
    $kelas_wali  = $_POST['kelas_wali'] ?? null;

    $query = "UPDATE users SET 
                username='$username', 
                nama_lengkap='$nama_lengkap', 
                role='$role',
                kode_mapel=" . ($kode_mapel ? "'$kode_mapel'" : "NULL") . ", 
                kelas_wali=" . ($kelas_wali ? "'$kelas_wali'" : "NULL") . " 
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['msg'] = 'Data pengguna berhasil diperbarui.';
    } else {
        $_SESSION['msg'] = 'Terjadi kesalahan: ' . mysqli_error($conn);
    }

    header('Location: data_user.php');
    exit();
}
?>
