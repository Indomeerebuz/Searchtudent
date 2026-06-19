<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login_register.php');
    exit;
}

include 'koneksi.php';
include 'class/class_auth.php';

$authObj = new Auth($koneksi);
$username = $_SESSION['user']['username'] ?? '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ganti_password'])) {
        $password_baru = $_POST['password_baru'] ?? '';
        if (strlen($password_baru) < 4) {
            $message = "Password baru minimal 4 karakter.";
        } else {
            if ($authObj->gantiPassword($username, $password_baru)) {
                $message = "Password berhasil diubah!";
            } else {
                $message = "Gagal mengubah password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Admin</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>
    <?php include 'sidebarmin.php'; ?>
    <div class="tambah-container">
        <h1>🔒 Ganti Password</h1>
        <?php if ($message): ?>
            <div class="message" style="<?php echo strpos($message, 'berhasil') !== false ? 'color: green; background-color: #d4edda; border-color: #c3e6cb;' : 'color: red;'; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form class="tambah-form" method="post" action="ganti_password_admin.php">
            <div class="form-group">
                <label for="password_baru">Password Baru</label>
                <input type="password" id="password_baru" name="password_baru" placeholder="Masukkan password baru" required />
            </div>
            <button class="submit-btn" name="ganti_password" type="submit">Ganti Password</button>
        </form>
        <p><a class="back-link" href="dashboard_admin.php">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
