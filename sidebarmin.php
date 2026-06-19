<?php
if(!isset($_SESSION['user'])){
    header("Location: login_register.php");
}
?>

<div class="sidebar">
<link rel="stylesheet" href="sidemin.css">
    <h1>
        <a href="dashboard_admin.php" class="logo-link">
            <img src="assets/edankeun.png" class="logo">
            Searchtudent
        </a>
    </h1>

    <ul>
        <li>    <a href="dashboard_admin.php">🏠 Dashboard</a></li>
        <li>    <a href="data_siswa.php">📂 Data Seluruh Siswa</a></li>
        <li>    <a href="tambah_siswa.php">➕ Tambah Siswa</a></li>
        <li>    <a href="laporan.php">📋 Laporan Data</a></li>
        <li>    <a href="ganti_password_admin.php">🔒 Ganti Password</a></li>
        <li>    <hr style="border: 0.5px solid #ffffff; margin: 10px 20px;"></li>
        <li>    <a href="logout.php" style="color: #ff0000;">🚪 Keluar</a></li>
    </ul>
</div>
