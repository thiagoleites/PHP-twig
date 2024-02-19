<?php 

use App\Router;

$router = new Router();


// Rotas para home
$router->addRoute('GET', '/', 'App\Controller\HomeController', 'index');

$router->addRoute('GET', '/teste', 'App\Controller\HomeController', 'teste');


// Rotas para pessoa
$router->addRoute('GET','/pessoas','App\Controller\PessoaController', 'index');

// Rotas para contato
$router->addRoute('GET','/contato','App\Controller\ContatoController', 'index');
// $router->addRoute('GET','','', 'index');

$router->dispatch();