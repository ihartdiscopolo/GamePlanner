<?php
global $router, $HouseholdController, $ProfileController;

$router->get('/', function($template) use ($HouseholdController) {
    // $test = $HouseholdController->login();
    // $template['household'] = $test;
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

$router->get('/login', function($template) {
    return 'login';
});

$router->post('/login', function($template) use ($HouseholdController) {
    $HouseholdController->login();
    reload("/login");
    return 'login';
});