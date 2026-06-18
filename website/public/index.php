<?php
session_start();

if(!isset($_SESSION['householdLoggedIn'])) { $_SESSION['householdLoggedIn'] = false; }
if(!isset($_SESSION['profileLoggedIn'])) { $_SESSION['profileLoggedIn'] = false; }

// load configuration and classes
include("../app/init.inc.php");

// get routes
if($_SESSION['householdLoggedIn'] == false) {
    include("../routes/web.php");
} else {
    include("../routes/app.php");
}
// dump($_SESSION);

echo $router->resolve();

if (isset($_SESSION['response'])) {
    unset($_SESSION['response']);
}