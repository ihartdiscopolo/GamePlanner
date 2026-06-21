<?php
require_once __DIR__ . "/../Modules/gamesRepo.php";
require_once __DIR__ . "/../Core/class.response.php";

class GamesController {
    private GamesRepo $gamesRepo;
    private Response $response;

    public function __construct() {
        $this->gamesRepo = new GamesRepo();
        $this->response = new Response();
    }
}
