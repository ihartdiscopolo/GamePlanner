<?php
require_once __DIR__ . "/../Modules/ProfileRepo.php";
require_once __DIR__ . "/../Modules/HouseholdRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class SettingsController {
    private Response $response;
    private ProfileRepo $profileRepo;
    private HouseholdRepo $householdRepo;

    public function __construct() {
        $this->response = new Response();
        $this->profileRepo = new ProfileRepo();
        $this->householdRepo = new HouseholdRepo();
    }

    public function formHandler() {
        $field = $_POST['field'] ?? null;

        switch ($field) {
            case 'householdName':     $this->updateHouseholdName();     break;
            case 'householdEmail':    $this->updateHouseholdEmail();    break;
            case 'householdDelete':   $this->deleteHousehold();         break;
            case 'profilePassword':   $this->updateHouseholdPassword(); break;
            case 'profileUsername':   $this->updateProfileUsername();   break;
            case 'profilePin':        $this->updateProfilePin();        break;
            case 'profileDelete':     $this->deleteProfile();           break;
            default: 
                respond("Wrong input field");
            break;
        }
    }

    public function updateHouseholdName() {
        $name = $_POST['name'];

        $this->response->validate([
            'name' => ['required' => "Please fill in a name"],
        ]);

        if(!$this->householdRepo->edithouseholdById($_SESSION['householdId'], ['Name' => $name])) {
            respond("Couldn't update name.");
            return;
        }
        respond("Name updated!", "success");
    }

    public function updateHouseholdEmail() {
        $email = $_POST['email'];

        $this->response->validate([
            'email' => ['required' => "Please fill in a email.", 'email' => 'Please use a valid email.'],
        ]);

        if($this->householdRepo->getEmail($email)) {
            respond("Email already connected to an acount.");
            return;
        }

        if(!$this->householdRepo->edithouseholdById($_SESSION['householdId'], ['Email' => $email])) {
            respond("Couldn't create acount.");
            return;
        }
        respond("Email updated!", "success");
    }

    public function updateHouseholdPassword() {
        $oldpassword = $_POST['oldpassword'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];

        $requiredText = "Please fill in all required fields.";
        $this->response->validate([
            'oldpassword' => ['required' => $requiredText],
            'password' => ['required' => $requiredText, 'min:6' => 'Your new password needs to be at least 6 characters'],
            'repassword' => ['required' => $requiredText, 'min:6' => 'Your new password needs to be at least 6 characters']
        ]);

        if (!$this->householdRepo->verifyPassword(null, $oldpassword)) {
            respond("Old password is wrong");
            return;
        }

        if ($password != $repassword) {
            respond("New passwords don't match");
            return;
        }

        if(!$this->householdRepo->edithouseholdById($_SESSION['householdId'], ['Password' => password_hash($password, PASSWORD_BCRYPT)])) {
            respond("Couldn't update password.");
            return;
        }
        respond("Password updated!", "success");
    }

    public function deleteHousehold() {
        $password = $_POST['password'];

        $this->response->validate([
            'password' => ['required' => "Please fill in your password."],
        ]);

        if (!$this->householdRepo->verifyPassword(null, $password)) {
            respond("Password is wrong.");
            return;
        }

        if (!$this->householdRepo->deleteHouseholdById($_SESSION['householdId'])) {
            respond("Couldn't delete household.");
            return;
        }

        session_destroy();
        reload("/");
    }

    public function updateProfileUsername() {
        $username = $_POST['username'];

        $this->response->validate([
            'username' => ['required' => "Please fill in a username"],
        ]);

        if(!$this->profileRepo->editProfileById($_SESSION['profileId'], ['Username' => $username])) {
            respond("Couldn't update username.");
            return;
        }
        respond("Username updated!", "success");
    }

    public function updateProfilePin() {
        $pin = $_POST['pin'];
        $repin = $_POST['repin'];

        $profile = $this->profileRepo->getProfileById($_SESSION['profileId']);

        $this->response->validate([
            'pin' => ['min:3' => 'The new pin needs to be at least 3 characters, or nothing.'],
            'repin' => ['min:3' => 'The new pin needs to be at least 3 characters, or nothing.']
        ]);

        if($profile->Pin) {
            $oldpin = $_POST['oldpin'];
            $this->response->validate([
                'oldpin' => ['required' => "Please fill in the old pin."],
            ]);
            if($profile->Pin != $oldpin) {
                respond("Old pin is wrong.");
                return;
            }
        }

        if ($pin != $repin) {
            respond("New pins don't match");
            return;
        }

        if(!$this->profileRepo->editProfileById($_SESSION['profileId'], ['Pin' => $pin])) {
            respond("Couldn't update pin.");
            return;
        }
        respond("pin updated!", "success");
    }

    public function deleteProfile() {
        $profile = $this->profileRepo->getProfileById($_SESSION['profileId']);

        if ($profile->Pin) {
            $pin = $_POST['pin'];

            $this->response->validate([
                'pin' => ['required' => "Please fill in your pin."],
            ]);

            if ($profile->Pin != $pin) {
                respond("Pin is wrong.");
                return;
            }
        }

        if (!$this->profileRepo->deleteProfileById($_SESSION['profileId'])) {
            respond("Couldn't delete profile.");
            return;
        }

        unset($_SESSION['profileId']);
        unset($_SESSION['profileLoggedIn']);
        reload("/");
    }
}
