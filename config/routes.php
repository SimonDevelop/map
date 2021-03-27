<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

// Authentification
$app->group("/auth", function ($app) {
    // Login
    $app->get("/login", AuthController::class. ":getLogin")->setName("login");
    $app->post("/login", AuthController::class. ":postLogin");
    $app->get("/logout", AuthController::class. ":getLogout")->setName("logout");
})
// Twig
->add(\Slim\Views\TwigMiddleware::createFromContainer($app))
// Middleware pour les message d'alert en session
->add(new App\Middlewares\AlertMiddleware($container))
// Middleware pour la sauvegarde des champs de saisie
->add(new App\Middlewares\OldMiddleware($container))
// Middleware pour la vérification csrf
->add(new App\Middlewares\CsrfMiddleware($container))
->add("csrf");


// Home pages
$app->group("", function ($app) {
    $app->get('/', HomeController::class. ':getHome')->setName('home');
})
// Twig
->add(\Slim\Views\TwigMiddleware::createFromContainer($app))
// Middleware pour la demande de connexion
->add(new App\Middlewares\ConnectedMiddleware($container))
// Middleware pour les message d'alert en session
->add(new App\Middlewares\AlertMiddleware($container))
// Middleware pour la sauvegarde des champs de saisie
->add(new App\Middlewares\OldMiddleware($container))
// Middleware pour la génération de token
->add(new App\Middlewares\TokenMiddleware($container))
// Middleware pour la vérification csrf
->add(new App\Middlewares\CsrfMiddleware($container))
->add("csrf");

// Markers Ajax
$app->group("/marker", function ($app) {
    // Ajout marker
    $app->post("/add/{token}", HomeController::class. ":postAjaxAddMarker")->setName("add-marker");
})
// Middleware pour la génération de token
->add(new App\Middlewares\TokenMiddleware($container));
