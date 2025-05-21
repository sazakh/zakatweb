<?php
session_start();
include '../config/db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($query) {
            header("Location: login.php");
        } else {
            $error = "Gagal mendaftar, coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Akun Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h4 class="text-center mb-4">Register Akun</h4>
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="post">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" name="register">Daftar</button>
                </form>
                <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
