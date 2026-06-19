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

$admin = new Admin($koneksi);

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $admin->hapusSiswa($id);
    header('Location: data_siswa.php?msg=Siswa+berhasil+dihapus');
    exit;
}

// Edit siswa (POST)
$editData = null;
$editMsg  = '';
if (isset($_GET['edit'])) {
    $editData = $admin->getSiswaById((int) $_GET['edit']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id    = (int) $_POST['edit_id'];
    $nama  = trim($_POST['nama']  ?? '');
    $nis   = trim($_POST['nis']   ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    if ($nama && $nis && $kelas) {
        if ($admin->cekNisAda($nis, $id)) {
            $editMsg = 'NIS sudah terdaftar oleh siswa lain.';
        } else {
            $admin->editSiswa($id, $nama, $nis, $kelas);
            header('Location: data_siswa.php?msg=Data+berhasil+diperbarui');
            exit;
        }
    } else {
        $editMsg = 'Semua field harus diisi.';
    }
    $editData = ['id' => $id, 'nama' => $nama, 'nis' => $nis, 'kelas' => $kelas];
}

$cari  = trim($_GET['cari'] ?? '');
$siswa = $admin->getSemua($cari);
$msg   = htmlspecialchars($_GET['msg'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link rel="stylesheet" href="datasis.css">
</head>
<body>
<?php include 'sidebarmin.php'; ?>

<div class="data-container">
    <h1>📂  Data Seluruh Siswa</h1>
    <p class="subtitle">Total <?php echo count($siswa); ?> siswa<?php echo $cari ? " ditemukan untuk \"".htmlspecialchars($cari)."\"" : ''; ?>.</p>

    <?php if ($msg): ?>
        <div class="alert"><?= $msg ?></div>
    <?php endif; ?>
    <?php if ($editMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($editMsg) ?></div>
    <?php endif; ?>

    <div class="toolbar">
        <form method="get" action="data_siswa.php">
            <input type="text" name="cari" placeholder="Cari nama, NIS, atau kelas…" value="<?= htmlspecialchars($cari) ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($cari): ?><a href="data_siswa.php" class="btn btn-reset">Reset</a><?php endif; ?>
        </form>
        <a href="tambah_siswa.php" class="btn btn-primary btn-right">+ Tambah Siswa</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($siswa)): ?>
            <tr><td colspan="5" class="no-data">Tidak ada data siswa.</td></tr>
        <?php else: $no = 1; foreach ($siswa as $s): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($s['nama']) ?></td>
                <td><?= htmlspecialchars($s['nis']) ?></td>
                <td><?= htmlspecialchars($s['kelas']) ?></td>
                <td>
                    <div class="actions">
                        <button class="btn btn-warning btn-sm"
                            onclick="openEdit(<?= $s['id'] ?>, '<?= addslashes($s['nama']) ?>', '<?= addslashes($s['nis']) ?>', '<?= addslashes($s['kelas']) ?>')">
                            ✏️ Edit
                        </button>
                        <a href="data_siswa.php?hapus=<?= $s['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars($s['nama']) ?>?')">
                            🗑️ Hapus
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h2>Edit Data Siswa</h2>
        <form method="post" action="data_siswa.php">
            <input type="hidden" name="edit_id" id="edit_id">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" id="edit_nama" required>
            </div>
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" id="edit_nis" required>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" id="edit_kelas" required>
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
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-batal" onclick="closeEdit()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, nama, nis, kelas) {
    document.getElementById('edit_id').value   = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nis').value  = nis;
    document.getElementById('edit_kelas').value = kelas;
    document.getElementById('editModal').classList.add('open');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('open');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
</body>
</html>
