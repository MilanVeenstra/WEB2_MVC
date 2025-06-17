<?php
// config/routes.php

use Framework\Routing\Router;
use App\Controller\IndexController;
use App\Controller\LoginController;
use App\Controller\LogoutController;

return static function(Router $router): void {
    // Home
    $router->add('GET', '/', IndexController::class);

    // Login (GET = formulier tonen, POST = formulier verwerken)
    $router->add('GET',  '/login',  LoginController::class);
    $router->add('POST', '/login',  LoginController::class);

    // Logout
    $router->add('GET',  '/logout', LogoutController::class);
    $router->get('/error', \App\Controller\ErrorController::class);


    // TODO: hier later je andere routes toevoegen
};
