<?php
global $router, $HouseholdController, $ProfileController, $HouseholdRepo, $GamesController, $GamesRepo, $GroceryController, $GroceryRepo, $TasksController, $TasksRepo, $SettingsController;

$router->get('/', function($template) use ($HouseholdRepo) {
    $template['css'] = ['profiles', 'modal', 'forms', 'alerts'];
    $template['js'] = ['modal', 'passwordBtn'];

    if($_SESSION['profileLoggedIn'] == true) reload("/dashboard");

    $profiles = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    if(empty($profiles)) reload("/create");
    $template['profiles'] = $profiles;
    return 'profiles';
});

$router->get('/create', function($template) use ($ProfileController) {
    $template['css'] = ['forms', 'alerts'];
    $template['js'] = ['passwordBtn'];
    return 'create';
});


$router->post('/create', function($template) use ($ProfileController) {
    $ProfileController->create();
    exit;
});

$router->post('/login', function($template) use ($ProfileController) {
    $ProfileController->login();
    exit;
});

$router->post('/logout', function($template) use ($HouseholdController, $ProfileController) {
    if($_SESSION['profileLoggedIn'] == false) {
        $HouseholdController->logout();
    } else {
        $ProfileController->logout();
    }
    exit;
});

$router->get('/dashboard', function($template) {
    return 'dashboard';
});

$router->get('/grocerylist', function($template) use ($GroceryRepo) {
    $categoryFilter = isset($_GET['filterCategory']) ? (int) $_GET['filterCategory'] : 0;
    $sortOrder = $_GET['sortOrder'] ?? 'newest';
    $allowedSorts = ['newest', 'oldest', 'alpha', 'alpha_desc'];
    if (!in_array($sortOrder, $allowedSorts, true)) {
        $sortOrder = 'newest';
    }

    $template['groceries'] = $GroceryRepo->getGroceriesById($_SESSION['householdId'], $categoryFilter, '', $sortOrder);
    $template['categories'] = $GroceryRepo->getAllCategories();
    $template['categoryFilter'] = $categoryFilter;
    $template['sortOrder'] = $sortOrder;
    return 'grocerylist';
});

$router->get('/tasks', function($template) use ($TasksRepo) {
    $categoryFilter = isset($_GET['filterCategory']) ? (int) $_GET['filterCategory'] : 0;
    $sortOrder = $_GET['sortOrder'] ?? 'newest';
    $allowedSorts = ['newest', 'oldest', 'alpha', 'alpha_desc'];
    if (!in_array($sortOrder, $allowedSorts, true)) {
        $sortOrder = 'newest';
    }

    $template['tasks'] = $TasksRepo->getTasksByHouseholdId($_SESSION['householdId'], $categoryFilter, $sortOrder);
    $template['categories'] = $TasksRepo->getAllCategories();
    $template['householdMembers'] = $TasksRepo->getHouseholdMembers($_SESSION['householdId']);
    $template['categoryFilter'] = $categoryFilter;
    $template['sortOrder'] = $sortOrder;
    return 'tasks';
});

$router->post('/tasks/add', function($template) use ($TasksController) {
    $TasksController->create();
    exit;
});

$router->get('/tasks/edit/{id}', function($template, $id) use ($TasksRepo) {
    $id = (int) $id;
    $task = $TasksRepo->getTaskById($id);

    if (!$task) {
        http_response_code(404);
        return '404';
    }

    $template['task'] = $task;
    $template['categories'] = $TasksRepo->getAllCategories();
    $template['householdMembers'] = $TasksRepo->getHouseholdMembers($task->Household_Id);
    return 'edit-task';
});

$router->post('/tasks/edit', function($template) use ($TasksController) {
    $TasksController->update();
    exit;
});

$router->post('/tasks/delete', function($template) use ($TasksController) {
    $TasksController->delete();
    exit;
});

$router->post('/tasks/toggle', function($template) use ($TasksController) {
    $TasksController->toggleComplete();
    exit;
});

$router->post('/grocery/add', function($template) use ($GroceryRepo) {
    $name = trim($_POST['name'] ?? '');
    $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 0;
    $amount = trim($_POST['amount'] ?? '');
    $specification = trim($_POST['specification'] ?? '');

    if (!$name) {
        respond('Please enter an item name.');
        return;
    }

    if (!$categoryId) {
        respond('Please select a category.');
        return;
    }

    $result = $GroceryRepo->addGrocery(
        $_SESSION['householdId'],
        $_SESSION['profileId'],
        $name,
        $specification,
        $amount,
        $categoryId
    );

    if (!$result) {
        respond('Unable to add grocery item.');
        return;
    }

    reload('/grocerylist');
});

$router->post('/grocery/delete', function($template) use ($GroceryRepo) {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if (!$id) {
        respond('Invalid grocery item.');
        return;
    }

    if (!$GroceryRepo->deleteGrocery($id)) {
        respond('Unable to delete grocery item.');
        return;
    }

    reload('/grocerylist');
});

$router->get('/grocery/edit/{id}', function($template, $id) use ($GroceryRepo) {
    $id = (int) $id;
    $grocery = $GroceryRepo->getGroceryById($id);
    
    if (!$grocery) {
        http_response_code(404);
        return '404';
    }

    $template['grocery'] = $grocery;
    $template['categories'] = $GroceryRepo->getAllCategories();
    return 'edit-grocery';
});

$router->post('/grocery/edit', function($template) use ($GroceryRepo) {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $categoryId = isset($_POST['category']) ? (int) $_POST['category'] : 0;
    $amount = trim($_POST['amount'] ?? '');
    $specification = trim($_POST['specification'] ?? '');

    if (!$id || !$name || !$categoryId) {
        respond('All fields are required.');
        return;
    }

    if (!$GroceryRepo->updateGrocery($id, $name, $specification, $amount, $categoryId)) {
        respond('Unable to update grocery item.');
        return;
    }

    reload('/grocerylist');
});

$router->get('/settings/profile', function($template) {
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown'];
    return 'settings';
});

$router->get('/settings/household', function($template) use ($HouseholdRepo) {
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown'];
    $template['household'] = $HouseholdRepo->getHouseholdById($_SESSION['householdId']);
    return 'settings';
});

$router->get('/settings/permisions', function($template) use ($HouseholdRepo) {
    $template['css'] = ['forms', 'alerts', 'settings'];
    $template['js'] = ['passwordBtn', 'dropdown'];
    $profiles = $HouseholdRepo->getProfilesByHouseholdId($_SESSION['householdId']);
    $template['profiles'] = $profiles;
    return 'settings';
});

$router->post('/settings', function($template) use ($SettingsController) {
    $SettingsController->formHandler();
    exit;
});

// games
$router->get('/games', function($template) use ($GamesRepo) {
    $template['css'] = ['games'];
    $template['games'] = $GamesRepo->getGames();
    return 'games';
});

$router->get('/game/hangman', function($template) {
    $template['css'] = ['game'];

    if(!$_SESSION['profileLoggedIn']) reload('/');

    return 'hangman';
});

$router->get('/game/tictactoe', function($template) {
    $template['css'] = ['game'];

    if(!$_SESSION['profileLoggedIn']) reload('/');

    return 'tictactoe';
});

$router->post('/game/start', function($template) use ($GamesController) {
    $GamesController->gameStart();
    exit;
});

$router->post('/game/tickets', function($template) use ($GamesController) {
    $GamesController->gameEnd();
    exit;
});

$router->post('/game/close', function($template) use ($GamesController) {
    // $GamesController->gameClose();
    reload('/');
    exit;
});

