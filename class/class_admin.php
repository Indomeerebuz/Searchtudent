<?php
class Admin {
    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    // Hitung total siswa
    public function jumlahSiswa(): int {
        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM siswa");
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return (int) ($row['total'] ?? 0);
    }

    // Cek apakah NIS sudah ada
    public function cekNisAda(string $nis, int $exclude_id = 0): bool {
        $nis = mysqli_real_escape_string($this->koneksi, $nis);
        $sql = "SELECT id FROM siswa WHERE nis = '$nis'";
        if ($exclude_id > 0) {
            $sql .= " AND id != $exclude_id";
        }
        $result = mysqli_query($this->koneksi, $sql);
        return $result && mysqli_num_rows($result) > 0;
    }

    // Ambil semua data siswa (dengan opsional pencarian)
    public function getSemua(string $cari = ''): array {
        $cari_safe = mysqli_real_escape_string($this->koneksi, $cari);
        if ($cari_safe !== '') {
            $sql = "SELECT * FROM siswa
                    WHERE nama LIKE '%$cari_safe%'
                       OR nis  LIKE '%$cari_safe%'
                       OR kelas LIKE '%$cari_safe%'
                    ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM siswa ORDER BY id DESC";
        }
        $result = mysqli_query($this->koneksi, $sql);
        if (!$result) return [];
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // Ambil satu siswa berdasarkan ID
    public function getSiswaById(int $id): ?array {
        $result = mysqli_query($this->koneksi,
            "SELECT * FROM siswa WHERE id = $id LIMIT 1");
        if (!$result) return null;
        $row = mysqli_fetch_assoc($result);
        return $row ?: null;
    }

    // Tambah siswa baru
    public function tambahSiswa(string $nama, string $nis, string $kelas): bool {
        $nama  = mysqli_real_escape_string($this->koneksi, $nama);
        $nis   = mysqli_real_escape_string($this->koneksi, $nis);
        $kelas = mysqli_real_escape_string($this->koneksi, $kelas);
        return (bool) mysqli_query($this->koneksi,
            "INSERT INTO siswa (nama, nis, kelas) VALUES ('$nama', '$nis', '$kelas')");
    }

    // Update data siswa
    public function editSiswa(int $id, string $nama, string $nis, string $kelas): bool {
        $nama  = mysqli_real_escape_string($this->koneksi, $nama);
        $nis   = mysqli_real_escape_string($this->koneksi, $nis);
        $kelas = mysqli_real_escape_string($this->koneksi, $kelas);
        return (bool) mysqli_query($this->koneksi,
            "UPDATE siswa SET nama='$nama', nis='$nis', kelas='$kelas' WHERE id=$id");
    }

    // Hapus siswa
    public function hapusSiswa(int $id): bool {
        return (bool) mysqli_query($this->koneksi,
            "DELETE FROM siswa WHERE id=$id");
    }
}
?>
