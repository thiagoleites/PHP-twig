<?php

declare(strict_types= 1);// Declaração de tipos escalares

namespace App\Database;

class Connect {

    private string $host = "127.0.0.1";
    private string $db = "projeto-php-estudo";
    private string $user = "root";
    private string $pass = "";
    private \PDO $pdo;

    public function __construct() {
        $this->pdo = new \PDO("mysql:host=". $this->host .";dbname=". $this->db, $this->user, $this->pass);
    }

    public function getPDO(): \PDO {
        return $this->pdo;
    }
}