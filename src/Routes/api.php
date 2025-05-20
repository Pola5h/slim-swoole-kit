<?php

use Slim\App;
use App\Controllers\UserController;

return function (App $app) {
    $app->get('/users', [UserController::class, 'index']);
    $app->post('/users', [UserController::class, 'store']);
    $app->get('/users/{id}', [UserController::class, 'show']);
    $app->put('/users/{id}', [UserController::class, 'update']);
    $app->delete('/users/{id}', [UserController::class, 'destroy']);

    $app->get('/favicon.ico', function ($request, $response) {
        return $response->withStatus(204);
    });

    $app->get('/', function ($request, $response) {
        $response->getBody()->write('Welcome to the API');
        return $response->withStatus(200);
    });
};
