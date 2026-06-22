<?php
require_once __DIR__ . '/../Core/class.database.php';
require_once __DIR__ . '/profileRepo.php';

class GroceryRepo {
    private Database $db;
    public ProfileRepo $profileRepo;

    public function __construct() {
        $this->db = Database::instance();
        $this->profileRepo = new ProfileRepo();
    }

    public function getCategoryById(int $id) {
        $sql = "SELECT * FROM grocerycategory WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id])->fetch();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM grocerycategory ORDER BY Name";
        return $this->db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGroceriesById(int $id) {
        $sql = "SELECT * FROM grocery WHERE Household_Id = :id";
        $groceries = $this->db->run($sql, ['id' => $id])->fetchAll(\PDO::FETCH_ASSOC);

        foreach($groceries as $index => $grocery) {
            $profile = $this->profileRepo->getProfileById($grocery['Profile_Id']);
            $groceries[$index]['AddedBy'] = $profile->Username;
            $category = $this->getCategoryById($grocery['Category_Id']);
            $groceries[$index]['CategoryName'] = $category->Name;
        }
        return $groceries; 
    }

    public function addGrocery(int $householdId, int $profileId, string $name, string $specification, string $amount, int $categoryId) {
        $sql = "INSERT INTO grocery (Household_Id, Profile_Id, Name, Specification, Amount, Category_Id) VALUES (:householdId, :profileId, :name, :specification, :amount, :categoryId)";
        return $this->db->run($sql, ['householdId' => $householdId, 'profileId' => $profileId, 'name' => $name, 'specification' => $specification, 'amount' => $amount, 'categoryId' => $categoryId]);
    }

    public function deleteGrocery(int $groceryId) {
        $sql = "DELETE FROM grocery WHERE Id = :id";
        return $this->db->run($sql, ['id' => $groceryId]);
    }
}