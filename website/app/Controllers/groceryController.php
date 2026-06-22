<?php
require_once __DIR__ . "/../Modules/GroceryRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class GroceryController {
    private GroceryRepo $groceryRepo;
    private Response $response;

    public function __construct() {
        $this->groceryRepo = new GroceryRepo();
        $this->response = new Response();
    }

}
