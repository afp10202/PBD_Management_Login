<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GroupDuaPBD\Management\Login\Php\App\Router;
use GroupDuaPBD\Management\Login\Php\Config\Database;
use GroupDuaPBD\Management\Login\Php\Controller\HomeController;
use GroupDuaPBD\Management\Login\Php\Controller\UserController;

Database::getConnection('prod');

//Home Controller
Router::add('GET', '/', HomeController::class, 'index', []);

//User Controller
Router::add('GET', '/users/register', UserController::class, 'register', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);

Router::run();