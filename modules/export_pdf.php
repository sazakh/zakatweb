<?php
session_start();
include '../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

$mpdf = new Mpdf([
    'format' => 'A4',
    'orientation' => 'P'
]);

// Ambil data (seperti yang kamu lakukan)
$totalMuzakki = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_KK FROM bayarzakat"));
$totalJiwa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_tanggungan) AS total_jiwa FROM muzakki"))['total_jiwa'];
$totalBeras = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_beras) AS total_beras FROM bayarzakat"))['total_beras'];
$totalUang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_uang) AS total_uang FROM bayarzakat"))['total_uang'];

$distribusiWarga = mysqli_query($conn, "
  SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras 
  FROM mustahik_warga 
  GROUP BY kategori
");

$distribusiLainnya = mysqli_query($conn, "
  SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras 
  FROM mustahik_lainnya 
  GROUP BY kategori
");

// Mulai bangun HTML
$html = '
<style>
  body { font-family: sans-serif; }
  h3, h4 { text-align: center; margin-bottom: 10px; }
  table { width: 100%; border-collapse: collapse; margin: 15px 0; }
  th, td { border: 1px solid #000; padding: 8px; text-align: center; }
  th { background-color: #f2f2f2; }
</style>

<h3>Laporan Pengumpulan Zakat Fitrah</h3>
<table>
  <tr><th>Total Muzakki</th><td>' . $totalMuzakki . '</td></tr>
  <tr><th>Total Jiwa</th><td>' . $totalJiwa . '</td></tr>
  <tr><th>Total Beras (Kg)</th><td>' . $totalBeras . ' Kg</td></tr>
  <tr><th>Total Uang (Rp)</th><td>Rp ' . number_format($totalUang, 0, ',', '.') . '</td></tr>
</table>

<h4>Distribusi Zakat ke Mustahik Warga</h4>
<table>
  <tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr>';

while ($row = mysqli_fetch_assoc($distribusiWarga)) {
    $html .= '<tr>
        <td>' . ucfirst($row['kategori']) . '</td>
        <td>' . $row['jumlah_kk'] . '</td>
        <td>' . $row['total_beras'] . ' Kg</td>
    </tr>';
}

$html .= '</table>
<h4>Distribusi Zakat ke Mustahik Lainnya</h4>
<table>
  <tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr>';

while ($row = mysqli_fetch_assoc($distribusiLainnya)) {
    $html .= '<tr>
        <td>' . ucfirst($row['kategori']) . '</td>
        <td>' . $row['jumlah_kk'] . '</td>
        <td>' . $row['total_beras'] . ' Kg</td>
    </tr>';
}

$html .= '</table>';

// Buat PDF
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_zakat.pdf', \Mpdf\Output\Destination::INLINE);
