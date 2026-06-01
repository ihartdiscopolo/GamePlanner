<?php
require_once __DIR__ . '/../Core/class.database.php';

class HouseholdRepo {
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getName(string $name) {
        $sql = "SELECT name FROM households WHERE name = :name";
        return $this->db->run($sql, ['name' => $name])->fetch();
    }

    public function getEmail(string $email) {
        $sql = "SELECT email FROM households WHERE email = :email";
        return $this->db->run($sql, ['email' => $email])->fetch();
    }

    public function getPassword(string $password) {
        $sql = "SELECT password FROM households WHERE password = :password";
        return $this->db->run($sql, ['password' => $password])->fetch();
    }

    public function getHouseholdByEmail(string $email) {
        $sql = "SELECT * FROM households WHERE email = :email";
        return $this->db->run($sql, ['email' => $email])->fetch();
    }
}