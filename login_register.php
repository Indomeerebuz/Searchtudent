<?php
session_start();
include "koneksi.php";
include "class/class_auth.php";

$auth = new Auth($koneksi);
$login_error = "";
$register_error = "";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $pass = $_POST['password'];

    if($username != "" && $pass != "") {
        $data = $auth->login($username, $pass);
        if($data) {
            $_SESSION['user'] = $data;
            $_SESSION['role'] = $data['role'];

            if($data['role'] == 'Admin') {
                header("Location: dashboard_admin.php");
                exit;
            } elseif($data['role'] == 'Siswa') {
                header("Location: dashboard_siswa.php");
                exit;
            } else {
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $login_error = "Username atau password salah!";
        }
    } else {
        $login_error = "Username dan password harus diisi!";
    }
}

if(isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($name != "" && $username != "" && $email != "" && $password != "") {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = "Format email tidak valid!";
        } else {
            $result = $auth->register($name, $username, $email, $password);
            if($result === true) {
                echo "<script>alert('Registrasi Berhasil! Silahkan Login.'); window.location='login_register.php';</script>";
                exit;
            } else {
                $register_error = $result;
            }
        }
    } else {
        $register_error = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ke Dashboard!</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <div class="form-box <?php echo (isset($_POST['register'])) ? '' : 'active'; ?>" id="login-form">
            <form action="login_register.php" method="POST">
                <img src="assets/Edankeun.png">
                <h2>Login ke Dashboard</h2>
                <?php if($login_error != "") { echo "<p class='error-msg'>$login_error</p>"; } ?>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p>Belum punya akun? <a href="#" onclick="showForm('register-form')">Daftar di sini</a></p>
            </form>
        </div>

        <div class="form-box <?php echo (isset($_POST['register'])) ? 'active' : ''; ?>" id="register-form">
            <form action="login_register.php" method="POST">
                <div class="login-logo">
                    <img src="assets/Edankeun.png">
                </div>
                <h2>Registrasi</h2>
                <?php if($register_error != "") { echo "<p class='error-msg'>$register_error</p>"; } ?>
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="hidden" name="Role" value="Siswa">
                <button type="submit" name="register">Daftar</button>
                <p>Sudah punya akun? <a href="#" onclick="showForm('login-form')">Login di sini</a></p>
            </form>
        </div>
    </div>

    <script src="logister.js"></script>
</body>
</html>