<?php

declare(strict_types=1);

namespace App;

use App\Database\Connect;
use App\Twig\BaseUrlExtension;
use App\Twig\RouterExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Router;
use PDO;
use PDOException;

/**
 * Abstract base controller class.
 *
 * This class provides common functionality for all controller,
 * including database interaction and template rendering with twig.
 */
abstract class Controller
{

    /**
     * @var PDO Database connection
     */
    protected PDO $pdo;

    /**
     * @var FilesystemLoader twig filesystem loader
     */
    protected FilesystemLoader $loader;

    /**
     * @var Environment Twig environment
     */
    protected Environment $twig;

    /**
     * @var \App\Router Router instance
     */
    protected Router $router;

    /**
     * Controller constructor.
     *
     * Initializes the database connection, template engine, router system.
     */
    public function __construct()
    {
        try {
            $this->pdo = (new Connect())->getConnect();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        $this->loader = new FilesystemLoader(__DIR__ . "/../templates");
        $this->twig = new Environment($this->loader);

        $this->router = new Router();
        $this->twig->addExtension(new RouterExtension($this->router));
        $this->twig->addExtension(new BaseUrlExtension());
    }

    /**
     * Logs an error message into log file.
     *
     * @param string $message The error message to log
     * @return void
     */
    public function logError(string $message): void
    {
        $logFile = __DIR__ . '/../logs/errors.log';
        $date = date("Y-m-d H:i:s");
        $formattedMessage = "[$date] ERROR: $message" . PHP_EOL;
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }

    /**
     * Insert a new records into the specified table.
     *
     * @param string $table The name of the table
     * @param array $data Associative array of column names and values
     * @return bool True on success, false on failure
     */
    public function insert(string $table, array $data): bool
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
            $this->logError("Database query failed: " . $e->getMessage() . " | SQL: $sql | Params: " . json_encode(array_values($data)));
            return false;
        }
    }

    /**
     * Updates existing records in the specified table.
     *
     * @param string $table The name of the table
     * @param array $data Associative array of column names and values
     * @param string $condition The WHERE clause ot identify the records to uipdate
     * @param array $params Parameters for the WHERE clause
     * @return bool True on success, false on failure
     */
    public function update(string $table, array $data, string $condition, array $params): bool
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE $table SET $setClause WHERE $condition";
        try {
            $stmt = $this->pdo->prepare($sql);
            $values = array_merge(array_values($data), $params);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            $this->logError("Database query failed: " . $e->getMessage() . " | SQL: $sql | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Deletes records from the specified table.
     *
     * @param string $table The name of the table
     * @param string $condition The WHERE clause to identify the records to delete
     * @param array $params Parameters for the WHERE clause
     * @return bool True on success, false on failure
     */

    public function delete(string $table, string $condition, array $params): bool
    {
        $sql = "DELETE FROM $table WHERE $condition";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            $this->logError("Database query failed: " . $e->getMessage() . " | SQL: $sql | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Selects records from the specified table.
     *
     * @param string $table The name of the table.
     * @param string $condition Option WHERE clause to filter the records
     * @param array $params Parameters for the WHERE clause
     * @param string $fields The columns to select
     * @return array The selected records as an associative array
     */
    public function select(string $table, string $condition = '', array $params = [], string $fields = '*'): array
    {
        $sql = "SELECT $fields FROM $table";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Database query failed: " . $e->getMessage() . " | SQL: $sql | Params: " . json_encode($params));
            return [];
        }
    }
}
