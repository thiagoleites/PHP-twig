<?php

declare(strict_types=1);

namespace App\Controller;
use App\Controller;

class ContatoController extends Controller {

    public function index(): void {

        echo $this->twig->render('contato.html');
    }

    public function enviar(array $data): void {
        $this->insert('contato_message', $data);
        header('Location: /contato');
    }
}