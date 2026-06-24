<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo, $GamesController, $GamesRepo, $GroceryController, $GroceryRepo, $TasksController, $TasksRepo, $SettingsController;

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
    loggedIn();
    return 'dashboard';
});

$router->get('/grocerylist', function($template) use ($GroceryRepo) {
    loggedIn();
    $template['css'] = ['forms', 'alerts', 'lists'];
    $template['js'] = ['dropdown'];
    $categoryFilter = isset($_GET['filterCategory']) ? (int) $_GET['filterCategory'] : 0;
    $sortOrder = $_GET['sortOrder'] ?? 'newest';
    $allowedSorts = ['newest', 'oldest', 'alpha', 'alpha_desc'];
    if (!in_array($sortOrder, $allowedSorts, true)) {
        $sortOrder = 'newest';
    }

    $template['groceries'] = $GroceryRepo->getGroceriesById($_SESSION['householdId'], $categoryFilter, '', $sortOrder);
    $template['categories'] = $GroceryRepo->getAllCategories();
    $template['categoryFilter'] = $categoryFilter;
    $template['sortOrder'] = $sortOrder;
    return 'grocerylist';
});

$router->get('/tasks', function($template) use ($TasksRepo) {
    loggedIn();
    $template['css'] = ['forms', 'alerts', 'lists'];
    $template['js'] = ['dropdown'];
    $categoryFilter = isset($_GET['filterCategory']) ? (int) $_GET['filterCategory'] : 0;
    $sortOrder = $_GET['sortOrder'] ?? 'newest';
    $allowedSorts = ['newest', 'oldest', 'alpha', 'alpha_desc'];
    if (!in_array($sortOrder, $allowedSorts, true)) {
        $sortOrder = 'newest';
    }

    $template['tasks'] = $TasksRepo->getTasksByHouseholdId($_SESSION['householdId'], $categoryFilter, $sortOrder);
    $template['categories'] = $TasksRepo->getAllCategories();
    $template['householdMembers'] = $TasksRepo->getHouseholdMembers($_SESSION['householdId']);
    $template['categoryFilter'] = $categoryFilter;
    $template['sortOrder'] = $sortOrder;
    return 'tasks';
});

$router->post('/tasks/add', function($template) use ($TasksController) {
    $TasksController->create();
    exit;
});

$router->get('/tasks/edit/{id}', function($template, $id) use ($TasksRepo) {
    loggedIn();
    $template['css'] = ['alerts'];
    $id = (int) $id;
    $task = $TasksRepo->getTaskById($id);

    if (!$task) {
        http_response_code(404);
        return '404';
    }

    $template['task'] = $task;
    $template['categories'] = $TasksRepo->getAllCategories();
    $template['householdMembers'] = $TasksRepo->getHouseholdMembers($task->Household_Id);
    return 'edit-task';
});

$router->post('/tasks/edit', function($template) use ($TasksController) {
    $TasksController->update();
    exit;
});

$router->post('/tasks/delete', function($template) use ($TasksController) {
    $TasksController->delete();
    exit;
});

$router->post('/tasks/toggle', function($template) use ($TasksController) {
    $TasksController->toggleComplete();
    exit;
});

$router->post('/grocery/add', function($template) use ($GroceryController) {
    $GroceryController->create();
    exit;
});

$router->post('/grocery/delete', function($template) use ($GroceryController) {
    $GroceryController->delete();
    exit;
});

$router->get('/grocery/edit/{id}', function($template, $id) use ($GroceryRepo) {
    loggedIn();
    $template['css'] = ['alerts'];
    $id = (int) $id;
    $grocery = $GroceryRepo->getGroceryById($id);
    
    if (!$grocery) {
        http_response_code(404);
        return '404';
    }

    $template['grocery'] = $grocery;
    $template['categories'] = $GroceryRepo->getAllCategories();
    return 'edit-grocery';
});

$router->post('/grocery/edit', function($template) use ($GroceryController) {
    $GroceryController->edit();
    exit;
});

$router->get('/settings/profile', function($template) {
    loggedIn();
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown'];
    return 'settings';
});

$router->get('/settings/household', function($template) use ($HouseholdRepo, $ProfileRepo) {
    loggedIn();
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown'];
    $template['household'] = $HouseholdRepo->getHouseholdById($_SESSION['householdId']);

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    if(!$profile->Can_Edit_Household && !$profile->Is_Creator) reload("/settings/profile");

    return 'settings';
});

$router->get('/settings/permisions', function($template) use ($HouseholdRepo, $ProfileRepo) {
    loggedIn();
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown', 'checkboxSubmit'];
    $profiles = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    $template['profiles'] = $profiles;

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    if(!$profile->Can_Edit_Permisions && !$profile->Is_Creator) reload("/settings/profile");

    return 'settings';
});

$router->post('/settings', function($template) use ($SettingsController) {
    $SettingsController->formHandler();
    exit;
});

// games
$router->get('/games', function($template) use ($GamesRepo) {
    loggedIn();
    $template['css'] = ['games'];
    $template['games'] = $GamesRepo->getGames();
    return 'games';
});

$router->get('/game/hangman', function($template) use ($ProfileRepo, $GamesRepo) {
    loggedIn();
    $template['css'] = ['game'];
    $template['js'] = ['gameClose'];

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    $game = $GamesRepo->getGameByName('hangman');
    if($profile->Coins < $game->Cost) reload("/games");

    return 'hangman';
});

$router->get('/game/tictactoe', function($template) use ($ProfileRepo, $GamesRepo) {
    loggedIn();
    $template['css'] = ['game'];
    $template['js'] = ['gameClose'];

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    $game = $GamesRepo->getGameByName('tictactoe');
    if($profile->Coins < $game->Cost) reload("/games");

    return 'tictactoe';
});

$router->get('/game/snake', function($template) use ($ProfileRepo, $GamesRepo) {
    loggedIn();
    $template['css'] = ['game'];
    $template['js'] = ['gameClose'];

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    $game = $GamesRepo->getGameByName('snake');
    if($profile->Coins < $game->Cost) reload("/games");

    return 'snake';
});

$router->get('/game/memory', function($template) use ($ProfileRepo, $GamesRepo) {
    loggedIn();
    $template['css'] = ['game'];
    $template['js'] = ['gameClose'];

    $profile = $ProfileRepo->getProfileById($_SESSION['profileId']);
    // $game = $GamesRepo->getGameByName('memory');
    // if($profile->Coins < $game->Cost) reload("/games");

    return 'memory';
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
    $GamesController->gameClose();
    exit;
});

$router->get('/game/status/stream', function() use ($GamesController) {
    $GamesController->gameStatusStream();
    exit;
});