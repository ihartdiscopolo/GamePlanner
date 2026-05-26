<?php
class HouseholdController {
    private HouseholdRepo $householdRepo;

    public function __construct() {
        $this->householdRepo = new HouseholdRepo();
    }
}