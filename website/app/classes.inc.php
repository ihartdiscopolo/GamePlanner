<?php
// Core
require_once("Core/class.router.php");
$router = new Router();

// Controllers
require_once("Controllers/HouseholdController.php");
$HouseholdController = new HouseholdController();

require_once("Controllers/ProfileController.php");
$ProfileController = new ProfileController();