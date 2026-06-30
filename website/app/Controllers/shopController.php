<?php
require_once __DIR__ . '/../Modules/shopRepo.php';
require_once __DIR__ . '/../Modules/profileRepo.php';

class ShopController
{
    private ShopRepo $shopRepo;
    private ProfileRepo $profileRepo;

    public function __construct()
    {
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
            respond($result['message'], 'success');
        } else {
            respond($result['message'], 'danger');
        }

        reload('/shop');
    }
}