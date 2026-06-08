<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo;

$router->get('/', function($template) use ($HouseholdRepo) {
    $template['css'] = ['profiles'];

    $template['profiles'] = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    return 'profiles';
});

$router->post('/logout', function($template) use ($HouseholdController) {
    $HouseholdController->logout();
    exit;
});