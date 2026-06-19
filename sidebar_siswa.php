<?php
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'Siswa'){
    // ensure access control
}
?>
<div class="sidebar">
<link rel="stylesheet" href="sidemin.css">
    <h1>
        <a href="dashboard_siswa.php" class="logo-link">
            <img src="assets/edankeun.png" class="logo">
            Searchtudent
        </a>
    </h1>

    <ul>
        <li><a href="dashboard_siswa.php">🏠 Dashboard</a></li>
        <li><a href="profil_siswa.php">🏫 Profil Siswa</a></li>
        <li><a href="ganti_password_siswa.php">🔒 Ganti Password</a></li>
        <li><hr style="border: 0.5px solid rgba(255, 255, 255, 0.2); margin: 10px 20px;"></li>
        <li><a href="logout.php" style="color: #ff4d4d;">🚪 Keluar</a></li>
    </ul>
</div>
