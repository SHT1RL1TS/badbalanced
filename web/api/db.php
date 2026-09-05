<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST'];
        $this->db_name  = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
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
    private $host1;
    private $db_name1;
    private $username1;
    private $password1;
    public $con1;

    public function __construct() {
        $this->host1     = $_ENV['DB_HOST'];
        $this->db_name1  = $_ENV['POSTGRES_DB'];
        $this->username1 = $_ENV['DB_USER'];
        $this->password1 = $_ENV['DB_PASSWORD'];
    }

    public function getConnection() {
        $this->con1 = null;
        try {
            $this->con1 = new PDO("pgsql:host=" . $this->host1 . ";dbname=" . $this->db_name1, $this->username1, $this->password1);
            $this->con1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->con1;
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
    static $pdo1 = null;
    if ($pdo1 === null) {
        $database1 = new Database1();
        $pdo1 = $database1->getConnection();
    }
    return $pdo1;
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
