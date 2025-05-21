<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Laporan Pengumpulan
$totalMuzakki = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_KK FROM bayarzakat"));
$totalJiwa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_tanggungan) AS total_jiwa FROM muzakki"))['total_jiwa'];
$totalBeras = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_beras) AS total_beras FROM bayarzakat"))['total_beras'];
$totalUang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bayar_uang) AS total_uang FROM bayarzakat"))['total_uang'];

// Distribusi Warga
$distribusiWarga = mysqli_query($conn, "
  SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras 
  FROM mustahik_warga 
  GROUP BY kategori
");

// Distribusi Lainnya
$distribusiLainnya = mysqli_query($conn, "
  SELECT kategori, COUNT(*) AS jumlah_kk, SUM(hak) AS total_beras 
  FROM mustahik_lainnya 
  GROUP BY kategori
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Zakat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h3 class="text-center mb-4">Laporan Pengumpulan Zakat Fitrah</h3>
  <table class="table table-bordered">
    <tr><th>Total Muzakki</th><td><?= $totalMuzakki ?></td></tr>
    <tr><th>Total Jiwa</th><td><?= $totalJiwa ?></td></tr>
    <tr><th>Total Beras (Kg)</th><td><?= $totalBeras ?> Kg</td></tr>
    <tr><th>Total Uang (Rp)</th><td>Rp <?= number_format($totalUang, 0, ',', '.') ?></td></tr>
  </table>

  <hr class="my-5">

  <h4 class="mb-3">Distribusi Zakat ke Mustahik Warga</h4>
  <table class="table table-bordered">
    <thead class="table-secondary">
      <tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($distribusiWarga)) { ?>
      <tr>
        <td><?= ucfirst($row['kategori']) ?></td>
        <td><?= $row['jumlah_kk'] ?></td>
        <td><?= $row['total_beras'] ?> Kg</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <h4 class="mt-5 mb-3">Distribusi Zakat ke Mustahik Lainnya</h4>
  <table class="table table-bordered">
    <thead class="table-secondary">
      <tr><th>Kategori</th><th>Jumlah KK</th><th>Total Hak Beras (Kg)</th></tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($distribusiLainnya)) { ?>
      <tr>
        <td><?= ucfirst($row['kategori']) ?></td>
        <td><?= $row['jumlah_kk'] ?></td>
        <td><?= $row['total_beras'] ?> Kg</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="text-end mt-4">
    <a href="export_pdf.php" class="btn btn-danger">Export PDF</a>
    <a href="export_word.php" class="btn btn-secondary">Export Word</a>
  </div>
</div>
</body>
</html>
