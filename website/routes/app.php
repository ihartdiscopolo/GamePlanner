<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo, $GamesController, $GamesRepo, $GroceryController, $GroceryRepo;

$router->get('/', function($template) use ($HouseholdRepo) {
    $template['css'] = ['profiles', 'modal', 'forms', 'alerts'];
    $template['js'] = ['modal', 'passwordBtn'];

    if($_SESSION['profileLoggedIn'] == true) reload("/dashboard");

    $profiles = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    if(empty($profiles)) reload("/create");
    $template['profiles'] = $profiles;
    return 'profiles';
});

$router->get('/create', function($template) use ($ProfileController) {
    $template['css'] = ['forms', 'alerts'];
    $template['js'] = ['passwordBtn'];
    return 'create';
});


$router->post('/create', function($template) use ($ProfileController) {
    $ProfileController->create();
    exit;
});

$router->post('/login', function($template) use ($ProfileController) {
    $ProfileController->login();
    exit;
});

$router->post('/logout', function($template) use ($HouseholdController, $ProfileController) {
    if($_SESSION['profileLoggedIn'] == false) {
        $HouseholdController->logout();
    } else {
        $ProfileController->logout();
    }
    exit;
});

$router->get('/dashboard', function($template) {
    return 'dashboard';
});

$router->get('/grocerylist', function($template) use ($GroceryRepo) {
    $template['groceries'] = $GroceryRepo->getGroceriesById($_SESSION['householdId']);
    $template['categories'] = $GroceryRepo->getAllCategories();
    return 'grocerylist';
});

$router->post('/grocery/add', function($template) use ($GroceryRepo) {
    $name = trim($_POST['name'] ?? '');
    $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 0;
    $amount = trim($_POST['amount'] ?? '');
    $specification = trim($_POST['specification'] ?? '');

    if (!$name) {
        respond('Please enter an item name.');
        return;
    }

    if (!$categoryId) {
        respond('Please select a category.');
        return;
    }

    if (!isset($_SESSION['householdId']) || !isset($_SESSION['profileId'])) {
        respond('You must be logged in to add groceries.');
        return;
    }

    $result = $GroceryRepo->addGrocery(
        $_SESSION['householdId'],
        $_SESSION['profileId'],
        $name,
        $specification,
        $amount,
        $categoryId
    );

    if (!$result) {
        respond('Unable to add grocery item.');
        return;
    }

    reload('/grocerylist');
});

$router->post('/grocery/delete', function($template) use ($GroceryRepo) {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if (!$id) {
        respond('Invalid grocery item.');
        return;
    }

    if (!$GroceryRepo->deleteGrocery($id)) {
        respond('Unable to delete grocery item.');
        return;
    }

    reload('/grocerylist');
});

$router->get('/settings/profile', function($template) {
    return 'settings';
});

// games
$router->get('/games', function($template) use ($GamesRepo) {
    $template['css'] = ['games'];
    $template['games'] = $GamesRepo->getGames();
    return 'games';
});

$router->get('/game/hangman', function($template) {
    $template['css'] = ['game'];

    if(!$_SESSION['profileLoggedIn']) reload('/');

    return 'hangman';
});

$router->get('/game/tictactoe', function($template) {
    $template['css'] = ['game'];

    if(!$_SESSION['profileLoggedIn']) reload('/');

    return 'tictactoe';
});

$router->post('/game/start', function($template) use ($GamesController) {
    $GamesController->gameStart();
    exit;
});

$router->post('/game/tickets', function($template) use ($GamesController) {
    $GamesController->gameEnd();
    exit;
});

$router->post('/game/close', function($template) use ($GamesController) {
    // $GamesController->gameClose();
    reload('/');
    exit;
});

