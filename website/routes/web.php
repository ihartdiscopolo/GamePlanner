<?php
global $router, $HouseholdController, $response;

$router->get('/', function($template) {
    $template['css'] = ['home'];
    return 'home';
});

$router->get('/login', function($template) {
    $template['css'] = ['forms', 'alerts'];
    return 'login';
});

$router->post('/login', function($template) use ($HouseholdController) {
    $HouseholdController->login();
    return 'login';
});

$router->get('/register', function($template) {
    $template['css'] = ['forms', 'alerts'];
    return 'register';
});

$router->post('/register', function($template) use ($HouseholdController) {
    $HouseholdController->register();
    return 'register';
});

$router->get('/test', function($template) {
    return 'test';
});


// $router->get('/users/{id}', function($template, $id) use ($HouseholdRepo){
//     $household = $HouseholdRepo->getHouseholdById($id);

//     if (!$household) {
//         return "404User";
//     }

//     $template['user'] = $household;
//     return 'user';
// });