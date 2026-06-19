<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login_register.php');
    exit;
}

if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Siswa') {
    header('Location: login_register.php');
    exit;
}

include 'koneksi.php';
include 'class/class_auth.php';

$authObj = new Auth($koneksi);
$username = $_SESSION['user']['username'] ?? '';

$pesanPassword = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ganti_password'])) {
        $password_baru = $_POST['password_baru'] ?? '';
        if (strlen($password_baru) < 4) {
            $pesanPassword = "Password baru minimal 4 karakter.";
        } else {
            if ($authObj->gantiPassword($username, $password_baru)) {
                $pesanPassword = "Password berhasil diubah!";
            } else {
                $pesanPassword = "Gagal mengubah password.";
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
    <title>Ganti Password</title>
    <link rel="stylesheet" href="dashwa.css?v=<?= time() ?>">
</head>
<body class="dashboard-body">

<?php include 'sidebar_siswa.php'; ?>

<div class="main-panel">
<div class="siswa-panel">

    <div class="info-card">
        <h2>🔒 Ganti Password</h2>
        <?php if ($pesanPassword): ?>
            <p class="<?= strpos($pesanPassword, 'berhasil') !== false ? '' : 'error-text' ?>" style="<?= strpos($pesanPassword, 'berhasil') !== false ? 'color:green;' : '' ?>"><?= htmlspecialchars($pesanPassword) ?></p>
        <?php endif; ?>
        <form method="post" action="ganti_password_siswa.php" class="identity-form">
            <input type="password" name="password_baru" placeholder="Password Baru" required class="identity-input">
            <button type="submit" name="ganti_password" class="identity-btn">Ganti Password</button>
        </form>
    </div>

</div>
</div>

</body>
</html>
