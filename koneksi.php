<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db_name = "sistem data siswa";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
            if ($this->conn->connect_error) {
                die("Koneksi database gagal : " . $this->conn->connect_error);
            }
        } catch(Exception $exception) {
            die("Koneksi database gagal : " . $exception->getMessage());
        }
        return $this->conn;
    }
}

$database = new Database();
$koneksi = $database->getConnection();
?>