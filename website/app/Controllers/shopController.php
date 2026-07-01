<?php
require_once __DIR__ . "/../Core/class.response.php";
require_once __DIR__ . '/../Modules/shopRepo.php';
require_once __DIR__ . '/../Modules/profileRepo.php';

class ShopController
{
    private Response $response;
    private ShopRepo $shopRepo;
    private ProfileRepo $profileRepo;

    public function __construct()
    {
        $this->response = new Response();
        $this->shopRepo = new ShopRepo();
        $this->profileRepo = new ProfileRepo();
    }

    public function purchase()
    {
        loggedIn();

        $profileId = $_SESSION['profileId'] ?? 0;
        $profile = $this->profileRepo->getProfileById($profileId);

        if (!$profile) {
            respond('Please log in to make a purchase.');
            reload('/login');
        }

        $itemId = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $currency = $_POST['currency'] ?? '';

        if ($itemId <= 0 || !in_array($currency, ['ticket', 'coin'], true)) {
            respond('Invalid purchase request.');
            reload('/shop');
        }

        $result = $this->shopRepo->purchase(
            $profileId,
            $itemId,
            $currency,
            (int) ($profile->Coins ?? 0),
            (int) ($profile->Tickets ?? 0)
        );

        if ($result['success']) {
            if (isset($reuslt['coins']) && isset($result['tickets'])) {
                respond('', 'success', ['stats' => ['coins' => $result['coins'],'tickets' => $result['tickets']]]);
            }
            if (isset($result['coins'])) {
                respond('', 'success', ['stats' => ['coins' => $result['coins']]]);
            } else {
                respond('', 'success', ['stats' => ['tickets' => $result['tickets']]]);
            }
        } else {
            respond($result['message'], 'danger');
        }
    }

    public function create()
    {
        loggedIn();
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ticketPrice = (int) ($_POST['ticket_price'] ?? 0);
        $coinPrice = (int) ($_POST['coin_price'] ?? 0);

        // $this->response->validate([
        //     'name' => ['required' => 'Please enter an item name.'],
        // ]);

        if ($ticketPrice < 0 || $coinPrice < 0) {
            respond('Prices cannot be negative.');
            return;
        }

        if (!$this->shopRepo->addItem($name, $description, $ticketPrice, $coinPrice)) {
            respond('Unable to add item.');
            return;
        }

        reload('/');
    }

    public function update()
    {
        loggedIn();
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ticketPrice = (int) ($_POST['ticket_price'] ?? 0);
        $coinPrice = (int) ($_POST['coin_price'] ?? 0);

        $this->response->validate([
            'id'   => ['required' => 'Invalid item.'],
            'name' => ['required' => 'Please enter an item name.'],
        ]);

        if ($ticketPrice < 0 || $coinPrice < 0) {
            respond('Prices cannot be negative.');
            return;
        }

        if (!$this->shopRepo->updateItem($id, $name, $description, $ticketPrice, $coinPrice)) {
            respond('Unable to update item.');
            return;
        }

        respond('Item updated successfully.', 'success');
        reload('/shop');
    }

    public function delete()
    {
        loggedIn();
        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            respond('Invalid item.');
            return;
        }

        if (!$this->shopRepo->deleteItem($id)) {
            respond('Unable to delete item.');
            return;
        }

        respond('', 'success');
    }

}