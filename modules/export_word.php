<?php
include '../config/db.php';

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Laporan_Zakat.doc");

echo "<html><body>";
echo "<h2 style='text-align:center;'>Laporan Zakat Fitrah</h2>";

$totalMuzakki = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_KK FROM bayarzakat"));
$totalJiwa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_tanggungan) AS total_jiwa FROM bayarzakat"))['total_jiwa'];
$totalBeras = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_beras) AS total_beras FROM bayarzakat"))['total_beras'];
$totalUang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_uang) AS total_uang FROM bayarzakat"))['total_uang'];

echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>
    <tr><th>Total Muzakki</th><td>$totalMuzakki</td></tr>
    <tr><th>Total Jiwa</th><td>$totalJiwa</td></tr>
    <tr><th>Total Beras (Kg)</th><td>$totalBeras Kg</td></tr>
    <tr><th>Total Uang (Rp)</th><td>Rp " . number_format($totalUang, 0, ',', '.') . "</td></tr>
</table><br>";

echo "<h4>Distribusi Zakat ke Mustahik Warga</h4>";
echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>
    <thead><tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr></thead><tbody>";

$distribusiWarga = mysqli_query($conn, "SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras FROM mustahik_warga GROUP BY kategori");
while ($row = mysqli_fetch_assoc($distribusiWarga)) {
    echo "<tr><td>" . ucfirst($row['kategori']) . "</td><td>{$row['jumlah_kk']}</td><td>" . number_format($row['total_beras'], 2) . " Kg</td></tr>";
}
echo "</tbody></table>";

echo "<br><h4>Distribusi Zakat ke Mustahik Lainnya</h4>";
echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>
    <thead><tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr></thead><tbody>";

$distribusiLain = mysqli_query($conn, "SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras FROM mustahik_lainnya GROUP BY kategori");
while ($row = mysqli_fetch_assoc($distribusiLain)) {
    echo "<tr><td>" . ucfirst($row['kategori']) . "</td><td>{$row['jumlah_kk']}</td><td>" . number_format($row['total_beras'], 2) . " Kg</td></tr>";
}
echo "</tbody></table>";

echo "</body></html>";
?>
