<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller;

class PessoaController extends Controller {
    public function index(): void {
        $pessoas = $this->select('users');
        echo $this->twig->render('usuarios.html', ['usuarios' => $pessoas]);
    }

    public function show(int $id): void {
        $pessoa = $this->select('users', 'id = ?', [$id]);
        echo $this->twig->render('pessoa.html', ['pessoa' => $pessoa]);
    }

    public function create(): void {
        echo $this->twig->render('formulario_pessoa.html');
    }

    public function store(array $data): void {
        $this->insert('pessoas', $data);
        header('Location: /pessoa');
    }

    public function edit(int $id): void {
        $pessoa = $this->select('pessoas', 'id = ?', [$id]);
        echo $this->twig->render('formulario_pessoa.html', ['pessoa' => $pessoa]);
    }

    public function atualizar(int $id, array $data): void {
        $this->update('users', $data, 'id = ?', [$id]);
        header('Location: /pessoa');
    }

    public function destroy(int $id): void {
        $this->delete('pessoas', 'id = ?', [$id]);
        header('Location: /pessoa');
    }
}
