<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Tambah Muzakki
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_muzakki'];
    $tanggungan = $_POST['jumlah_tanggungan'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO muzakki (nama_muzakki, jumlah_tanggungan, keterangan) VALUES ('$nama', '$tanggungan', '$keterangan')");
    header("Location: muzakki.php");
}

// Edit Muzakki
if (isset($_POST['edit'])) {
    $id = $_POST['id_muzakki'];
    $nama = $_POST['nama_muzakki'];
    $tanggungan = $_POST['jumlah_tanggungan'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "UPDATE muzakki SET nama_muzakki='$nama', jumlah_tanggungan='$tanggungan', keterangan='$keterangan' WHERE id_muzakki='$id'");
    header("Location: muzakki.php");
}

// Hapus Muzakki
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM muzakki WHERE id_muzakki='$id'");
    header("Location: muzakki.php");
}

// Ambil Data Muzakki
$muzakki = mysqli_query($conn, "SELECT * FROM muzakki");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Muzakki</title>
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
    <h3 class="text-center mb-4">Data Muzakki</h3>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Muzakki</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Muzakki</th>
                <th>Jumlah Tanggungan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($muzakki)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_muzakki'] ?></td>
                <td><?= $row['jumlah_tanggungan'] ?></td>
                <td><?= $row['keterangan'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_muzakki'] ?>">Edit</button>
                    <a href="muzakki.php?hapus=<?= $row['id_muzakki'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id_muzakki'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Muzakki</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id_muzakki" value="<?= $row['id_muzakki'] ?>">
                      <div class="mb-3">
                        <label>Nama Muzakki</label>
                        <input type="text" name="nama_muzakki" value="<?= $row['nama_muzakki'] ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label>Jumlah Tanggungan</label>
                        <input type="number" name="jumlah_tanggungan" value="<?= $row['jumlah_tanggungan'] ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" required><?= $row['keterangan'] ?></textarea>
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
          <h5 class="modal-title">Tambah Muzakki</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Muzakki</label>
            <input type="text" name="nama_muzakki" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Jumlah Tanggungan</label>
            <input type="number" name="jumlah_tanggungan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" required></textarea>
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
