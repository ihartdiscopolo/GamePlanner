<?php
require_once __DIR__ . '/../Core/class.database.php';

class EventsRepo {
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getEventsByHouseholdId(int $householdId): array {
        $sql = "SELECT e.Id, e.Title, e.EventDate, e.EventTime, e.AssignedTo, e.IsEveryone,\n                       p.Username AS AssignedName\n                FROM events e\n                LEFT JOIN profiles p ON e.AssignedTo = p.Id\n                WHERE e.Household_Id = :householdId\n                ORDER BY e.EventDate, e.EventTime";

        $events = $this->db->run($sql, ['householdId' => $householdId])->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function(array $event) {
            $start = $event['EventDate'];
            if (!empty($event['EventTime'])) {
                $start .= 'T' . substr($event['EventTime'], 0, 5);
            }

            return [
                'id' => (int) $event['Id'], 
                'title' => $event['Title'],
                'start' => $start,
                'eventDate' => $event['EventDate'],
                'eventTime' => $event['EventTime'] ?: '',
                'assignedTo' => $event['AssignedTo'] ? (int) $event['AssignedTo'] : null,
                'assignedName' => $event['IsEveryone'] ? 'All members' : ($event['AssignedName'] ?: 'Unassigned'),
                'isEveryone' => (bool) $event['IsEveryone'],
            ];
        }, $events);
    }

    public function addEvent(int $householdId, int $profileId, string $title, string $eventDate, ?string $eventTime = null, ?int $assignedTo = null, bool $isEveryone = false) {
        $sql = "INSERT INTO events (Household_Id, Profile_Id, Title, EventDate, EventTime, AssignedTo, IsEveryone)\n                VALUES (:householdId, :profileId, :title, :eventDate, :eventTime, :assignedTo, :isEveryone)";

        return $this->db->run($sql, [
            'householdId' => $householdId,
            'profileId' => $profileId,
            'title' => $title,
            'eventDate' => $eventDate,
            'eventTime' => $eventTime ?: null,
            'assignedTo' => $assignedTo,
            'isEveryone' => $isEveryone ? 1 : 0,
        ]);
    }

    public function getEventById(int $id) {
        $sql = "SELECT e.Id, e.Title, e.EventDate, e.EventTime, e.AssignedTo, e.IsEveryone,\n                       p.Username AS AssignedName\n                FROM events e\n                LEFT JOIN profiles p ON e.AssignedTo = p.Id\n                WHERE e.Id = :id";

        return $this->db->run($sql, ['id' => $id])->fetch(\PDO::FETCH_ASSOC);
    }
}
