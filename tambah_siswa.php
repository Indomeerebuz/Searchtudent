<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login_register.php');
    exit;
}

include 'koneksi.php';
include 'class/class_admin.php';

$admin = new Admin($koneksi);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nis = trim($_POST['nis'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');

    if ($nama === '' || $nis === '' || $kelas === '') {
        $message = 'Semua field harus diisi.';
    } elseif ($admin->cekNisAda($nis)) {
        $message = 'NIS sudah terdaftar. Gunakan NIS lain.';
    } else {
        $success = $admin->tambahSiswa($nama, $nis, $kelas);
        if ($success) {
            header('Location: dashboard_admin.php?msg=Tambah siswa berhasil');
            exit;
        }
        $message = 'Gagal menyimpan data siswa. Silakan coba lagi.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>
    <?php include 'sidebarmin.php'; ?>
    <div class="tambah-container">
        <h1>➕   Tambah Siswa</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form class="tambah-form" method="post" action="tambah_siswa.php">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required />
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" id="nis" name="nis" value="<?php echo isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : ''; ?>" required />
            </div>
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas" required>
                    <?php $selectedKelas = isset($_POST['kelas']) ? $_POST['kelas'] : ''; ?>
                    <option value="" disabled <?php echo $selectedKelas === '' ? 'selected' : ''; ?>>Pilih Kelas</option>
                    <?php
                    $kelasOptions = ['X-PPLG', 'X-PMN', 'X-HTL', 'XI-PPLG', 'XI-PMN', 'XI-HTL', 'XI-TJKT', 'XII-PPLG', 'XII-PMN', 'XII-HTL', 'XII-TJKT'];
                    foreach ($kelasOptions as $opt) {
                        $sel = ($selectedKelas === $opt) ? 'selected' : '';
                        echo "<option value=\"$opt\" $sel>$opt</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="submit-btn" type="submit">Simpan</button>
        </form>
        <p><a class="back-link" href="dashboard_admin.php">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>