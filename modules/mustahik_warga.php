<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Ambil daftar muzakki untuk pilihan nama mustahik
$daftarMuzakki = mysqli_query($conn, "SELECT * FROM muzakki");

// Tambah Mustahik Warga
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $hak = $_POST['hak'];

    mysqli_query($conn, "INSERT INTO mustahik_warga (nama, kategori, hak) VALUES ('$nama', '$kategori', '$hak')");
    header("Location: mustahik_warga.php");
}

// Edit Mustahik Warga
if (isset($_POST['edit'])) {
    $id = $_POST['id_mustahikwarga'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $hak = $_POST['hak'];

    mysqli_query($conn, "UPDATE mustahik_warga SET nama='$nama', kategori='$kategori', hak='$hak' WHERE id_mustahikwarga='$id'");
    header("Location: mustahik_warga.php");
}

// Hapus Mustahik Warga
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mustahik_warga WHERE id_mustahikwarga='$id'");
    header("Location: mustahik_warga.php");
}

// Ambil Data Mustahik Warga
$mustahikWarga = mysqli_query($conn, "SELECT * FROM mustahik_warga");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Distribusi Zakat Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
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
    <h3 class="text-center mb-4">Distribusi Zakat Fitrah ke Warga</h3>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Penerima Zakat</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Hak (Kg Beras)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($mustahikWarga)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= ucfirst($row['kategori']) ?></td>
                <td><?= $row['hak'] ?> Kg</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_mustahikwarga'] ?>">Edit</button>
                    <a href="mustahik_warga.php?hapus=<?= $row['id_mustahikwarga'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus data ini?')">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id_mustahikwarga'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Data Penerima Zakat</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id_mustahikwarga" value="<?= $row['id_mustahikwarga'] ?>">
                      <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" value="<?= $row['nama'] ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="fakir" <?= $row['kategori'] == 'fakir' ? 'selected' : '' ?>>Fakir</option>
                            <option value="miskin" <?= $row['kategori'] == 'miskin' ? 'selected' : '' ?>>Miskin</option>
                            <option value="mampu" <?= $row['kategori'] == 'mampu' ? 'selected' : '' ?>>Mampu</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label>Hak (Kg)</label>
                        <input type="number" step="0.1" name="hak" value="<?= $row['hak'] ?>" class="form-control" required>
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
          <h5 class="modal-title">Tambah Penerima Zakat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama</label>
            <select name="nama" class="form-control" required>
                <option value="">- Pilih Muzakki -</option>
                <?php while ($muzakki = mysqli_fetch_assoc($daftarMuzakki)) { ?>
                    <option value="<?= $muzakki['nama_muzakki'] ?>"><?= $muzakki['nama_muzakki'] ?></option>
                <?php } ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">- Pilih Kategori -</option>
                <option value="fakir">Fakir</option>
                <option value="miskin">Miskin</option>
                <option value="mampu">Mampu</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Hak (Kg)</label>
            <input type="number" step="0.1" name="hak" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" name="tambah">Tambah Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Tambah -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
