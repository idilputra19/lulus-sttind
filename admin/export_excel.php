<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Ambil tahun ajaran dari tabel settings
$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
$tahun_ajaran = $settings['tahun_ajaran'];

// Fitur pencarian dan filter kelas
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

// Query ambil data siswa dan nilai dengan filter kelas
$query = "
    SELECT siswa.nis, siswa.nama, siswa.kelas, nilai.kode_mapel, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas, mapel.nama_mapel
    FROM siswa
    LEFT JOIN nilai ON siswa.nis = nilai.nis
    LEFT JOIN mapel ON nilai.kode_mapel = mapel.kode_mapel
    WHERE siswa.tahun_ajaran = '$tahun_ajaran'
    AND (siswa.nis LIKE '%$cari%' OR siswa.nama LIKE '%$cari%' OR mapel.nama_mapel LIKE '%$cari%')
    " . ($kelas_filter ? "AND siswa.kelas = '$kelas_filter'" : "") . "
    ORDER BY siswa.nama ASC
";

$result = mysqli_query($conn, $query);

// Inisialisasi output CSV
$filename = "Legger_Nilai_Siswa_" . date("Y-m-d_H-i-s") . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Membuka file output CSV
$output = fopen('php://output', 'w');

// Menulis header kolom CSV
fputcsv($output, ['NIS', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'UAS', 'Total Nilai', 'Rata-rata', 'Keterangan']);

// Menulis data siswa ke CSV
$last_nis = ""; // Untuk melacak NIS terakhir
while ($siswa = mysqli_fetch_assoc($result)) {
    if ($last_nis != $siswa['nis']) {
        // Menulis baris data siswa pertama kali
        $last_nis = $siswa['nis'];
        $total_nilai = 0;
        $total_rata = 0; // Total nilai untuk rata-rata
        $total_mapel = 0; // Total mapel yang dihitung untuk rata-rata
        $keterangan = '';

        // Menghitung total nilai dan rata-rata
        $total_nilai += $siswa['smt1'] + $siswa['smt2'] + $siswa['smt3'] + $siswa['smt4'] + $siswa['smt5'] + $siswa['uas'];
        $total_rata += ($siswa['smt1'] + $siswa['smt2'] + $siswa['smt3'] + $siswa['smt4'] + $siswa['smt5'] + $siswa['uas']);
        $total_mapel++;

        // Rata-rata dihitung berdasarkan 12 mapel
        $rata_rata = ($total_mapel > 0) ? $total_rata / (12 * 6) : 0; // 12 mapel dan 6 nilai per mapel (Semester 1-5 dan UAS)
        $keterangan = ($total_nilai >= 5400) ? 'Lulus' : 'Tidak Lulus'; // 5400 adalah nilai total minimal untuk 12 mapel

        // Menulis data siswa ke CSV hanya sekali
        fputcsv($output, [
            $siswa['nis'],
            $siswa['nama'],
            $siswa['kelas'],
            $siswa['nama_mapel'],
            $siswa['smt1'] ?? 'Belum Ditetapkan',
            $siswa['smt2'] ?? 'Belum Ditetapkan',
            $siswa['smt3'] ?? 'Belum Ditetapkan',
            $siswa['smt4'] ?? 'Belum Ditetapkan',
            $siswa['smt5'] ?? 'Belum Ditetapkan',
            $siswa['uas'] ?? 'Belum Ditetapkan',
            $total_nilai,
            number_format($rata_rata, 2),
            $keterangan
        ]);
    } else {
        // Jika siswa sudah ada, hanya menambahkan mata pelajaran dan nilainya
        fputcsv($output, [
            '',
            '',
            '',
            $siswa['nama_mapel'],
            $siswa['smt1'] ?? 'Belum Ditetapkan',
            $siswa['smt2'] ?? 'Belum Ditetapkan',
            $siswa['smt3'] ?? 'Belum Ditetapkan',
            $siswa['smt4'] ?? 'Belum Ditetapkan',
            $siswa['smt5'] ?? 'Belum Ditetapkan',
            $siswa['uas'] ?? 'Belum Ditetapkan',
            '',
            '',
            ''
        ]);
    }
}

// Menutup file output CSV
fclose($output);
exit;
?>
