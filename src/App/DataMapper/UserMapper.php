<?php
namespace App\DataMapper;

use Framework\Database\ConnectionInterface;
use Framework\Database\DataMapperInterface;
use App\Model\User;
use Framework\Database\QueryInterface;

class UserMapper implements DataMapperInterface
{
    private ConnectionInterface $conn;

    public function __construct(ConnectionInterface $connection)
    {
        $this->conn = $connection;
    }

    public function insert($object): void
    {
        if (! $object instanceof User) {
            throw new \InvalidArgumentException('UserMapper krijgt geen User-object');
        }
        $this->conn->execute(
            'INSERT INTO users (username, password_hash) VALUES (:u, :p)',
            ['u' => $object->getUsername(), 'p' => $object->getPasswordHash()]
        );
        $lastId = $this->conn->getPdo()->lastInsertId();
        $object->setId((int)$lastId);
    }

    public function update($object): void
    {
        if (! $object instanceof User) {
            throw new \InvalidArgumentException('UserMapper krijgt geen User-object');
        }
        $this->conn->execute(
            'UPDATE users SET username = :u, password_hash = :p WHERE id = :id',
            ['u' => $object->getUsername(), 'p' => $object->getPasswordHash(), 'id' => $object->getId()]
        );
    }

    /**
     * Zoek een gebruiker op ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        $rows = $this->conn->fetchAll(
            'SELECT id, username, password_hash FROM users WHERE id = :id',
            ['id' => $id]
        );
        if (empty($rows)) {
            return null;
        }
        $data = $rows[0];
        return new User(
            (int)$data['id'],
            $data['username'],
            $data['password_hash']
        );
    }

    /**
     * Zoek een User op gebruikersnaam.
     *
     * @param string $username
     * @return \App\Model\User|null
     */
    public function findByUsername(string $username): ?User
    {
        $rows = $this->conn->fetchAll(
            'SELECT id, username, password_hash FROM users WHERE username = :u',
            ['u' => $username]
        );

        if (empty($rows)) {
            return null;
        }

        $data = $rows[0];
        return new User(
            (int)$data['id'],
            $data['username'],
            $data['password_hash']
        );
    }


    public function get(int $id): object
    {
        // TODO: Implement get() method.
    }

    public function select(QueryInterface $query): array
    {
        // TODO: Implement select() method.
    }

    public function delete($object): void
    {
        // TODO: Implement delete() method.
    }
}
