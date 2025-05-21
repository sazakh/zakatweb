<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Tambah Kategori Mustahik
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kategori'];
    $hak = $_POST['jumlah_hak'];

    mysqli_query($conn, "INSERT INTO kategori_mustahik (nama_kategori, jumlah_hak) VALUES ('$nama', '$hak')");
    header("Location: kategori_mustahik.php");
}

// Edit Kategori Mustahik
if (isset($_POST['edit'])) {
    $id = $_POST['id_kategori'];
    $nama = $_POST['nama_kategori'];
    $hak = $_POST['jumlah_hak'];

    mysqli_query($conn, "UPDATE kategori_mustahik SET nama_kategori='$nama', jumlah_hak='$hak' WHERE id_kategori='$id'");
    header("Location: kategori_mustahik.php");
}

// Hapus Kategori Mustahik
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kategori_mustahik WHERE id_kategori='$id'");
    header("Location: kategori_mustahik.php");
}

// Ambil Data Kategori Mustahik
$kategori = mysqli_query($conn, "SELECT * FROM kategori_mustahik");
$kategoriList = ["fakir", "miskin", "mampu", "amilin", "fisabilillah", "mualaf", "ibnu_sabil"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori Mustahik</title>
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
    <h3 class="text-center mb-4">Data Kategori Mustahik</h3>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Kategori</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Jumlah Hak (Kg Beras)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($kategori)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= ucfirst(str_replace('_', ' ', $row['nama_kategori'])) ?></td>
                <td><?= $row['jumlah_hak'] ?> Kg</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_kategori'] ?>">Edit</button>
                    <a href="kategori_mustahik.php?hapus=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id_kategori'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Kategori Mustahik</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id_kategori" value="<?= $row['id_kategori'] ?>">
                      <div class="mb-3">
                        <label>Nama Kategori</label>
                        <select name="nama_kategori" class="form-control" required>
                          <option value="">- Pilih Kategori -</option>
                          <?php foreach ($kategoriList as $item): ?>
                            <option value="<?= $item ?>" <?= ($row['nama_kategori'] == $item ? 'selected' : '') ?>>
                              <?= ucfirst(str_replace('_', ' ', $item)) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label>Jumlah Hak (Kg)</label>
                        <input type="number" name="jumlah_hak" value="<?= $row['jumlah_hak'] ?>" class="form-control" required>
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
          <h5 class="modal-title">Tambah Kategori Mustahik</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Kategori</label>
            <select name="nama_kategori" class="form-control" required>
              <option value="">- Pilih Kategori -</option>
              <?php foreach ($kategoriList as $item): ?>
                <option value="<?= $item ?>"><?= ucfirst(str_replace('_', ' ', $item)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Jumlah Hak (Kg)</label>
            <input type="number" name="jumlah_hak" class="form-control" required>
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
