<?php
session_start();
include('../includes/koneksi.php');

$_SESSION['nama'] = $user_data['nama'];  // Misal dari tabel users kolom nama
$_SESSION['role'] = $user_data['role'];
$_SESSION['kelas_wali'] = $user_data['kelas_wali'];

if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$kode_mapel = '';
$result = null;

// Ambil daftar mata pelajaran
$mapel_query = mysqli_query($conn, "SELECT kode_mapel, nama_mapel FROM mapel");

// Jika kode_mapel dikirim, ambil data nilai
if (isset($_GET['kode_mapel'])) {
    $kode_mapel = mysqli_real_escape_string($conn, $_GET['kode_mapel']);

    $sql = "SELECT siswa.nis, siswa.nama, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas, nilai.tahun_ajaran 
            FROM siswa
            JOIN nilai ON siswa.nis = nilai.nis 
            WHERE nilai.kode_mapel = '$kode_mapel'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query error: " . mysqli_error($conn));
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
        <h3 class="text-center mb-4 text-gray-800">Rekap Nilai Mata Pelajaran</h3>

        <form method="get" class="mb-4" action="">
            <div class="row">
                <div class="col-md-6">
                    <label for="kode_mapel">Pilih Mata Pelajaran:</label>
                    <select name="kode_mapel" id="kode_mapel" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php while ($mapel = mysqli_fetch_assoc($mapel_query)) { ?>
                            <option value="<?php echo $mapel['kode_mapel']; ?>" <?php echo ($kode_mapel == $mapel['kode_mapel']) ? 'selected' : ''; ?>>
                                <?php echo $mapel['nama_mapel']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>

        <?php if ($kode_mapel): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Smt 1</th>
                        <th>Smt 2</th>
                        <th>Smt 3</th>
                        <th>Smt 4</th>
                        <th>Smt 5</th>
                        <th>UAS</th>
                        <th>Tahun Ajaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['nis']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['smt1']; ?></td>
                                <td><?php echo $row['smt2']; ?></td>
                                <td><?php echo $row['smt3']; ?></td>
                                <td><?php echo $row['smt4']; ?></td>
                                <td><?php echo $row['smt5']; ?></td>
                                <td><?php echo $row['uas']; ?></td>
                                <td><?php echo $row['tahun_ajaran']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center">Tidak ada data nilai untuk mata pelajaran ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
