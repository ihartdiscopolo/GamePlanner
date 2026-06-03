<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo;

$router->get('/', function($template) {
    return 'profiles';
});

// $router->get('/users/{id}', function($template, $id) use ($HouseholdRepo){
//     $household = $HouseholdRepo->getHouseholdById($id);

//     if (!$household) {
//         return "404User";
//     }

//     $template['user'] = $household;
//     return 'user';
// });

$router->post('/logout', function($template) use ($HouseholdController) {
    $HouseholdController->logout();
    exit;
});