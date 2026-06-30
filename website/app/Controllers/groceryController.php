<?php
require_once __DIR__ . "/../Modules/GroceryRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class GroceryController {
    private GroceryRepo $groceryRepo;
    private Response $response;

    public function __construct() {
        $this->groceryRepo = new GroceryRepo();
        $this->response = new Response();
    }

    public function create() {
        $name = trim($_POST['name'] ?? '');
        $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 1;
        $amount = trim($_POST['amount'] ?? '');
        $specification = trim($_POST['specification'] ?? '');

        $this->response->validate([
            'name' => ['required' => 'Please enter an item name.'],
        ]);

        $result = $this->groceryRepo->addGrocery(
            $_SESSION['householdId'],
            $_SESSION['profileId'],
            $name,
            $specification,
            $amount,
            $categoryId
        );

        if (!$result) {
            respond('Unable to add grocery item.');
            return;
        }

        reload('/grocerylist');
    }

    public function edit() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $name = trim($_POST['name'] ?? '');
        $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 1;
        $amount = trim($_POST['amount'] ?? '');
        $specification = trim($_POST['specification'] ?? '');

        $requiredText = "Please fill in all required fields.";
        $this->response->validate([
            'id' => ['required' => $requiredText],
            'name' => ['required' => $requiredText],
        ]);

        if (!$this->groceryRepo->updateGrocery($id, $name, $specification, $amount, $categoryId)) {
            respond('Unable to update grocery item.');
            return;
        }

        reload('/grocerylist');
    }

    public function delete() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if (!$id) {
            respond('Invalid grocery item.');
            return;
        }

        if (!$this->groceryRepo->deleteGrocery($id)) {
            respond('Unable to delete grocery item.');
            return;
        }

        respond('deleted', 'success');
    }

    public function togglePurchased() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if (!$id) {
            respond('Invalid grocery item.');
            return;
        }

        if (!$this->groceryRepo->togglePurchased($id)) {
            respond('Unable to update grocery item status.');
            return;
        }

        reload('/dashboard');
    }
}
