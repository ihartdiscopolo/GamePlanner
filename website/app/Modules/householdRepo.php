<?php
require_once __DIR__ . '/../Core/class.database.php';

class HouseholdRepo {
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getHouseholdByEmail(string $email) {
        $sql = "SELECT * FROM households WHERE email = :email";
        return $this->db->run($sql, ['email' => $email])->fetch();
    }
}