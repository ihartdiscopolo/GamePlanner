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

    public function getProfileById(int $profileId) {
        $sql = "SELECT * FROM profiles WHERE id = :id";
        return $this->db->run($sql, ['id' => $profileId])->fetch();
    }

    public function createProfile(int $householdId, string $username, string $pin) {
        $sql = "INSERT INTO profiles (Household_Id, Username, Pin) VALUES (:Household_Id, :Username, :Pin)";
        return $this->db->run($sql, ['Household_Id' => $householdId, 'Username' => $username, 'Pin' => $pin]);
    }

    public function editProfileById(int $id, array $data) {
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :$column";
            $params[$column] = $value;
        }
        $setString = implode(', ', $setClause);
        $sql = "UPDATE profiles SET $setString WHERE id = :id";
        $params['id'] = $id;
        return $this->db->run($sql, $params);
    }
}