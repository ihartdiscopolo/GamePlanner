<?php

$router->get('/', function($template) {
    $template['message'] = "Hello, Guest!";
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