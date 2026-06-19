<?php
class Auth {
    private $koneksi;

    public function __construct($db) {
        $this->koneksi = $db;
    }

    public function login($username, $password) {
        $username = $this->koneksi->real_escape_string($username);
        $query = $this->koneksi->query("SELECT * FROM users WHERE username='$username'");
        if ($query && $query->num_rows > 0) {
            $data = $query->fetch_assoc();
            if (password_verify($password, $data['pw'])) {
                return $data;
            }
        }
        return false;
    }

    public function register($name, $username, $email, $password, $role = 'Siswa') {
        $username_safe = $this->koneksi->real_escape_string($username);
        $email_safe = $this->koneksi->real_escape_string($email);
        $name_safe = $this->koneksi->real_escape_string($name);
        
        $check = $this->koneksi->query("SELECT * FROM users WHERE username='$username_safe'");
        if ($check && $check->num_rows > 0) {
            return "Username sudah terdaftar!";
        }

        $check_email = $this->koneksi->query("SELECT * FROM users WHERE email='$email_safe'");
        if ($check_email && $check_email->num_rows > 0) {
            return "Email sudah terdaftar!";
        }

        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->koneksi->query("INSERT INTO users (name, username, email, pw, role) VALUES ('$name_safe', '$username_safe', '$email_safe', '$pass_hash', '$role')");
        
        if ($query) {
            return true;
        }
        return "Gagal mendaftar!";
    }
    public function gantiPassword($username, $new_password) {
        $username_safe = $this->koneksi->real_escape_string($username);
        $pass_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $query = $this->koneksi->query("UPDATE users SET pw='$pass_hash' WHERE username='$username_safe'");
        return $query ? true : false;
    }
}
?>
