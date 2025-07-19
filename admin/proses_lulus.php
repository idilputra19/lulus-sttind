<?php
$koneksi = new mysqli('localhost', 'root', '', 'db_kelulusan');
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil jumlah mapel total
$jumlah_mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mapel"))['total'];

// Ambil semua siswa
$siswa_result = mysqli_query($koneksi, "SELECT nis FROM siswa");

while ($siswa = mysqli_fetch_assoc($siswa_result)) {
    $nis = $siswa['nis'];

    // Ambil semua nilai siswa
    $nilai_result = mysqli_query($koneksi, "
        SELECT 
            ((smt1 + smt2 + smt3 + smt4 + smt5 + uas) / 6) AS nilai_akhir
        FROM nilai 
        WHERE nis = '$nis'
    ");

    $jumlah_nilai = 0;
    $total_nilai = 0;
    while ($row = mysqli_fetch_assoc($nilai_result)) {
        if ($row['nilai_akhir'] !== null) {
            $jumlah_nilai++;
            $total_nilai += $row['nilai_akhir'];
        }
    }

    // Cek apakah nilai lengkap dan rata-rata cukup
    if ($jumlah_nilai == $jumlah_mapel) {
        $rata_rata = $total_nilai / $jumlah_nilai;
        $status = ($rata_rata >= 80.00) ? 'Lulus' : 'Tidak Lulus';
    } else {
        $status = 'Tidak Lulus'; // Karena belum lengkap nilainya
    }

    // Update status kelulusan di tabel siswa
    mysqli_query($koneksi, "UPDATE siswa SET status_kelulusan = '$status' WHERE nis = '$nis'");
}

echo "✅ Proses kelulusan selesai.";
?>
