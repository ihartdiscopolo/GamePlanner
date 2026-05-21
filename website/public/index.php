<?php
session_start();

// load configuration and classes
include("../app/init.inc.php");

// get routes
include("../routes/web.php");

echo $router->resolve();