<?php
require_once __DIR__ . "/../Modules/ProfileRepo.php";

class ProfileController {
    private ProfileRepo $profileRepo;

    public function __construct() {
        $this->profileRepo = new ProfileRepo();
    }

    public function create() {
        $username = $_POST['username'];
        $pin = $_POST['pin'];

        if(empty($username)) {
            respond("Fill in all fields");
            reload();
            return;
        }

        if(!$this->profileRepo->createProfile($_SESSION['householdId'], $username, $pin)) {
            respond("Couldn't create profile");
            reload();
            return;
        }
        
        reload("/");
    }

    public function login() {

        $_SESSION['profileLoggedIn'] = true;
        $_SESSION['profileId'] = $profile->Id;
        reload("/dashboard");
    }
}
