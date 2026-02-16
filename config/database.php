<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "dashboard_mtsn_ngada";
    public $conn;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>
                    <h3><i class='bi bi-exclamation-triangle'></i> Error Koneksi Database!</h3>
                    <p><strong>Pesan:</strong> " . $this->conn->connect_error . "</p>
                    <p><strong>Solusi:</strong> Jalankan file <a href='install.php'>install.php</a> untuk membuat database</p>
                </div>
            ");
        }

        $this->conn->set_charset("utf8");
        return $this->conn;
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function prepare($sql)
    {
        return $this->conn->prepare($sql);
    }

    public function escape_string($string)
    {
        return $this->conn->real_escape_string($string);
    }

    public function insert_id()
    {
        return $this->conn->insert_id;
    }

    public function affected_rows()
    {
        return $this->conn->affected_rows;
    }
}

$db = new Database();
