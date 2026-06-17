<?php
// Core
require_once("Core/class.router.php");
$router = new Router();

require_once("Core/class.response.php");
$response = new Response();

// Controllers
require_once("Controllers/HouseholdController.php");
$HouseholdController = new HouseholdController();

require_once("Controllers/ProfileController.php");
$ProfileController = new ProfileController();

require_once("Modules/HouseholdRepo.php");
$HouseholdRepo = new HouseholdRepo();