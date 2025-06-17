<?php
namespace App\Provider;

use Framework\AccessControl\UserProviderInterface;
use Framework\AccessControl\UserInterface as AccessUser;
use App\DataMapper\UserMapper;

class DbUserProvider implements UserProviderInterface
{
    private UserMapper $mapper;
    private ?AccessUser $current = null;

    public function __construct(UserMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function loadUserByUsername(string $username): ?AccessUser
    {
        return $this->current = $this->mapper->findByUsername($username);
    }

    public function loadUserById(mixed $id): ?AccessUser
    {
        return $this->current = $this->mapper->findById((int)$id);
    }

    /**
     * @inheritDoc
     */
    public function get(): ?AccessUser
    {
        return $this->current;
    }
}
