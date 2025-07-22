<?php
// Menghubungkan ke database
include('../includes/koneksi.php');

// Query untuk mendapatkan semua data pengguna yang ada
$query = "SELECT id, password FROM users";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Proses setiap pengguna untuk meng-hash password mereka
    while ($user = mysqli_fetch_assoc($result)) {
        // Hash password dengan password_hash
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

        // Update password yang di-hash ke database
        $update_query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user['id']);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Password untuk pengguna ID " . $user['id'] . " telah berhasil diperbarui ke hash.<br>";
        } else {
            echo "Terjadi kesalahan saat memperbarui password untuk pengguna ID " . $user['id'] . ".<br>";
        }
    }
} else {
    echo "Tidak ada data pengguna untuk di-update.";
}

// Tutup koneksi
mysqli_close($conn);
?>
