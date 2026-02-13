<?php

require_once __DIR__ . '/./utils.php';
require_once __DIR__ . '/./middleware.php';
require_once __DIR__ . '/./rules.php';

// /*ini_set('display_errors', 1);*/
// /*error_reporting(E_ALL);*/

// Health status message
$out = <<<'_GAN'
    They have taken the bridge and the second hall.
    We have barred the gates but cannot hold them for long.
    The ground shakes, drums... drums in the deep. We cannot get out.
    A shadow lurks in the dark. We can not get out.
    They are coming.
    _GAN;

$handler = new MiddlewareHandler;
$handler->add(new ValidationMiddleware(RULES))->add(new JsonMiddleware);

if (! $handler->handle()) {
    if (! headers_sent()) {
        header('HTTP/1.1 400 Bad Request');
    }
    exit(1);
}

// Get the URI and split it into its components
$uri = parseURI();

// Get the HTTP verb
$verb = $_SERVER['REQUEST_METHOD'];

// If the URI is empty, return a 404
if (! isset($uri[0])) {
    sendError('Empty route', 404);
    exit();
}

try {
    // Match the first element of the URI to the appropriate route
    // Each route will have its own handler function that will be called with the HTTP verb and the URI as parameters
    switch ($uri[0]) {
        case 'info':
            sendData(['msg' => 'API']);
            break;
        case 'health':
            sendData(['status' => $out]);
            break;
        case "contacts":
            require_once __DIR__ . "/./actions/contacts.php";
            contactsHandler($verb, $uri);
            break;
        default:
            sendError('Route not found', 404);
            exit();
            break;
    }
} catch (Exception $e) {
    // Exception handler
    error_log($e->getTraceAsString());
    sendError($e->getMessage(), 500);
}
