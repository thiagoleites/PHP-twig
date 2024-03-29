<?php

declare(strict_types=1); 

namespace App;

use App\Database\Connect;
use App\Twig\RouterExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Router;

abstract class Controller {

    protected $pdo;
    protected $loader;
    protected $twig;
    protected $router;

    public function __construct() {
        $this->pdo = (new Connect())->getPDO();
        $this->loader = new FilesystemLoader(__DIR__ . "/../templates");
        $this->twig = new Environment($this->loader);

        $this->router = new Router();
        $this->twig->addExtension(new RouterExtension($this->router));
    }

    public function insert(string $table, array $data): bool {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update(string $table, array $data, string $condition, array $params): bool {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE $table SET $setClause WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        $values = array_merge(array_values($data), $params);
        return $stmt->execute($values);
    }

    public function delete(string $table, string $condition, array $params): bool {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function select(string $table, string $condition = '', array $params = [], string $fields = '*'): array {
        $sql = "SELECT $fields FROM $table";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}