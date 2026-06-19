<?php
class Siswa {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }

    // Tampilkan semua siswa
    public function tampil() {
        $query = "SELECT * FROM siswa ORDER BY kelas ASC, nama ASC";
        return $this->koneksi->query($query);
    }

    // Lihat satu siswa berdasarkan ID
    public function lihat(int $id) {
        $stmt = $this->koneksi->prepare("SELECT * FROM siswa WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cari siswa berdasarkan nama atau NIS
    public function cari(string $keyword) {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM siswa WHERE nama LIKE ? OR nis LIKE ? ORDER BY nama ASC"
        );
        $like = "%$keyword%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Tambah siswa baru
    public function tambah(string $nama, string $nis, string $kelas): bool {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO siswa (nama, nis, kelas) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nama, $nis, $kelas);
        return $stmt->execute();
    }

    // Update data siswa
    public function edit(int $id, string $nama, string $nis, string $kelas): bool {
        $stmt = $this->koneksi->prepare(
            "UPDATE siswa SET nama = ?, nis = ?, kelas = ? WHERE id = ?"
        );
        $stmt->bind_param("sssi", $nama, $nis, $kelas, $id);
        return $stmt->execute();
    }

    // Hapus siswa berdasarkan ID
    public function hapus(int $id): bool {
        $stmt = $this->koneksi->prepare("DELETE FROM siswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Hitung total siswa (opsional: filter per kelas)
    public function hitung(string $kelas = ''): int {
        if ($kelas !== '') {
            $stmt = $this->koneksi->prepare(
                "SELECT COUNT(*) AS total FROM siswa WHERE kelas = ?"
            );
            $stmt->bind_param("s", $kelas);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
        } else {
            $row = $this->koneksi->query(
                "SELECT COUNT(*) AS total FROM siswa"
            )->fetch_assoc();
        }
        return (int) ($row['total'] ?? 0);
    }

    // Ambil daftar kelas unik (untuk dropdown/filter)
    public function daftarKelas(): array {
        $result = $this->koneksi->query(
            "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC"
        );
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row['kelas'];
        }
        return $list;
    }

    // Get siswa by username
    public function getSiswaByUsername(string $username) {
        $stmt = $this->koneksi->prepare("SELECT * FROM siswa WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    // Tambah identitas siswa (dengan username)
    public function tambahIdentitasSiswa(string $nama, string $nis, string $kelas, string $username): bool {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO siswa (nama, nis, kelas, username) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $nama, $nis, $kelas, $username);
        return $stmt->execute();
    }

    // Edit identitas siswa (dengan username)
    public function editIdentitasSiswa(string $nama, string $nis, string $kelas, string $username): bool {
        $stmt = $this->koneksi->prepare(
            "UPDATE siswa SET nama = ?, nis = ?, kelas = ? WHERE username = ?"
        );
        $stmt->bind_param("ssss", $nama, $nis, $kelas, $username);
        return $stmt->execute();
    }

    // Cek NIS ada untuk siswa selain diri sendiri
    public function cekNisAda(string $nis, string $exclude_username = ''): bool {
        $stmt = $this->koneksi->prepare("SELECT id FROM siswa WHERE nis = ? AND username != ?");
        $stmt->bind_param("ss", $nis, $exclude_username);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }
}
?>
