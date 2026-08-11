<?php

class Database {
    private $host = "localhost";
    private $db_name = "butbalanced";
    private $username = "root";
    private $password = "jdp96n";
    public $conn;

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
function getDb() {
    static $pdo = null;
    if ($pdo === null) {
        $database = new Database();
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
