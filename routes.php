<?php 

use App\Router;

$router = new Router();


// Rotas para home
$router->addRoute('home', 'GET', '/', 'App\Controller\HomeController', 'index');
$router->addRoute('login', 'GET', '/login', 'App\Controller\HomeController', 'login');


// Rotas para pessoa
$router->addRoute('pessoas', 'GET','/pessoas','App\Controller\PessoaController', 'index');

// Rotas para contato
$router->addRoute('contato_home', 'GET','/contato','App\Controller\ContatoController', 'index');
$router->addRoute('contato_enviar', 'POST','/contato/do','App\Controller\ContatoController', 'enviar');

$router->dispatch();