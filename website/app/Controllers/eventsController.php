<?php
require_once __DIR__ . '/../Modules/eventsRepo.php';

class EventsController {
    private EventsRepo $eventsRepo;

    public function __construct() {
        $this->eventsRepo = new EventsRepo();
    }

    public function create() {
        $title = trim($_POST['title'] ?? '');
        $eventDate = trim($_POST['eventDate'] ?? '');
        $eventTime = trim($_POST['eventTime'] ?? '');
        $assignedTo = $_POST['assignedTo'] ?? 'everyone';
        $isEveryone = $assignedTo === 'everyone';
        $assignedToId = $isEveryone ? null : ((int) $assignedTo ?: null);

        if (!$title || !$eventDate) {
            respond('Event name and date are required.');
            return;
        }

        if (!$this->eventsRepo->addEvent(
            $_SESSION['householdId'],
            $_SESSION['profileId'],
            $title,
            $eventDate,
            $eventTime ?: null,
            $assignedToId,
            $isEveryone
        )) {
            respond('Unable to save event.');
            return;
        }

        reload('/calender');
    }

    public function getEvents() {
        header('Content-Type: application/json');
        echo json_encode($this->eventsRepo->getEventsByHouseholdId($_SESSION['householdId']));
        exit;
    }
}
