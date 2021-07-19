<?php

use App\Controllers\HomeOwnerController;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api/home-owners', function (RouteCollectorProxy $route) {
    $route->post('', HomeOwnerController::class . ':upload');
});
