<?php
function isAjax(): bool {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function respond(string $message, $type = 'danger') {
    if (isAjax()) {
        header('Content-Type: application/json');
        http_response_code($type === 'danger' ? 400 : 200);
        echo json_encode(['success' => $type !== 'danger', 'message' => $message, 'type' => $type]);
        exit();
    }
    $_SESSION['response'] = ['message' => $message, 'type' => $type];
}

function reload($location = null, $statusCode = 302, $exitAfter = true) {
    if (isAjax()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'redirect' => $location ?? $_SERVER['REQUEST_URI']]);
        if ($exitAfter) exit();
        return;
    }
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($location === null) {
        $location = $_SERVER['REQUEST_URI'];
    }
    if (strpos($location, '?') === 0) {
        $location = $currentPath . $location;
    }
    header("Location: $location", true, $statusCode);
    if ($exitAfter) {
        exit();
    }
}

function loggedIn() {
    if (!$_SESSION['profileLoggedIn']) {
        reload("/");
    }
}

function dump(mixed $data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}