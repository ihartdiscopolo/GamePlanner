<?php
require_once __DIR__ . '/../Modules/tasksRepo.php';
require_once __DIR__ . '/../Core/class.response.php';
require_once __DIR__ . "/../Modules/ProfileRepo.php";

class TasksController {
    private TasksRepo $tasksRepo;
    private Response $response;
    private ProfileRepo $profileRepo;

    public function __construct() {
        $this->tasksRepo = new TasksRepo();
        $this->response = new Response();
        $this->profileRepo = new ProfileRepo();
    }

    public function create() {
        $name = trim($_POST['name'] ?? '');
        $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 1;
        $info = trim($_POST['info'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');
        $assignedTo = isset($_POST['assignedTo']) && $_POST['assignedTo'] !== '' ? (int) $_POST['assignedTo'] : null;
        $coins = isset($_POST['coins']) ? (int) $_POST['coins'] : 0;

        $this->response->validate([
            'name' => ['required' => 'Please enter a task name.'],
            'coins' => ['required' => 'Please enter how many coins the task is worth.'],
        ]);

        if (!$categoryId) {
            respond('Please choose a task category.');
            return;
        }

        if (!$this->tasksRepo->addTask($_SESSION['householdId'], $categoryId, $name, $info ?: null, $deadline ?: null, $assignedTo, $coins)) {
            respond('Unable to add task.');
            return;
        }

        reload('/tasks');
    }

    public function update() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $name = trim($_POST['name'] ?? '');
        $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 1;
        $info = trim($_POST['info'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');
        $assignedTo = isset($_POST['assignedTo']) && $_POST['assignedTo'] !== '' ? (int) $_POST['assignedTo'] : null;
        $coins = isset($_POST['coins']) ? (int) $_POST['coins'] : 0;

        $requiredText = "Please fill in all required fields.";
        $this->response->validate([
            'id' => ['required' => $requiredText],
            'name' => ['required' => $requiredText],
        ]);

        if (!$this->tasksRepo->updateTask($id, $categoryId, $name, $info ?: null, $deadline ?: null, $assignedTo, $coins)) {
            respond('Unable to update task.');
            return;
        }

        reload('/tasks');
    }

    public function delete() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if (!$id) {
            respond('Invalid task.');
            return;
        }

        if (!$this->tasksRepo->deleteTask($id)) {
            respond('Unable to delete task.');
            return;
        }

        reload('/tasks');
    }

    public function toggleComplete() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if (!$id) {
            respond('Invalid task.');
            return;
        }
        $profileId = $_SESSION['profileId'] ?? 0;
        if (!$this->tasksRepo->toggleComplete($id, $profileId)) {
            respond('Unable to update task status.');
            return;
        }

        $profile = $this->profileRepo->getProfileById($profileId);

        respond('', 'success', ['stats' => ['coins' => $profile->Coins,'tickets' => $profile->Tickets,]]);
    }
}
