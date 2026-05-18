<?php
include dirname(__DIR__, 1) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

include(dirname(__FILE__) . "/functions.inc.php");
include(dirname(__FILE__) . "/classes.inc.php");