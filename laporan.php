<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login_register.php');
    exit;
}
if ($_SESSION['role'] !== 'Admin') {
    header('Location: dashboard_siswa.php');
    exit;
}

include 'koneksi.php';
include 'class/class_admin.php';

$admin  = new Admin($koneksi);
$cari   = trim($_GET['cari'] ?? '');
$kelas  = trim($_GET['kelas'] ?? '');

// Ambil semua kelas unik untuk filter dropdown
$kelasResult = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC");
$kelasList   = [];
while ($row = mysqli_fetch_assoc($kelasResult)) {
    $kelasList[] = $row['kelas'];
}

// Query dengan filter kelas
$sql = "SELECT * FROM siswa WHERE 1=1";
if ($cari !== '') {
    $s = mysqli_real_escape_string($koneksi, $cari);
    $sql .= " AND (nama LIKE '%$s%' OR nis LIKE '%$s%' OR kelas LIKE '%$s%')";
}
if ($kelas !== '') {
    $k = mysqli_real_escape_string($koneksi, $kelas);
    $sql .= " AND kelas='$k'";
}
$sql .= " ORDER BY kelas ASC, nama ASC";
$result = mysqli_query($koneksi, $sql);
$siswa  = [];
while ($row = mysqli_fetch_assoc($result)) {
    $siswa[] = $row;
}

$tanggal = date('d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Siswa</title>
    <link rel="stylesheet" href="lapor.css">
</head>
<body>
<?php include 'sidebarmin.php'; ?>

<div class="data-container">
    <h1>📋 Laporan Data Siswa</h1>
    <p class="subtitle">Dicetak pada <?= $tanggal ?></p>

    <div class="toolbar no-print">
        <form method="get" action="laporan.php">
            <input type="text" name="cari" placeholder="Cari nama / NIS…" value="<?= htmlspecialchars($cari) ?>">
            <select name="kelas">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelasList as $k): ?>
                    <option value="<?= htmlspecialchars($k) ?>" <?= $kelas === $k ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <?php if ($cari || $kelas): ?>
                <a href="laporan.php" class="btn btn-reset">Reset</a>
            <?php endif; ?>
        </form>
        <button class="btn btn-print" onclick="window.print()">🖨️ Cetak / PDF</button>
    </div>

    <!-- Stats -->
    <div class="stats no-print">
        <div class="stat-box">
            <div class="label">Total Ditampilkan</div>
            <div class="value"><?= count($siswa) ?></div>
        </div>
        <?php if ($kelas): ?>
        <div class="stat-box">
            <div class="label">Filter Kelas</div>
            <div class="value"><?= htmlspecialchars($kelas) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="report-card">
        <div class="report-header">
            <h2>Daftar Data Siswa</h2>
            <p>
                <?= $kelas ? 'Kelas ' . htmlspecialchars($kelas) . ' — ' : '' ?>
                <?= $cari  ? 'Pencarian: "' . htmlspecialchars($cari) . '" — ' : '' ?>
                Total: <?= count($siswa) ?> siswa &nbsp;|&nbsp; <?= $tanggal ?>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($siswa)): ?>
                <tr><td colspan="4" class="no-data">Tidak ada data siswa.</td></tr>
            <?php else: $no = 1; foreach ($siswa as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($s['nama']) ?></td>
                    <td><?= htmlspecialchars($s['nis']) ?></td>
                    <td><?= htmlspecialchars($s['kelas']) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
