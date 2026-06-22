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

require_once("Controllers/GamesController.php");
$GamesController = new GamesController();

require_once("Controllers/GroceryController.php");
$GroceryController = new GroceryController();

// Repos
require_once("Modules/HouseholdRepo.php");
$HouseholdRepo = new HouseholdRepo();

require_once("Modules/profileRepo.php");
$ProfileRepo = new ProfileRepo();

require_once("Modules/gamesRepo.php");
$GamesRepo = new GamesRepo();

require_once("Modules/groceryRepo.php");
$GroceryRepo = new GroceryRepo();