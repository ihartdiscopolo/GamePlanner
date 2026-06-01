<?php
require_once __DIR__ . "/../Modules/HouseholdRepo.php";

class HouseholdController {
    private HouseholdRepo $householdRepo;

    public function __construct() {
        $this->householdRepo = new HouseholdRepo();
    }

    public function logout() {
        unset($_SESSION['userId']);
        $_SESSION['loggedIn'] = false;
        reload("/");
    }

    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];

        if (empty($name) || empty($password) || empty($email)) {
            $_SESSION['error'] = "Fill in all fields";
            reload("/household/register");
            return;
        }

        if ($password !== $repassword) {
            $_SESSION['error'] = "Passwords don't match";
            reload("/household/register");
            return;
        }

        if($this->householdRepo->getEmail($name)) {
            $_SESSION['error'] = "Email already connected to an acount.";
            reload("/household/register");
            return;
        }

        $acount = $this->householdRepo->getHouseholdByEmail($name);

        $_SESSION['loggedIn'] = true;
        $_SESSION['userId'] = $acount->Id;
        reload("/");
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Fill in all fields";
            reload("/household/login");
            return;
        }

        if(!$this->householdRepo->getEmail($email) ||
        !$this->householdRepo->getPassword($password)) {
            $_SESSION['error'] = "Wrong email, name or password";
            reload("http://gameplanner.test/household/login");
            return;
        }

        $acount = $this->householdRepo->getHouseholdByEmail($email);

        $_SESSION['loggedIn'] = true;
        $_SESSION['userId'] = $acount->Id;
        reload("/");
    }
}