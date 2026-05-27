<?php
require_once __DIR__ . "/../Modules/HouseholdRepo.php";

class HouseholdController {
    private HouseholdRepo $householdRepo;

    public function __construct() {
        $this->householdRepo = new HouseholdRepo();
    }

    public function login() {
        $name = $_POST['username'] ?? '';
        // $email = $_POST['email'];
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Fill in all fields";
            return;
        }

        if($this->householdRepo->getName($name) ||
        $this->householdRepo->getEmail($username) ||
        $this->householdRepo->getPassword($password)) {
            $_SESSION['error'] = "Wrong email, name or password";
            return;
        }

        $_SESSION['loggedIn'] = true;
    }
}