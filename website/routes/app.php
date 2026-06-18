<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo, $GamesController;

$router->get('/', function($template) use ($HouseholdRepo) {
    $template['css'] = ['profiles', 'modal', 'forms'];
    $template['js'] = ['modal'];

    if($_SESSION['profileLoggedIn'] == true) reload("/dashboard");

    $template['profiles'] = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    return 'profiles';
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
$router->get('/games', function($template) use ($GamesController) {
    return 'games';
});

$router->get('/game/hangman', function($template) {
    $template['css'] = ['games'];

    if(!$_SESSION['profileLoggedIn']) reload('/');

    return 'hangman';
});