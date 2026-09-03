<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST']     ?? 'localhost';
        $this->db_name  = $_ENV['DB_NAME']      ?? 'butbalanced';
        $this->username = $_ENV['DB_USER']      ?? 'root';
        $this->password = $_ENV['DB_PASSWORD']   ?? 'jdp96n';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}

class Database1 {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $con;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST']     ?? 'localhost';
        $this->db_name  = 'bb';
        $this->username = $_ENV['DB_USER']      ?? 'root';
        $this->password = $_ENV['DB_PASSWORD']   ?? 'jdp96n';
    }

    public function getConnection() {
        $this->con = null;
        try {
            $this->con = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->con;
    }
}

function getDb() {
    static $pdo = null;
    if ($pdo === null) {
        $database = new Database();
        $pdo = $database->getConnection();
    }
    return $pdo;
}

function getDb_() {
    static $pdo = null;
    if ($pdo === null) {
        $database = new Database1();
        $pdo = $database->getConnection();
    }
    return $pdo;
}

$input = json_decode(file_get_contents('php://input'), true);
if(isset($input['action']) && $input['action'] === 'getAllHeroes') {
    $db = getDb();
    $query = "SELECT *
                FROM heroes
                ORDER BY name_hero";
    $stmt = $db->prepare($query);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
