# Projek Edan - Sistem Data Siswa

Aplikasi berbasis **PHP Native OOP** untuk pengelolaan data siswa dengan sistem **multi-user (Admin & Siswa)**. Aplikasi ini menyediakan fitur autentikasi, pengelolaan data siswa, serta pencetakan laporan.

---

## Cara Install (XAMPP / Laragon)

### 1. Ekstrak Project

Ekstrak folder **projek_edan** ke:

* `htdocs/` untuk XAMPP
* `www/` untuk Laragon

### 2. Import Database

1. Buka **phpMyAdmin**
2. Buat database dengan nama:

```sql
sistem_data_siswa
```

3. Import file:

```text
database.sql
```

File tersebut akan membuat:

* Seluruh tabel yang diperlukan
* 1 akun Admin
* 2 contoh data siswa

### 3. Konfigurasi Koneksi Database

Buka file:

```text
koneksi.php
```

Sesuaikan konfigurasi berikut jika diperlukan:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "sistem_data_siswa";
```

### 4. Jalankan Aplikasi

Buka browser dan akses:

```text
http://localhost/projek_edan/
```

---

# Akun Default

### Admin

| Username | Password |
| -------- | -------- |
| admin    | admin    |

### Siswa

Silakan melakukan registrasi melalui halaman **Register**.

> Secara default, akun yang mendaftar akan memiliki role **Siswa**. Jika ingin mengubah menjadi **Admin**, Anda dapat mengubah kolom `role` melalui phpMyAdmin.

---

# Struktur Folder

```text
projek_edan/
│
├── class/
│   ├── class_auth.php
│   └── class_admin.php
│
├── assets/              # Folder aset gambar (logo, icon, dll)
│
├── database.sql         # Schema database + sample data
│
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
```

---

# Fitur

## Siswa

* Register akun
* Login
* Dashboard Siswa
* Melihat profil pribadi
* Mengubah password akun

## Admin

* Login Admin
* Dashboard dengan statistik siswa
* Tambah data siswa
* Lihat data siswa
* Edit data siswa
* Hapus data siswa
* Cetak laporan data siswa
* Mengubah password akun

---

# Alur Pengelolaan Data

### Tambah Data

Admin menambahkan data siswa baru ke dalam sistem.

### Edit Data

Admin memperbarui data siswa yang sudah ada.

### Hapus Data

Admin menghapus data siswa dari sistem.

### Cetak Laporan

Admin mencetak seluruh data siswa dalam bentuk laporan.

---

# Teknologi yang Digunakan

* PHP Native (OOP)
* MySQL
* HTML5
* CSS3
* Bootstrap
* XAMPP / Laragon

---

# Troubleshooting

### Gagal Login

* Pastikan username dan password benar.
* Pastikan akun memiliki role yang sesuai.
* Jika login sebagai siswa, lakukan registrasi terlebih dahulu.

### Koneksi Database Gagal

* Pastikan MySQL sudah berjalan.
* Pastikan nama database adalah:

```text
sistem_data_siswa
```

* Periksa konfigurasi pada file `koneksi.php`.

### CSS Tidak Muncul

* Pastikan folder project bernama:

```text
projek_edan
```

* Pastikan aplikasi diakses melalui:

```text
http://localhost/projek_edan/
```

---

## Author

**Projek Edan - Sistem Data Siswa**
Aplikasi pengelolaan data siswa berbasis PHP OOP dengan sistem multi-user.

