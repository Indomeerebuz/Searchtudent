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
include 'class/class_siswa.php';
include 'class/class_auth.php';

$siswaObj = new Siswa($koneksi);
$authObj = new Auth($koneksi);
$username = $_SESSION['user']['username'] ?? '';
$dataSiswa = $siswaObj->getSiswaByUsername($username);

// Form processing moved to profil_siswa.php and ganti_password_siswa.php

$namaUser = htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? 'Siswa');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="dashwa.css?v=<?= time() ?>">
</head>
<body class="dashboard-body">

<?php include 'sidebar_siswa.php'; ?>

<div class="main-panel">
<div class="siswa-panel">
    <div class="welcome-card">
        <div class="avatar">🎓</div>
        <h1>Halo, <?= $namaUser ?>!</h1>
        <p>Selamat datang di portal siswa Searchtudent.</p>
    </div>

    <div class="info-card">
        <h2>📋 Informasi Akun</h2>
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value"><?= $namaUser ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Username</span>
            <span class="info-value"><?= htmlspecialchars($_SESSION['user']['username'] ?? '-') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value"><?= htmlspecialchars($_SESSION['user']['email'] ?? '-') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Role</span>
            <span class="info-value"><?= htmlspecialchars($_SESSION['role']) ?></span>
        </div>
    </div>



</div>
</div>

</body>
</html>
