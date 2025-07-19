<?php
session_start();

include('../includes/koneksi.php');

// Cek login dan role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login.php");
    exit();
}

$nis = $_SESSION['nis'] ?? null;
if (!$nis) {
    echo "NIS tidak ditemukan di session. Silakan login ulang.";
    session_destroy();
    exit();
}

// Ambil data siswa
$sql_siswa = "SELECT nama FROM siswa WHERE nis = '$nis'";
$result_siswa = mysqli_query($conn, $sql_siswa);
$row_siswa = mysqli_fetch_assoc($result_siswa);
$nama_siswa = $row_siswa['nama'] ?? '-';

// Ambil data nilai
$sql_nilai = "
    SELECT 
        smt1, smt2, smt3, smt4, smt5, uas, kkm
    FROM nilai
    WHERE nis = '$nis'
";
$result_nilai = mysqli_query($conn, $sql_nilai);

// Hitung status kelulusan akhir
$total_lulus = true;
while ($row = mysqli_fetch_assoc($result_nilai)) {
    $smt1 = $row['smt1'] ?? 0;
    $smt2 = $row['smt2'] ?? 0;
    $smt3 = $row['smt3'] ?? 0;
    $smt4 = $row['smt4'] ?? 0;
    $smt5 = $row['smt5'] ?? 0;
    $uas  = $row['uas']  ?? 0;
    $kkm  = $row['kkm']  ?? 80;

    // Cek jika semua nilai belum diisi
    if ($smt1 == 0 && $smt2 == 0 && $smt3 == 0 && $smt4 == 0 && $smt5 == 0 && $uas == 0) {
        continue;
    }

    // Jika salah satu nilai < KKM maka tidak lulus
    if (
        $smt1 < $kkm || $smt2 < $kkm || $smt3 < $kkm ||
        $smt4 < $kkm || $smt5 < $kkm || $uas < $kkm
    ) {
        $total_lulus = false;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Kelulusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center mb-4">Status Kelulusan Siswa</h3>

    <div class="card p-4 shadow-sm">
        <p><strong>Nama:</strong> <?= htmlspecialchars($nama_siswa) ?></p>
        <p><strong>NIS:</strong> <?= htmlspecialchars($nis) ?></p>

        <h5>Status Kelulusan:
            <span class="<?= $total_lulus ? 'text-success' : 'text-danger' ?>">
                <?= $total_lulus ? 'Lulus' : 'Tidak Lulus' ?>
            </span>
        </h5>

        <?php if ($total_lulus): ?>
            <div class="mt-4">
                <a href="cetak_skl.php?nis=<?= urlencode($nis) ?>" target="_blank" class="btn btn-success">
                    Cetak SKL (PDF)
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
