<?php
class Database {
    protected static $instance;
    protected $db;

    private $host, $username,  $password,  $database;

    private function __construct() {
        $this->host = $_ENV['MYSQL_HOST'];
        $this->username = $_ENV['MYSQL_USERNAME'];
        $this->password = $_ENV['MYSQL_PASSWORD'];
        $this->database = $_ENV['MYSQL_DATABASE'];

        try {
            $this->db = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
            die();
        }
    }

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($query) {
        return $this->db->query($query);
    }

    public function run($sql, $args = [], $debug = false) {
        if (!$args) {
            if ($debug) {
                echo $sql;
            }
            return $this->query($sql);
        } elseif ($debug) {
            echo strtr($sql, $args);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }
}
