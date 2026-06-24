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
        $_SESSION['gameClosed'] = true;
        session_write_close();
        echo json_encode(['success' => true]);
    }

    public function gameStatusStream() {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        while (true) {
            session_start();
            if (!empty($_SESSION['gameClosed'])) {
                unset($_SESSION['gameClosed']);
                session_write_close();
                echo "data: closed\n\n";
                ob_flush();
                flush();
                break;
            }
            session_write_close();
            sleep(1);
        }
    }

    public function getData() {
        $body = file_get_contents('php://input');
        return json_decode($body, true);
    }
}