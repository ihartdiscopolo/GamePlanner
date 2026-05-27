<?php
require_once __DIR__ . "/../Modules/ProfileRepo.php";

class ProfileController {
    private ProfileRepo $profileRepo;

    public function __construct() {
        $this->profileRepo = new ProfileRepo();
    }
}
