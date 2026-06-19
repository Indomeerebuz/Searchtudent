Projek Edan — Sistem Data Siswa
Aplikasi PHP OOP untuk pengelolaan data siswa dengan fitur user/admin, alur tambah-edit-hapus-cetak laporan.

Cara Install (XAMPP / Laragon)
Extract folder projek_edan ke htdocs (XAMPP) atau www (Laragon).
Buat database: buka phpMyAdmin → import file database.sql. Otomatis membuat database sistem data siswa beserta semua tabel + 1 akun admin + 2 contoh data siswa.
Sesuaikan koneksi jika perlu di koneksi.php (default: host localhost, user root, password kosong).
Jalankan: buka http://localhost/projek_edan/ di browser.

Akun Default
Admin: username admin, password admin
User: daftar via halaman Register.
Catatan: secara default registrasi akan diberikan role Siswa. Untuk mengubah menjadi Admin, dapat diedit langsung via database phpMyAdmin.

Struktur Folder
projek_edan/
├── class/           # Class PHP (class_auth.php, class_admin.php)
├── assets/          # Folder aset gambar (logo, dll)
├── database.sql     # Schema + sample data
├── dashboard_admin.php
├── dashboard_siswa.php
├── data_siswa.php
├── tambah_siswa.php
├── profil_siswa.php
├── laporan.php
├── ganti_password_admin.php
├── ganti_password_siswa.php
├── koneksi.php
├── login_register.php
└── logout.php

Fitur
User (Siswa)
Register & Login
Dashboard Siswa
Lihat Profil detail data pribadi
Ganti Password keamanan akun

Admin
Dashboard dengan statistik siswa
CRUD Data Siswa (tambah, lihat, ubah, hapus)
Cetak laporan data siswa
Ganti Password keamanan akun

Status Alur Data
tambah → admin menambahkan data siswa baru
edit → admin memperbarui data siswa yang ada
hapus → admin menghapus data siswa
cetak → admin mencetak laporan keseluruhan data siswa

Troubleshooting
Gagal Login: pastikan Anda menggunakan role admin untuk fitur admin, atau daftar baru jika sebagai siswa.
Koneksi gagal: pastikan nama database di phpMyAdmin adalah "sistem data siswa" sesuai dengan di koneksi.php.
Tampilan CSS tidak memuat: pastikan folder ekstrak bernama "projek_edan" dan diakses secara benar via localhost.
