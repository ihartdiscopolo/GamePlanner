<?php
require_once __DIR__ . '/../Core/class.database.php';

class ShopRepo
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::instance();
    }

    /**
     * Get all shop items
     * @return array List of items
     */
    public function getItems(): array
    {
        $sql = "SELECT * FROM shop_items ORDER BY id";
        return $this->db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get a single item by ID
     * @param int $itemId
     * @return array|false
     */
    public function getItem(int $itemId)
    {
        $sql = "SELECT * FROM shop_items WHERE id = :id";
        return $this->db->run($sql, ['id' => $itemId])->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Process a purchase
     * @param int $userId
     * @param int $itemId
     * @param string $currency 'ticket' or 'coin'
     * @param int $userCoins Current user coins (for validation)
     * @param int $userTickets Current user tickets
     * @return array ['success' => bool, 'message' => string]
     */
    public function purchase(int $userId, int $itemId, string $currency, int $userCoins, int $userTickets): array
    {
        $item = $this->getItem($itemId);
        if (!$item) {
            return ['success' => false, 'message' => 'Item not found.'];
        }

        if ($currency === 'ticket') {
            $price = (int) ($item['ticket_price'] ?? 0);
            if ($price <= 0) {
                return ['success' => false, 'message' => 'This item cannot be bought with tickets.'];
            }
            if ($userTickets < $price) {
                return ['success' => false, 'message' => 'Not enough tickets. You need ' . $price . '.'];
            }
            $this->updateUserTickets($userId, $userTickets - $price);
        } elseif ($currency === 'coin') {
            $price = (int) ($item['coin_price'] ?? 0);
            if ($price <= 0) {
                return ['success' => false, 'message' => 'This item cannot be bought with coins.'];
            }
            if ($userCoins < $price) {
                return ['success' => false, 'message' => 'Not enough coins. You need ' . $price . '.'];
            }
            $this->updateUserCoins($userId, $userCoins - $price);
        } else {
            return ['success' => false, 'message' => 'Invalid currency.'];
        }

        $this->recordPurchase($userId, $itemId, $currency, $price);

        return ['success' => true, 'message' => 'Purchase successful!'];
    }


    private function updateUserTickets(int $userId, int $newTickets)
    {
        $sql = "UPDATE profiles SET Tickets = :tickets WHERE Id = :id";
        $this->db->run($sql, ['tickets' => $newTickets, 'id' => $userId]);
    }

    private function updateUserCoins(int $userId, int $newCoins)
    {
        $sql = "UPDATE profiles SET Coins = :coins WHERE Id = :id";
        $this->db->run($sql, ['coins' => $newCoins, 'id' => $userId]);
    }

    private function recordPurchase(int $userId, int $itemId, string $currency, int $cost)
    {
        $sql = "INSERT INTO user_purchases (user_id, item_id, currency_used, cost) VALUES (:userId, :itemId, :currency, :cost)";
        $this->db->run($sql, ['userId' => $userId, 'itemId' => $itemId, 'currency' => $currency, 'cost' => $cost]);
    }
}