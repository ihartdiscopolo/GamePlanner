<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo;

$router->get('/', function($template) {
    return 'home';
});

$router->get('/users/{id}', function($template, $id) use ($HouseholdRepo){
    $household = $HouseholdRepo->getHouseholdById($id);

    if (!$household) {
        return "404User";
    }

    $template['user'] = $household;
    return 'user';
});

$router->post('/', function($template) {
    $name = $_POST['name'] ?? 'Guest';
    $template['message'] = "Hello, " . htmlspecialchars($name) . "!";
    return 'home';
});

$router->get('/household/login', function($template) {
    return 'login';
});

$router->post('/household/login', function($template) use ($HouseholdController) {
    $HouseholdController->login();
    return 'login';
});

$router->get('/household/register', function($template) {
    return 'register';
});

$router->post('/household/register', function($template) use ($HouseholdController) {
    $HouseholdController->register();
    return 'register';
});

$router->post('/household/logout', function($template) use ($HouseholdController) {
    $HouseholdController->logout();
    exit;
});