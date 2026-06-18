<?php
require_once __DIR__ . "/../Modules/HouseholdRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class HouseholdController {
    private HouseholdRepo $householdRepo;
    private Response $response;

    public function __construct() {
        $this->householdRepo = new HouseholdRepo();
        $this->response = new Response();
    }

    public function logout() {
        unset($_SESSION['householdId']);
        $_SESSION['householdLoggedIn'] = false;
        reload("/");
    }

    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];

        $requiredText = "Fill in all fields.";
        $this->response->validate([
            'name' => ['required' => $requiredText],
            'email' => ['required' => $requiredText, 'email' => 'Please use a valid email.'],
            'password' => ['required' => $requiredText, 'min:6' => 'Your password needs to be at least 6 characters'],
            'repassword' => ['required' => $requiredText, 'min:6' => 'Your password needs to be at least 6 characters']
        ]);

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

        $_SESSION['householdLoggedIn'] = true;
        $_SESSION['householdId'] = $account->Id;
        reload("/");
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $requiredText = "Fill in all fields.";
        $this->response->validate([
            'email' => ['required' => $requiredText],
            'password' => ['required' => $requiredText]
        ]);

        if(!$this->householdRepo->getEmail($email) ||
        !$this->householdRepo->verifyPassword($email, $password)) {
            respond("Wrong email or password");
            reload();
            return;
        }
        $account = $this->householdRepo->getHouseholdByEmail($email);

        $_SESSION['householdLoggedIn'] = true;
        $_SESSION['householdId'] = $account->Id;
        reload("/");
    }
}