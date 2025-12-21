<?php
require __DIR__ . '/../backend/vendor/autoload.php';
use Core\Router;

$router = new Router();
require __DIR__ . '/../backend/Routes/api.php';
$router->run();
