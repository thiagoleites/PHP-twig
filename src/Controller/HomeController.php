<?php 

declare(strict_types=1);

namespace App\Controller;

use App\Controller;

class HomeController extends Controller {
    public function index(): void {
        $testes = "teste de variavel";
        echo $this->twig->render('home.html', ['testes' => $testes]);
    }

    public function login()
    {
        echo $this->twig->render('login.html');
    }
}