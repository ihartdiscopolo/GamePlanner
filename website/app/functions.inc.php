<?php
function reload($location = null, $statusCode = 302, $exitAfter = true) {
    if($location === null) {
        $location = $_SERVER['PHP_SELF'];
    }

    if(strpos($location, '?') === 0) {
        $location = $_SERVER['PHP_SELF'] . $location;
    }

    header("Location: $location", true, $statusCode);

    if($exitAfter) {
        exit();
    }
}

// function respond($message, $type = 'info', $location) {
//     $_SESSION['response'] = ['message' => $message, 'type' => $type, 'location' => $location];
// }

function dump($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}