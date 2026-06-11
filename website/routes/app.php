<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo;

$router->get('/', function($template) use ($HouseholdRepo) {
    $template['css'] = ['profiles', 'modal', 'forms'];
    $template['js'] = ['modal'];

    $template['profiles'] = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    return 'profiles';
});

$router->post('/create', function($template) use ($ProfileController) {
    $ProfileController->create();
    exit;
});

$router->post('/logout', function($template) use ($HouseholdController) {
    $HouseholdController->logout();
    exit;
});