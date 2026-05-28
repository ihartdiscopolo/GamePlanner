<?php
global $router, $HouseholdController, $ProfileController;

$router->get('/', function($template) use ($HouseholdController) {
    return 'home';
});

$router->post('/', function($template) {
    $name = $_POST['name'] ?? 'Guest';
    $template['message'] = "Hello, " . htmlspecialchars($name) . "!";
    return 'home';
}); 

$router->get('/about', function($template) {
    $template['message'] = "Welcome to the About Page!";
    return 'about';
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