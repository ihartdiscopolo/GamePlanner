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

    public function createProfile(int $householdId, string $username, string $pin, int $creator) {
        $sql = "INSERT INTO profiles (Household_Id, Username, Pin, Is_Creator, Can_Edit_Tasks, Can_Edit_Grocery) VALUES (:Household_Id, :Username, :Pin, :Is_Creator, :Can_Edit_Tasks, :Can_Edit_Grocery)";
        return $this->db->run($sql, ['Household_Id' => $householdId, 'Username' => $username, 'Pin' => $pin, 'Is_Creator' => $creator, 'Can_Edit_Tasks' => true, 'Can_Edit_Grocery' => true]);
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

    public function editPermisions(int $profileId, string $permission, int $value) {
        $columns = [
            'tasks'      => 'Can_Edit_Tasks',
            'groceries'  => 'Can_Edit_Grocery',
            'household'  => 'Can_Edit_Household',
            'permisions' => 'Can_Edit_Permisions',
        ];

        if (!isset($columns[$permission])) {
            return false;
        }

        $column = $columns[$permission];
        $sql = "UPDATE profiles SET $column = :value WHERE Id = :id";
        return $this->db->run($sql, ['value' => $value, 'id' => $profileId]);
    }

    public function deleteProfileById(int $id) {
        $sql = "DELETE FROM profiles WHERE id = :id";
        return $this->db->run($sql, ['id' => $id]);
    }
}