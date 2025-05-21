<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/login.php");
    exit;
}

$daftarMuzakki = mysqli_query($conn, "SELECT * FROM muzakki");

// Tambah
if (isset($_POST['tambah'])) {
    $nama_KK = $_POST['nama_KK'];
    $jumlah_tanggungan = $_POST['jumlah_tanggungan'];
    $jenis_bayar = $_POST['jenis_bayar'];
    $jumlah_dibayar = $_POST['jumlah_tanggunganyangdibayar'];
    $bayar_beras = ($jenis_bayar == 'beras') ? $_POST['bayar_beras'] : 0;
    $bayar_uang = ($jenis_bayar == 'uang') ? str_replace(".", "", $_POST['bayar_uang']) : 0;

    mysqli_query($conn, "INSERT INTO bayarzakat (nama_KK, jumlah_tanggungan, jenis_bayar, jumlah_tanggunganyangdibayar, bayar_beras, bayar_uang) 
    VALUES ('$nama_KK', '$jumlah_tanggungan', '$jenis_bayar', '$jumlah_dibayar', '$bayar_beras', '$bayar_uang')");

    header("Location: bayarzakat.php");
}

// Edit
if (isset($_POST['edit'])) {
    $id = $_POST['id_zakat'];
    $nama_KK = $_POST['nama_KK'];
    $jumlah_tanggungan = $_POST['jumlah_tanggungan'];
    $jenis_bayar = $_POST['jenis_bayar'];
    $jumlah_dibayar = $_POST['jumlah_tanggunganyangdibayar'];
    $bayar_beras = ($jenis_bayar == 'beras') ? $_POST['bayar_beras'] : 0;
    $bayar_uang = ($jenis_bayar == 'uang') ? str_replace(".", "", $_POST['bayar_uang']) : 0;

    mysqli_query($conn, "UPDATE bayarzakat SET 
        nama_KK='$nama_KK', 
        jumlah_tanggungan='$jumlah_tanggungan', 
        jenis_bayar='$jenis_bayar', 
        jumlah_tanggunganyangdibayar='$jumlah_dibayar', 
        bayar_beras='$bayar_beras', 
        bayar_uang='$bayar_uang' 
        WHERE id_zakat='$id'");

    header("Location: bayarzakat.php");
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM bayarzakat WHERE id_zakat='$id'");
    header("Location: bayarzakat.php");
}

$bayarzakat = mysqli_query($conn, "SELECT * FROM bayarzakat");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pengumpulan Zakat Fitrah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/bayarzakat.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="../pages/dashboard.php">Dashboard</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="../pages/logout.php" onclick="return confirm('Yakin mau logout?')">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h3 class="text-center mb-4">Pengumpulan Zakat Fitrah</h3>

  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Pembayaran</button>
  </div>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Nama KK</th>
        <th>Jumlah Tanggungan</th>
        <th>Jenis Bayar</th>
        <th>Jumlah Dibayar</th>
        <th>Bayar Beras (Kg)</th>
        <th>Bayar Uang (Rp)</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($bayarzakat)) { ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_KK'] ?></td>
        <td><?= $row['jumlah_tanggungan'] ?></td>
        <td><?= ucfirst($row['jenis_bayar']) ?></td>
        <td><?= $row['jumlah_tanggunganyangdibayar'] ?></td>
        <td><?= $row['bayar_beras'] ?></td>
        <td><?= number_format($row['bayar_uang'], 0, ',', '.') ?></td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_zakat'] ?>">Edit</button>
          <a href="bayarzakat.php?hapus=<?= $row['id_zakat'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus data ini?')">Hapus</a>
        </td>
      </tr>

<!-- Modal Edit -->
<div class="modal fade" id="editModal<?= $row['id_zakat'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="id_zakat" value="<?= $row['id_zakat'] ?>">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama KK</label>
            <input type="text" name="nama_KK" class="form-control" value="<?= $row['nama_KK'] ?>" required>
          </div>
          <div class="mb-3">
            <label>Jumlah Tanggungan</label>
            <input type="number" name="jumlah_tanggungan" class="form-control" value="<?= $row['jumlah_tanggungan'] ?>" required>
          </div>
          <div class="mb-3">
            <label>Jenis Bayar</label>
            <select name="jenis_bayar" id="jenisBayar_<?= $row['id_zakat'] ?>" class="form-control" onchange="updateEditPerkiraanBayar(<?= $row['id_zakat'] ?>)">
              <option value="beras" <?= $row['jenis_bayar'] == 'beras' ? 'selected' : '' ?>>Beras</option>
              <option value="uang" <?= $row['jenis_bayar'] == 'uang' ? 'selected' : '' ?>>Uang</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Jumlah Tanggungan yang Dibayar</label>
            <input type="number" name="jumlah_tanggunganyangdibayar" id="jumlahDibayar_<?= $row['id_zakat'] ?>" class="form-control" value="<?= $row['jumlah_tanggunganyangdibayar'] ?>" oninput="updateEditPerkiraanBayar(<?= $row['id_zakat'] ?>)">
          </div>
          <div class="mb-3">
            <label>Bayar Beras (Kg)</label>
            <input type="number" step="0.1" name="bayar_beras" id="bayarBeras_<?= $row['id_zakat'] ?>" class="form-control" value="<?= $row['bayar_beras'] ?>">
          </div>
          <div class="mb-3">
            <label>Bayar Uang (Rp)</label>
            <input type="text" name="bayar_uang" id="bayarUang_<?= $row['id_zakat'] ?>" class="form-control" value="<?= number_format($row['bayar_uang'], 0, ',', '.') ?>" oninput="formatInputRupiah(this)">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" name="edit">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit -->

<?php } ?>
</tbody>
</table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pembayaran Zakat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama KK</label>
            <input type="text" name="nama_KK" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Jumlah Tanggungan</label>
            <input type="number" name="jumlah_tanggungan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Jenis Bayar</label>
            <select name="jenis_bayar" id="jenisBayar" class="form-control" onchange="updatePerkiraanBayar()" required>
              <option value="">- Pilih Jenis Bayar -</option>
              <option value="beras">Beras</option>
              <option value="uang">Uang</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Jumlah Tanggungan yang Dibayar</label>
            <input type="number" name="jumlah_tanggunganyangdibayar" id="jumlahDibayar" class="form-control" oninput="updatePerkiraanBayar()" required>
          </div>
          <div class="mb-3">
            <label>Bayar Beras (Kg)</label>
            <input type="number" step="0.1" name="bayar_beras" id="bayarBeras" class="form-control">
          </div>
          <div class="mb-3">
            <label>Bayar Uang (Rp)</label>
            <input type="text" name="bayar_uang" id="bayarUang" class="form-control" oninput="formatInputRupiah(this)">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" name="tambah">Simpan Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Tambah -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function formatRupiah(angka) {
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
function formatInputRupiah(el) {
  let angka = el.value.replace(/\D/g, '');
  el.value = formatRupiah(angka);
}
function updatePerkiraanBayar() {
  const jenis = document.getElementById('jenisBayar').value;
  const jumlah = parseInt(document.getElementById('jumlahDibayar').value || 0);
  if (jenis === "beras") {
    document.getElementById('bayarBeras').value = (jumlah * 2.5).toFixed(1);
  } else if (jenis === "uang") {
    document.getElementById('bayarUang').value = formatRupiah(jumlah * 45000);
  }
}
function updateEditPerkiraanBayar(id) {
  const jenis = document.getElementById('jenisBayar_' + id).value;
  const jumlah = parseInt(document.getElementById('jumlahDibayar_' + id).value || 0);
  if (jenis === "beras") {
    document.getElementById('bayarBeras_' + id).value = (jumlah * 2.5).toFixed(1);
  } else if (jenis === "uang") {
    document.getElementById('bayarUang_' + id).value = formatRupiah(jumlah * 45000);
  }
}
</script>

</body>
</html>
