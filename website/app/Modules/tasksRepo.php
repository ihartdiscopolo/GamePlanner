<?php
require_once __DIR__ . '/../Core/class.database.php';
require_once __DIR__ . '/profileRepo.php';

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
            if (!empty($task['Assigned_To'])) {
                $profile = $this->getProfileById($task['Assigned_To']);
                $tasks[$index]['AssignedToName'] = $profile->Username ?? 'Unknown';
            } else {
                $tasks[$index]['AssignedToName'] = 'Unassigned';
            }
        }

        return $tasks;
    }

    private function getProfileById(int $profileId) {
        $sql = "SELECT * FROM profiles WHERE Id = :id";
        return $this->db->run($sql, ['id' => $profileId])->fetch();
    }

    public function getHouseholdMembers(int $householdId) {
        $sql = "SELECT * FROM profiles WHERE Household_Id = :householdId ORDER BY Username";
        return $this->db->run($sql, ['householdId' => $householdId])->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $categoryId) {
        $sql = "SELECT * FROM taskcategory WHERE Id = :id";
        return $this->db->run($sql, ['id' => $categoryId])->fetch();
    }

    public function addTask(int $householdId, int $categoryId, string $name, ?string $info, ?string $deadline, ?int $assignedTo = null, int $coins = 0) {
        $sql = "INSERT INTO tasks (Household_Id, Category_Id, Name, Info, Deadline, Assigned_To, Coins) VALUES (:householdId, :categoryId, :name, :info, :deadline, :assignedTo, :coins)";
        return $this->db->run($sql, [
            'householdId' => $householdId,
            'categoryId' => $categoryId,
            'name' => $name,
            'info' => $info,
            'deadline' => $deadline,
            'assignedTo' => $assignedTo,
            'coins' => $coins,
        ]);
    }

    public function getTaskById(int $id) {
        $sql = "SELECT * FROM tasks WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id])->fetch();
    }

    public function updateTask(int $id, int $categoryId, string $name, ?string $info, ?string $deadline, ?int $assignedTo = null, int $coins = 0) {
        $sql = "UPDATE tasks SET Category_Id = :categoryId, Name = :name, Info = :info, Deadline = :deadline, Assigned_To = :assignedTo, Coins = :coins WHERE Id = :id";
        return $this->db->run($sql, [
            'id' => $id,
            'categoryId' => $categoryId,
            'name' => $name,
            'info' => $info,
            'deadline' => $deadline,
            'assignedTo' => $assignedTo,
            'coins' => $coins,
        ]);
    }

    public function deleteTask(int $id) {
        $sql = "DELETE FROM tasks WHERE Id = :id";
        return $this->db->run($sql, ['id' => $id]);
    }

    public function toggleComplete(int $id, int $actorProfileId) {
        $task = $this->getTaskById($id);
        if (!$task) return false;

        // Determine current completed state (0/1)
        $currentlyCompleted = !empty($task->Completed);

        if (!$currentlyCompleted) {
            // Mark completed and record who completed it, then award coins to the actor
            $sql = "UPDATE tasks SET Completed = 1, Completed_By = :actor WHERE Id = :id";
            $res = $this->db->run($sql, ['actor' => $actorProfileId, 'id' => $id]);
            if ($res && !empty($task->Coins)) {
                $profileRepo = new ProfileRepo();
                $profile = $profileRepo->getProfileById($actorProfileId);
                $newBalance = ($profile->Coins ?? 0) + (int)$task->Coins;
                $profileRepo->editProfileById($actorProfileId, ['Coins' => $newBalance]);
            }
            return $res;
        } else {
            // Uncomplete: deduct coins from the original completer (if present) and clear Completed_By
            $originalCompleter = $task->Completed_By ?? null;
            $sql = "UPDATE tasks SET Completed = 0, Completed_By = NULL WHERE Id = :id";
            $res = $this->db->run($sql, ['id' => $id]);
            if ($res && !empty($originalCompleter) && !empty($task->Coins)) {
                $profileRepo = new ProfileRepo();
                $profile = $profileRepo->getProfileById((int)$originalCompleter);
                $newBalance = ($profile->Coins ?? 0) - (int)$task->Coins;
                $profileRepo->editProfileById((int)$originalCompleter, ['Coins' => $newBalance]);
            }
            return $res;
        }
    }
}
