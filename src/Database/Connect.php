<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class Connect
{
    private const MYSQL_HOST = "localhost";
    private const MYSQL_USER = "root";
    private const MYSQL_DBNAME = "projeto-php-estudo";
    private const MYSQL_PASSWD = "";
    private const MYSQL_OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_PERSISTENT => false
    ];

    private const SQLITE_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_PERSISTENT => false
    ];

    private static $instance;

    final public function __construct()
    {
    }

    public static function getInstance(): PDO
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . self::MYSQL_HOST . ";dbname=" . self::MYSQL_DBNAME,
                    self::MYSQL_USER,
                    self::MYSQL_PASSWD,
                    self::MYSQL_OPTIONS
                );
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados MySQL: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public function getConnect(): PDO
    {
        return self::$instance;
    }

    final public function __clone()
    {
    }
}
