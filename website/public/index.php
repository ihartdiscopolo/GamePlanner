<?php
session_start();

if(!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

// load configuration and classes
include("../app/init.inc.php");

// get routes
if($_SESSION['loggedIn'] == false) {
    include("../routes/web.php");
} else {
    include("../routes/app.php");
}
dump($_SESSION);

echo $router->resolve();