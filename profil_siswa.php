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
$username = $_SESSION['user']['username'] ?? '';
$dataSiswa = $siswaObj->getSiswaByUsername($username);

$pesanIdentitas = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah_identitas']) || isset($_POST['edit_identitas'])) {
        $nama = trim($_POST['nama'] ?? '');
        $nis = trim($_POST['nis'] ?? '');
        $kelas = trim($_POST['kelas'] ?? '');
        
        if ($nama && $nis && $kelas) {
            if ($siswaObj->cekNisAda($nis, $username)) {
                $pesanIdentitas = "NIS sudah terdaftar oleh siswa lain.";
            } else {
                if (isset($_POST['tambah_identitas'])) {
                    if ($siswaObj->tambahIdentitasSiswa($nama, $nis, $kelas, $username)) {
                        $pesanIdentitas = "Identitas berhasil disimpan!";
                        $dataSiswa = $siswaObj->getSiswaByUsername($username);
                    } else {
                        $pesanIdentitas = "Gagal menyimpan identitas.";
                    }
                } else {
                    if ($siswaObj->editIdentitasSiswa($nama, $nis, $kelas, $username)) {
                        $pesanIdentitas = "Identitas berhasil diperbarui!";
                        $dataSiswa = $siswaObj->getSiswaByUsername($username);
                    } else {
                        $pesanIdentitas = "Gagal memperbarui identitas.";
                    }
                }
            }
        } else {
            $pesanIdentitas = "Semua field harus diisi.";
        }
    }
}

$namaUser = htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? 'Siswa');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa</title>
    <link rel="stylesheet" href="dashwa.css?v=<?= time() ?>">
</head>
<body class="dashboard-body">

<?php include 'sidebar_siswa.php'; ?>

<div class="main-panel">
<div class="siswa-panel">

    <?php if ($dataSiswa): ?>
    <div class="info-card">
        <h2>🏫 Data Siswa (Profil)</h2>
        <?php if ($pesanIdentitas && isset($_POST['edit_identitas'])): ?>
            <p class="<?= strpos($pesanIdentitas, 'berhasil') !== false ? '' : 'error-text' ?>" style="<?= strpos($pesanIdentitas, 'berhasil') !== false ? 'color:green;' : '' ?>"><?= htmlspecialchars($pesanIdentitas) ?></p>
        <?php endif; ?>
        <form method="post" action="profil_siswa.php" class="identity-form">
            <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= htmlspecialchars($dataSiswa['nama']) ?>" required class="identity-input">
            <input type="text" name="nis" placeholder="NIS" value="<?= htmlspecialchars($dataSiswa['nis']) ?>" required class="identity-input">
            <select name="kelas" required class="identity-input">
                <option value="" disabled>Pilih Kelas</option>
                <?php
                $kelasOptions = ['X-PPLG', 'X-PMN', 'X-HTL', 'XI-PPLG', 'XI-PMN', 'XI-HTL', 'XI-TJKT', 'XII-PPLG', 'XII-PMN', 'XII-HTL', 'XII-TJKT'];
                foreach ($kelasOptions as $opt) {
                    $sel = ($dataSiswa['kelas'] === $opt) ? 'selected' : '';
                    echo "<option value=\"$opt\" $sel>$opt</option>";
                }
                ?>
            </select>
            <button type="submit" name="edit_identitas" class="identity-btn">Update Identitas</button>
        </form>
    </div>
    <?php else: ?>
    <div class="info-card">
        <h2>➕ Lengkapi Identitas Diri</h2>
        <?php if ($pesanIdentitas && isset($_POST['tambah_identitas'])): ?>
            <p class="<?= strpos($pesanIdentitas, 'berhasil') !== false ? '' : 'error-text' ?>" style="<?= strpos($pesanIdentitas, 'berhasil') !== false ? 'color:green;' : '' ?>"><?= htmlspecialchars($pesanIdentitas) ?></p>
        <?php endif; ?>
        <form method="post" action="profil_siswa.php" class="identity-form">
            <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= $namaUser ?>" required class="identity-input">
            <input type="text" name="nis" placeholder="NIS" required class="identity-input">
            <select name="kelas" required class="identity-input">
                <option value="" disabled selected>Pilih Kelas</option>
                <option value="X-PPLG">X-PPLG</option>
                <option value="X-PMN">X-PMN</option>
                <option value="X-HTL">X-HTL</option>
                <option value="XI-PPLG">XI-PPLG</option>
                <option value="XI-PMN">XI-PMN</option>
                <option value="XI-HTL">XI-HTL</option>
                <option value="XI-TJKT">XI-TJKT</option>
                <option value="XII-PPLG">XII-PPLG</option>
                <option value="XII-PMN">XII-PMN</option>
                <option value="XII-HTL">XII-HTL</option>
                <option value="XII-TJKT">XII-TJKT</option>
            </select>
            <button type="submit" name="tambah_identitas" class="identity-btn">Simpan Identitas</button>
        </form>
    </div>
    <?php endif; ?>

</div>
</div>

</body>
</html>
