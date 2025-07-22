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
    // Pastikan semua input telah terisi dan aman
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $kode_mapel = isset($_POST['kode_mapel']) ? mysqli_real_escape_string($conn, $_POST['kode_mapel']) : null;
    $kelas_wali = isset($_POST['kelas_wali']) ? mysqli_real_escape_string($conn, $_POST['kelas_wali']) : null;

    // Query untuk update data pengguna
    $query = "UPDATE users SET 
                username='$username', 
                nama_lengkap='$nama_lengkap', 
                role='$role', 
                kode_mapel=" . ($kode_mapel ? "'$kode_mapel'" : "NULL") . ", 
                kelas_wali=" . ($kelas_wali ? "'$kelas_wali'" : "NULL") . " 
              WHERE id='$id'";

    // Eksekusi query dan beri notifikasi
    if (mysqli_query($conn, $query)) {
        $_SESSION['msg'] = 'Data pengguna berhasil diperbarui.';
    } else {
        $_SESSION['msg'] = 'Terjadi kesalahan: ' . mysqli_error($conn);
    }

    header('Location: data_user.php');
    exit();
}
?>
