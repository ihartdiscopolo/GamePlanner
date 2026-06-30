<?php
require_once __DIR__ . '/../Core/class.database.php';
require_once __DIR__ . '/profileRepo.php';

class GroceryRepo {
    private Database $db;
    private bool $completedColumnChecked = false;
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

    public function getGroceriesById(int $id, ?int $categoryId = null, string $search = '', string $sortOrder = 'newest') {
        $sql = "SELECT * FROM grocery WHERE Household_Id = :id";
        $params = ['id' => $id];

        if ($categoryId && $categoryId > 0) {
            $sql .= " AND Category_Id = :categoryId";
            $params['categoryId'] = $categoryId;
        }

        if ($search !== '') {
            $sql .= " AND Name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $sortOrder = strtolower($sortOrder);
        switch ($sortOrder) {
            case 'oldest':
                $sql .= " ORDER BY DateAdded ASC";
                break;
            case 'alpha':
                $sql .= " ORDER BY Name ASC";
                break;
            case 'alpha_desc':
                $sql .= " ORDER BY Name DESC";
                break;
            default:
                $sql .= " ORDER BY DateAdded DESC";
                break;
        }

        $groceries = $this->db->run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);

        foreach($groceries as $index => $grocery) {
            $groceries[$index]['Completed'] = isset($grocery['Completed']) ? $grocery['Completed'] : 0;
            $profile = $this->profileRepo->getProfileById($grocery['Profile_Id']);
            $groceries[$index]['AddedBy'] = $profile->Username;
            $category = $this->getCategoryById($grocery['Category_Id']);
            $groceries[$index]['CategoryName'] = $category->Name;
        }
        return $groceries; 
    }

    private function ensureCompletedColumn(): bool {
        if ($this->completedColumnChecked) {
            return true;
        }

        $this->completedColumnChecked = true;
        $result = $this->db->run("SHOW COLUMNS FROM grocery LIKE 'Completed'")->fetch();
        if ($result) {
            return true;
        }

        try {
            $this->db->run("ALTER TABLE grocery ADD COLUMN Completed TINYINT(1) NOT NULL DEFAULT 0");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function togglePurchased(int $groceryId) {
        if (!$this->ensureCompletedColumn()) {
            return false;
        }

        $grocery = $this->getGroceryById($groceryId);
        if (!$grocery) {
            return false;
        }

        $completed = !empty($grocery->Completed) ? 0 : 1;
        $sql = "UPDATE grocery SET Completed = :completed WHERE Id = :id";
        return $this->db->run($sql, ['completed' => $completed, 'id' => $groceryId]);
    }

    public function addGrocery(int $householdId, int $profileId, string $name, string $specification, string $amount, int $categoryId) {
        $sql = "INSERT INTO grocery (Household_Id, Profile_Id, Name, Specification, Amount, Category_Id) VALUES (:householdId, :profileId, :name, :specification, :amount, :categoryId)";
        return $this->db->run($sql, ['householdId' => $householdId, 'profileId' => $profileId, 'name' => $name, 'specification' => $specification, 'amount' => $amount, 'categoryId' => $categoryId]);
    }

    public function deleteGrocery(int $groceryId) {
        $sql = "DELETE FROM grocery WHERE Id = :id";
        return $this->db->run($sql, ['id' => $groceryId]);
    }

    public function getGroceryById(int $id) {
        $sql = "SELECT * FROM grocery WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id])->fetch();
    }

    public function updateGrocery(int $groceryId, string $name, string $specification, string $amount, int $categoryId) {
        $sql = "UPDATE grocery SET Name = :name, Specification = :specification, Amount = :amount, Category_Id = :categoryId WHERE Id = :id";
        return $this->db->run($sql, ['id' => $groceryId, 'name' => $name, 'specification' => $specification, 'amount' => $amount, 'categoryId' => $categoryId]);
    }
}