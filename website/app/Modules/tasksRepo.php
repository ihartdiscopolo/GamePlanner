<?php
require_once __DIR__ . '/../Core/class.database.php';

class TasksRepo {
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM taskcategory ORDER BY Name";
        return $this->db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTasksByHouseholdId(int $householdId, ?int $categoryId = null, string $sortOrder = 'newest') {
        $sql = "SELECT * FROM tasks WHERE Household_Id = :householdId";
        $params = ['householdId' => $householdId];

        if ($categoryId && $categoryId > 0) {
            $sql .= " AND Category_Id = :categoryId";
            $params['categoryId'] = $categoryId;
        }

        switch ($sortOrder) {
            case 'oldest':
                $sql .= " ORDER BY Deadline ASC";
                break;
            case 'alpha':
                $sql .= " ORDER BY Name ASC";
                break;
            case 'alpha_desc':
                $sql .= " ORDER BY Name DESC";
                break;
            default:
                $sql .= " ORDER BY Deadline DESC";
                break;
        }

        $tasks = $this->db->run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($tasks as $index => $task) {
            $category = $this->getCategoryById($task['Category_Id']);
            $tasks[$index]['CategoryName'] = $category->Name;
        }

        return $tasks;
    }

    public function getCategoryById(int $categoryId) {
        $sql = "SELECT * FROM taskcategory WHERE Id = :id";
        return $this->db->run($sql, ['id' => $categoryId])->fetch();
    }

    public function addTask(int $householdId, int $categoryId, string $name, ?string $info, ?string $deadline) {
        $sql = "INSERT INTO tasks (Household_Id, Category_Id, Name, Info, Deadline) VALUES (:householdId, :categoryId, :name, :info, :deadline)";
        return $this->db->run($sql, [
            'householdId' => $householdId,
            'categoryId' => $categoryId,
            'name' => $name,
            'info' => $info,
            'deadline' => $deadline,
        ]);
    }

    public function getTaskById(int $id) {
        $sql = "SELECT * FROM tasks WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id])->fetch();
    }

    public function updateTask(int $id, int $categoryId, string $name, ?string $info, ?string $deadline) {
        $sql = "UPDATE tasks SET Category_Id = :categoryId, Name = :name, Info = :info, Deadline = :deadline WHERE Id = :id";
        return $this->db->run($sql, [
            'id' => $id,
            'categoryId' => $categoryId,
            'name' => $name,
            'info' => $info,
            'deadline' => $deadline,
        ]);
    }

    public function deleteTask(int $id) {
        $sql = "DELETE FROM tasks WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id]);
    }

    public function toggleComplete(int $id) {
        $sql = "UPDATE tasks SET Completed = NOT Completed WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id]);
    }
}
