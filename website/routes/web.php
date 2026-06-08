<?php
global $router, $HouseholdController;

$router->get('/', function($template) {
    return 'home';
});

$router->get('/login', function($template) {
    return 'login';
});

$router->post('/login', function($template) use ($HouseholdController) {
    $HouseholdController->login();
    return 'login';
});

$router->get('/register', function($template) {
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