<?php
require_once __DIR__ . '/../Core/class.database.php';

class GamesRepo {
    
    private Database $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    public function getGames() {
        $sql = "SELECT * FROM games";
        return $this->db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}