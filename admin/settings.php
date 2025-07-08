<?php
session_start();
include('../includes/koneksi.php');

// Cek jika bukan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Proses update tahun ajaran
if (isset($_POST['update_settings'])) {
    $tahun_ajaran = mysqli_real_escape_string($conn, $_POST['tahun_ajaran']);

    // Update ke database
    $sql_update = "UPDATE settings SET tahun_ajaran = '$tahun_ajaran' WHERE id = 1";
    if (mysqli_query($conn, $sql_update)) {
        $message = "<div class='alert alert-success'>Pengaturan berhasil diperbarui!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Terjadi kesalahan saat memperbarui: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data tahun ajaran sekarang
$sql_select = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $sql_select);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Sistem</title>
    <?php include('../template_head.php'); ?>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-gray-800">Pengaturan Sistem</h3>

        <?php if (isset($message)) echo $message; ?>

        <form method="POST" action="settings.php">
            <div class="mb-3">
                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran"
                       value="<?php echo htmlspecialchars($row['tahun_ajaran']); ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" name="update_settings" class="btn btn-success w-100">Update Pengaturan</button>
            </div>
        </form>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
