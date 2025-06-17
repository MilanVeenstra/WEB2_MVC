<?php
namespace Framework\Database;

use PDO;
use PDOStatement;

class Connection implements ConnectionInterface
{
    private PDO $pdo;

    /**
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $password
     * @param array       $options
     */
    public function __construct(string $dsn, ?string $user, ?string $password, array $options = [])
    {
        $this->pdo = new PDO($dsn, $user, $password, $options);
    }

    /**
     * Voor eenvoudige SELECT: retourneer alle rijen als array.
     *
     * @param string      $sql
     * @param mixed       ...$params  Positional of een enkele associative array
     * @return array
     */
    public function fetchAll(string $sql, mixed ...$params): array
    {
        $stmt = $this->pdo->prepare($sql);
        $args = $this->normalizeParams($params);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }

    /**
     * Voor INSERT, UPDATE, DELETE: retourneer het aantal affected rows.
     *
     * @param string      $query
     * @param mixed       ...$params  Positional of een enkele associative array
     * @return int
     */
    public function execute(string $query, mixed ...$params): int
    {
        $stmt = $this->pdo->prepare($query);
        $args = $this->normalizeParams($params);
        $stmt->execute($args);
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Voor tests: direct PDO-object ophalen.
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Zet variadic $params om tot een array geschikt voor PDO::execute().
     *
     * @param array $params
     * @return array
     */
    private function normalizeParams(array $params): array
    {
        // Als er precies één parameter is en dat is een array, gebruik dat
        if (count($params) === 1 && is_array($params[0])) {
            return $params[0];
        }
        // Anders gebruik de volledige variadic lijst
        return $params;
    }

    public function query(string $query, ...$params): array
    {
        // TODO: Implement query() method.
    }

    public function getLastInsertId(): int
    {
        // TODO: Implement getLastInsertId() method.
    }
}
