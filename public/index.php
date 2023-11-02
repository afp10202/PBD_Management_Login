<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GroupDuaPBD\Management\Login\Php\App\Router;
use GroupDuaPBD\Management\Login\Php\Controller\HomeController;

Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();