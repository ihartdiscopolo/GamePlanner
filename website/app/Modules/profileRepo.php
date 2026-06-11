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

    public function createProfile(int $householdId, string $username, string $pin) {
        $sql = "INSERT INTO profiles (Household_Id, Username, Pin) VALUES (:Household_Id, :Username, :Pin)";
        return $this->db->run($sql, ['Household_Id' => $householdId, 'Username' => $username, 'Pin' => $pin]);
    }
}