<?php
class dbConnection
{
    private $address;
    private $database;
    private $username;
    private $password;
    private $connection;
    private $login_user;

    public function __construct($database, $address = '127.0.0.1', $username = 'root', $password = '')
    {
        $this->database = $database;
        $this->address = $address;
        $this->username = $username;
        $this->password = $password;

        try {
            $conn = new PDO("mysql:host=" . $this->address . ";dbname=" . $this->database, $this->username, $this->password);
        } catch (PDOException $e) {
            // attempt to retry the connection after some timeout for example
            return 'Koneksi database gagal';
            exit();
        }
        $this->connection = $conn;
    }

    public function get($table, $where = array()) {}

    public function get_one($table, $where) {}

    public function query($query) {}

    public function insert($table, $value) {}

    public function is_login() {}

    public function attemp($email, $password)
    {
        // check auth user (DB / authentication lainnya)
        $query = "SELECT * from users where email = '$email' LIMIT 1";
        $stmt = $this->connection->query($query);
        $data_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data_user) {
            // username tidak ditemukan
            return false;
        }

        if (!password_verify($password, $data_user['password'])) {
            // password salah
            return false;
        }

        return $data_user;

        // jika auth diatas terlewati --> gagal --> kembalikan ke halaman login
        return false;
    }
}
