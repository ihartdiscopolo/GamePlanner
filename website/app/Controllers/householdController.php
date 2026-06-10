<?php
require_once __DIR__ . "/../Modules/HouseholdRepo.php";

class HouseholdController {
    private HouseholdRepo $householdRepo;

    public function __construct() {
        $this->householdRepo = new HouseholdRepo();
    }

    public function logout() {
        unset($_SESSION['householdId']);
        $_SESSION['loggedIn'] = false;
        reload("/");
    }

    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];

        if (empty($name) || empty($email) || empty($password) || empty($repassword)) {
            $_SESSION['error'] = "Fill in all fields";
            respond("Fill in all fields");
            reload();
            return;
        }

        if ($password != $repassword) {
            respond("Passwords don't match");
            reload();
            return;
        }

        if($this->householdRepo->getEmail($name)) {
            respond("Email already connected to an acount.");
            reload();
            return;
        }

        if(!$this->householdRepo->createHousehold($name, $email, password_hash($password, PASSWORD_BCRYPT))) {
            respond("Couldn't create acount.");
            reload();
            return;
        }

        $account = $this->householdRepo->getHouseholdByEmail($email);

        $_SESSION['loggedIn'] = true;
        $_SESSION['householdId'] = $account->Id;
        reload("/");
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            respond("Fill in all fields");
            reload();
            return;
        }

        if(!$this->householdRepo->getEmail($email) ||
        !$this->householdRepo->verifyPassword($email, $password)) {
            respond("Wrong email or password");
            reload();
            return;
        }
        $account = $this->householdRepo->getHouseholdByEmail($email);

        $_SESSION['loggedIn'] = true;
        $_SESSION['householdId'] = $account->Id;
        reload("/");
    }
}