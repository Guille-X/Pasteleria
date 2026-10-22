<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $ssl_ca;
    public $conn;

    public function __construct() {
        $this->host     = getenv('DB_HOST');
        $this->db_name  = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
        $this->ssl_ca   = getenv('MYSQL_ATTR_SSL_CA');
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_CA       => $this->ssl_ca
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            error_log("Error de conexión: " . $exception->getMessage());
            die("Lo sentimos, no se pudo conectar a la base de datos.");
        }
        return $this->conn;
    }
}
?>
