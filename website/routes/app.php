<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo, $GamesController, $GamesRepo;

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