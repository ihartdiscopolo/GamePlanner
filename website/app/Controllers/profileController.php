<?php
require_once __DIR__ . "/../Modules/ProfileRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class ProfileController {
    private ProfileRepo $profileRepo;
    private Response $response;

    public function __construct() {
        $this->profileRepo = new ProfileRepo();
        $this->response = new Response();
    }

    public function logout() {
        unset($_SESSION['profileId']);
        $_SESSION['profileLoggedIn'] = false;
        reload("/");
    }

    public function create() {
        $username = $_POST['username'];
        $pin = $_POST['pin'];

        $this->response->validate([
            'username' => ['required' => 'Please enter a username.'],
            'pin' => ['min:3' => 'The pin needs to be at least 3 characters.'],
        ]);

        if(!$this->profileRepo->createProfile($_SESSION['householdId'], $username, $pin)) {
            respond("Couldn't create profile");
            reload();
            return;
        }
        
        reload("/");
    }

    public function login() {
        $id = $_POST['id'];
        $profile = $this->profileRepo->getProfileById($id);

        if($profile->Pin) {
            $pin = $_POST['pin'];
            $this->response->validate([
                'pin' => ['required' => 'Please enter a pin.'],
            ]);

            if($profile->Pin != $pin) {
                respond("Not the right pin.");
                reload("/");
                return;
            }
        }

        $_SESSION['profileLoggedIn'] = true;
        $_SESSION['profileId'] = $profile->Id;
        reload("/dashboard");
    }
}
