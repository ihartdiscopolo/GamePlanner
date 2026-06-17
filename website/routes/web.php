<?php
global $router, $HouseholdController, $response;

$router->get('/', function($template) {
    return 'home';
});

$router->get('/login', function($template) {
    $template['css'] = ['forms'];
    return 'login';
});

$router->post('/login', function($template) use ($HouseholdController) {
    $HouseholdController->login();
    // $response->validate(['password' => ['required' => 'Need to have a password', 'max:2' => 'Your password needs to be less then 2']]);
    return 'login';
});

$router->get('/register', function($template) {
    $template['css'] = ['forms'];
    return 'register';
});

$router->post('/register', function($template) use ($HouseholdController) {
    $HouseholdController->register();
    return 'register';
});



// $router->get('/users/{id}', function($template, $id) use ($HouseholdRepo){
//     $household = $HouseholdRepo->getHouseholdById($id);

//     if (!$household) {
//         return "404User";
//     }

//     $template['user'] = $household;
//     return 'user';
// });