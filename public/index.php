<?php
//declare(strict_types=1);

require "../vendor/autoload.php";

//include "../routes.php";
//
//use App\Router;
//$router = new Router();
//$router->dispatch();

use App\Router;
use App\Controller\HomeController;

$router = new Router();
$router->addRoute('home', 'GET', '/', HomeController::class, 'index');
$router->addRoute('item.show', 'GET', '/item/{id}', HomeController::class, 'show');


try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "Erro: " . $e->getMessage();
}