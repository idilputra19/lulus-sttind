<?php
require __DIR__ . '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include('../../includes/koneksi.php');

// Aktifkan debugging jika perlu
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

$cari = $_GET['cari'] ?? '';

// Query data
$sql = "SELECT * FROM siswa";
if (!empty($cari)) {
    $sql .= " WHERE nama LIKE '%$cari%' OR nis LIKE '%$cari%'";
}
$result = mysqli_query($koneksi, $sql);

// Bangun HTML untuk isi PDF
$html = '
<h2 style="text-align:center;">Daftar Kelulusan</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Nilai</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>';
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $row['nis'] . '</td>
        <td>' . $row['nama'] . '</td>
        <td>' . $row['nilai'] . '</td>
        <td>' . $row['keterangan'] . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Inisialisasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF ke browser
$dompdf->stream("daftar_kelulusan.pdf", array("Attachment" => false)); // true untuk download langsung
exit;
