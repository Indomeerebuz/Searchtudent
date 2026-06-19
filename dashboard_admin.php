<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login_register.php');
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    header('Location: login_register.php');
    exit;
}

include 'koneksi.php';
include 'class/class_admin.php';

$admin = new Admin($koneksi);
$totalSiswa = $admin->jumlahSiswa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="dashmin.css">
</head>
<body>
    <?php include 'sidebarmin.php'; ?>
    <div class="main-panel">
        <header class="panel-header">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? 'Admin'); ?>.</p>
            </div>
            <div class="header-actions">
                <a href="data_siswa.php" class="button"> + tambah Siswa</a>
            </div>
        </header>

        <section class="dashboard-grid">
            <article class="card card-primary">
                <div class="card-title">Total Siswa 👥</div>
                <div class="card-value"><?php echo number_format($totalSiswa); ?></div>
            </article>

            <article class="card card-action">
                <div class="card-title">Data Siswa 📂</div>
                <div class="card-text">Lihat semua data siswa dan kelola informasi.</div>
                <a class="card-link" href="data_siswa.php">Buka Data Siswa</a>
            </article>

            <article class="card card-action">
                <div class="card-title">Laporan 📊</div>
                <div class="card-text">Buat dan cetak laporan daftar siswa.</div>
                <a class="card-link" href="laporan.php">Buka Laporan</a>
            </article>
        </section>
    </div>
</body>
</html>