<?php
session_start();

if(!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

// load configuration and classes
include("../app/init.inc.php");

// get routes
include("../routes/web.php");
dump($_SESSION);

echo $router->resolve();