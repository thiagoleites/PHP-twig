<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller;

class PessoaController extends Controller {
    public function index(): void {
        // Método para exibir uma lista de pessoas
        $pessoas = $this->select('users');
        echo $this->twig->render('usuarios.html', ['usuarios' => $pessoas]);
    }

    public function show(int $id): void {
        // Método para exibir os detalhes de uma pessoa específica
        $pessoa = $this->select('users', 'id = ?', [$id]);
        echo $this->twig->render('pessoa.html', ['pessoa' => $pessoa]);
    }

    public function create(): void {
        // Método para exibir o formulário de criação de uma pessoa
        echo $this->twig->render('formulario_pessoa.html');
    }

    public function store(array $data): void {
        // Método para armazenar uma nova pessoa no banco de dados
        $this->insert('pessoas', $data);
        // Redirecionar para a página de listagem de pessoas
        header('Location: /pessoa');
    }

    public function edit(int $id): void {
        // Método para exibir o formulário de edição de uma pessoa
        $pessoa = $this->select('pessoas', 'id = ?', [$id]);
        echo $this->twig->render('formulario_pessoa.html', ['pessoa' => $pessoa]);
    }

    public function atualizar(int $id, array $data): void {
        // Método para atualizar os dados de uma pessoa no banco de dados
        $this->update('users', $data, 'id = ?', [$id]);
        // Redirecionar para a página de listagem de pessoas
        header('Location: /pessoa');
    }

    public function destroy(int $id): void {
        // Método para excluir uma pessoa do banco de dados
        $this->delete('pessoas', 'id = ?', [$id]);
        // Redirecionar para a página de listagem de pessoas
        header('Location: /pessoa');
    }
}
