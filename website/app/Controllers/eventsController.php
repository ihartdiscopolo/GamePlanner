<?php
require_once __DIR__ . '/../Modules/eventsRepo.php';
require_once __DIR__ . '/../Modules/tasksRepo.php';

class EventsController {
    private EventsRepo $eventsRepo;
    private TasksRepo $tasksRepo;

    public function __construct() {
        $this->eventsRepo = new EventsRepo();
        $this->tasksRepo = new TasksRepo();
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

        $householdId = $_SESSION['householdId'];

        // Get regular events from the events table
        $events = $this->eventsRepo->getEventsByHouseholdId($householdId);

        // Get tasks with a deadline
        $tasks = $this->tasksRepo->getTasksWithDeadline($householdId);

        // Convert each task into an event‑like object
        foreach ($tasks as $task) {
            $events[] = [
                'id'          => 'task_' . $task['Id'],          // prefix to avoid collision
                'title'       => $task['Name'],
                'start'       => $task['Deadline'],
                'allDay'      => true,                           // no time, just date
                'className'   => 'task-event',                   // optional CSS class
                // Add any extra data you might want later
                'extendedProps' => [
                    'type' => 'task',
                    'taskId' => $task['Id'],
                ],
            ];
        }

        echo json_encode($events);
        exit;
    }
}
