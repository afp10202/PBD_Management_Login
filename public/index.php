<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GroupDuaPBD\Management\Login\Php\App\Router;
use GroupDuaPBD\Management\Login\Php\Controller\HomeController;
use GroupDuaPBD\Management\Login\Php\Controller\ProductController;
use GroupDuaPBD\Management\Login\Php\Middleware\AuthMiddleware;

Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories');

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/hello', HomeController::class, 'hello', [AuthMiddleware::class]);
Router::add('GET', '/world', HomeController::class, 'world', [AuthMiddleware::class]);
Router::add('GET', '/about', HomeController::class, 'about');

Router::run();