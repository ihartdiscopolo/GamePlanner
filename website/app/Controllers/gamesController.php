<?php
require_once __DIR__ . "/../Modules/gamesRepo.php";
require_once __DIR__ . "/profileController.php";
require_once __DIR__ . "/../Core/class.response.php";

class GamesController {
    private GamesRepo $gamesRepo;
    private ProfileController $profileController;
    private Response $response;

    public function __construct() {
        $this->gamesRepo = new GamesRepo();
        $this->profileController = new ProfileController();
        $this->response = new Response();
    }

    public function gameStart() {
        $data = $this->getData();

        $game = $this->gamesRepo->getGameByName($data['name']);
        $this->profileController->payCoins($game->Cost);

        header('Content-Type: application/json');
        echo json_encode(['name' => $data['name']]);
    }

    public function gameEnd() {
        $data = $this->getData();

        $this->profileController->getTickets($data['tickets']);

        header('Content-Type: application/json');
        echo json_encode(['tickets' => $data['tickets']]);
    }

    public function gameClose() {
        return reload("/games");
    }

    public function getData() {
        $body = file_get_contents('php://input');
        return json_decode($body, true);
    }
}