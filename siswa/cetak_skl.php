<?php
require __DIR__ . '/../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include('../includes/koneksi.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$nis = $_GET['nis'] ?? '';
if (!$nis) {
    die("NIS tidak ditemukan.");
}

$sql_siswa = "SELECT * FROM siswa WHERE nis = '$nis'";
$result_siswa = mysqli_query($conn, $sql_siswa);
$data_siswa = mysqli_fetch_assoc($result_siswa);

if (!$data_siswa) {
    die("Data siswa tidak ditemukan.");
}

$sql_nilai = "
    SELECT 
        mapel.nama_mapel,
        nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas,
        nilai.kkm
    FROM mapel
    LEFT JOIN nilai ON mapel.kode_mapel = nilai.kode_mapel AND nilai.nis = '$nis'
";
$result_nilai = mysqli_query($conn, $sql_nilai);
if (!$result_nilai) {
    die("Gagal mengambil data nilai.");
}

$sql_pengaturan = "SELECT * FROM settings LIMIT 1";
$result_pengaturan = mysqli_query($conn, $sql_pengaturan);
$data_pengaturan = mysqli_fetch_assoc($result_pengaturan);

$kop_surat = $data_pengaturan['kop_surat'] ?? '<h2 style="text-align: center;">[KOP SEKOLAH]</h2>';
$kepala_sekolah = $data_pengaturan['nama_kepala_sekolah'] ?? 'Nama Kepala Sekolah';
$nip_kepala_sekolah = $data_pengaturan['nip_kepala_sekolah'] ?? 'NIP. 00000000';
$nama_sekolah = $data_pengaturan['nama_sekolah'] ?? 'Nama Sekolah';
$tahun_ajaran = $data_pengaturan['tahun_ajaran'] ?? 'Tahun Ajaran Tidak Ditemukan';

$total_nilai = 0;
$jumlah_mapel = 0;
$total_lulus = true;
$rows = [];

$html = "
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        margin: 20px;
        color: #333;
    }
    .kop-container {
        text-align: center;
        margin-bottom: 5px;
    }
    .kop-logo img {
        max-width: 100%;
        height: auto;
        margin-top: -30px;
    }
    h3 {
        text-align: center;
        font-size: 16px;
        margin: 10px 0;
    }
    .identitas-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
        text-align: center;
    }
    .identitas-siswa {
        margin: auto;
        width: auto;
        border-collapse: collapse;
        font-size: 11px;
    }
    .identitas-siswa td {
        padding: 4px;
         border: none;
    }
    .identitas-siswa td.label {
        text-align: left;
        font-weight: bold;
        width: 35%;
    }
    .identitas-siswa td.separator {
        width: 5%;
        text-align: center;
    }
    .identitas-siswa td.value {
        text-align: left;
        width: 60%;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
        page-break-inside: avoid;
    }
    th, td {
        padding: 6px 8px;
        border: 1px solid #ccc;
    }
    th {
        background-color: #f5f5f5;
        font-weight: bold;
        text-align: center;
        font-size: 11.5px;
    }
    td {
        font-size: 11px;
    }
    td.text-left {
        text-align: left;
    }
    td.text-center {
        text-align: center;
    }
    td.text-right {
        text-align: right;
    }
    .status-lulus {
        color: green;
        font-weight: bold;
        font-size: 14px;
    }
    .status-tidak-lulus {
        color: red;
        font-weight: bold;
        font-size: 14px;
    }
    .footer {
        text-align: right;
        font-size: 10px;
        margin-top: 30px;
    }
</style>

<div class='kop-container'>
    <div class='kop-logo'>
        <img src='data:image/png;base64," . base64_encode(file_get_contents('kop3.png')) . "' alt='Logo Sekolah'>
    </div>
</div>

<h3>SURAT KETERANGAN KELULUSAN (SKL)</h3>

<p style='text-align: center; margin-top: -8px; font-size: 14px;'>
    Nomor: 421.3/{$nis}/SKL-SMPN4/2025
</p>
<br>


<p style='text-align: justify; font-size: 11px; margin-top: -10px;'>
    Berdasarkan hasil Rapat Dewan Guru SMPN 4 Kota Solok tanggal 2 Juni 2025 dan Kriteria Kelulusan peserta didik yang telah ditetapkan, Kepala SMPN 4 Kota Solok menerangkan bahwa:
</p>

<div class='identitas-wrapper'>
    <table class='identitas-siswa'>
        <tr><td class='label'>Nama Sekolah</td><td class='separator'>:</td><td class='value'>{$nama_sekolah}</td></tr>
        <tr><td class='label'>Tahun Ajaran</td><td class='separator'>:</td><td class='value'>{$tahun_ajaran}</td></tr>
        <tr><td class='label'>Nama</td><td class='separator'>:</td><td class='value'>{$data_siswa['nama']}</td></tr>
        <tr><td class='label'>NIS</td><td class='separator'>:</td><td class='value'>{$nis}</td></tr>
    </table>
</div>

<p style='font-size: 11px; text-align: justify;'>
    Bahwa nama tersebut di atas adalah benar siswa SMPN 4 Kota Solok dan telah melaksanakan Ujian Akhir Sekolah, dengan hasil sebagai berikut:
</p>
";

$no = 1;
while ($row = mysqli_fetch_assoc($result_nilai)) {
    $smt = [$row['smt1'], $row['smt2'], $row['smt3'], $row['smt4'], $row['smt5'], $row['uas']];
    $smt_filled = array_filter($smt, function($n) {
        return $n !== null && $n !== '';
    });

    $rata_rata = count($smt_filled) > 0 ? round(array_sum($smt_filled) / count($smt_filled), 2) : 0;
    $kkm = $row['kkm'] ?? 80;

    if (count($smt_filled) == 6) {
        foreach ($smt as $nilai) {
            if ($nilai < $kkm) {
                $total_lulus = false;
                break;
            }
        }
        $total_nilai += $rata_rata;
        $jumlah_mapel++;
    }

    $rows[] = "
        <tr>
            <td class='text-center'>{$no}</td>
            <td class='text-left'>{$row['nama_mapel']}</td>
            <td class='text-center'>{$kkm}</td>
            <td class='text-center'>{$rata_rata}</td>
        </tr>";
    $no++;
}

$rata_rata_akhir = $jumlah_mapel > 0 ? round($total_nilai / $jumlah_mapel, 2) : 0;
$status_akhir = $total_lulus ? 'Lulus' : 'Tidak Lulus';

$html .= "
<h1><center><span class='" . ($total_lulus ? 'status-lulus' : 'status-tidak-lulus') . "'><strong>{$status_akhir}</strong></span></center></h1>

<table>
    <thead>
        <tr>
            <th style='width:5%;'>No</th>
            <th style='width:55%; text-align: left;'>Mata Pelajaran</th>
            <th style='width:20%;'>KKM</th>
            <th style='width:20%;'>Rata-rata</th>
        </tr>
    </thead>
    <tbody>
        " . implode('', $rows) . "
        <tr>
            <td colspan='2' class='text-right'><strong>Jumlah Nilai</strong></td>
            <td colspan='2' class='text-center'><strong>{$total_nilai}</strong></td>
        </tr>
        <tr>
            <td colspan='2' class='text-right'><strong>Rata-rata Akhir</strong></td>
            <td colspan='2' class='text-center'><strong>{$rata_rata_akhir}</strong></td>
        </tr>
    </tbody>
</table>

<p style='font-size: 11px; text-align: justify;'>
Demikian Surat Keterangan Lulus (SKL) ini dibuat dengan sebenarnya untuk dapat digunakan sebagaimana mestinya menjelang diterbitkannya ijazah yang bersangkutan.
</p>
<table style='width: 100%; border: none; margin-top: 10px; font-size: 11px;'>
    <tr>
        <td style='text-align: left; border: none;'>
            <strong>Total Nilai:</strong> {$total_nilai}<br>
            <strong>Jumlah Mapel:</strong> {$jumlah_mapel}<br>
            <strong>Rata-rata Akhir:</strong> {$rata_rata_akhir}<br>
            <strong>Status Kelulusan:</strong> <span class='" . ($total_lulus ? 'status-lulus' : 'status-tidak-lulus') . "'><strong>{$status_akhir}</strong></span>
        </td>
        <td style='text-align: right; border: none;'>
            Mengetahui,<br>
            Kepala Sekolah<br><br><br>
            <strong><u>{$kepala_sekolah}</u></strong><br>
            NIP. {$nip_kepala_sekolah}
        </td>
    </tr>
</table>

<div class='footer'>
    <p>Generated on " . date('d M Y') . "</p>
</div>
";

// Render PDF
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Laporan_SKL_{$nis}.pdf", ["Attachment" => false]);
exit;
