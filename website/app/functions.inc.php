<?php
function reload($location = null, $statusCode = 302, $exitAfter = true) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if($location === null) {
        $location = $_SERVER['REQUEST_URI'];
    }

    if(strpos($location, '?') === 0) {
        $location = $currentPath . $location;
    }

    header("Location: $location", true, $statusCode);

    if($exitAfter) {
        exit();
    }
}

function respond(string $message, $type = 'info') {
    $_SESSION['response'] = ['message' => $message, 'type' => $type];
}

function dump(mixed $data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}