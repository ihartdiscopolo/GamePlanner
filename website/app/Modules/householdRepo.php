<?php
require_once __DIR__ . '/../Core/class.database.php';
require_once __DIR__ . '/profileRepo.php';

class HouseholdRepo {
    private Database $db;
    public ProfileRepo $profileRepo;

    public function __construct() {
        $this->db = Database::instance();
        $this->profileRepo = new ProfileRepo();
    }

    public function getName(string $name) {
        $sql = "SELECT name FROM households WHERE name = :name";
        return $this->db->run($sql, ['name' => $name])->fetch();
    }

    public function getEmail(string $email) {
        $sql = "SELECT email FROM households WHERE email = :email";
        return $this->db->run($sql, ['email' => $email])->fetch();
    }

    public function getHouseholdByEmail(string $email) {
        $sql = "SELECT * FROM households WHERE email = :email";
        return $this->db->run($sql, ['email' => $email])->fetch();
    }

    public function getHouseholdById(mixed $id) {
        $sql = "SELECT * FROM households WHERE id = :id";
        return $this->db->run($sql, ['id' => $id])->fetch();
    }

    public function getProfilesByHouseholdId(int $id) {
        $sql = "SELECT * FROM profiles WHERE Household_Id = :id";
        return $this->db->run($sql, ['id' => $id])->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createHousehold(string $name, string $email, string $password) {
        $sql = "INSERT INTO households (name, email, password) VALUES (:name, :email, :password)";
        return $this->db->run($sql, ['name' => $name, 'email' => $email, 'password' => $password]);
    }

    public function verifyPassword(string $email, string $password) {
        $household = $this->getHouseholdByEmail($email);
        return password_verify($password, $household->Password);
    }

    public function editHouseholdById(int $id, array $data) {
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :$column";
            $params[$column] = $value;
        }
        $setString = implode(', ', $setClause);
        $sql = "UPDATE households SET $setString WHERE id = :id";
        $params['id'] = $id;
        return $this->db->run($sql, $params);
    }
}