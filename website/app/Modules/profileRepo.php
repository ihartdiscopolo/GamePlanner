<?php
require_once __DIR__ . '/../Core/class.database.php';

class ProfileRepo {
    
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getProfilesByHouseholdId(string $householdId) {
        $sql = "SELECT * FROM profiles WHERE household_id = :household_id";
        return $this->db->run($sql, ['household_id' => $householdId])->fetch();
    }
}