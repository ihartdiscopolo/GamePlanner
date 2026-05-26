<?php
class ProfileController {
    private ProfileRepo $profileRepo;

    public function __construct() {
        $this->profileRepo = new ProfileRepo();
    }
}
