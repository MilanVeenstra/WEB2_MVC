<?php
namespace App\Model;

use Framework\AccessControl\UserInterface as AccessUser;

class User implements AccessUser
{
    private ?int $id;
    private string $username;
    private string $passwordHash;
    private array $roles;

    public function __construct(
        ?int   $id,
        string $username,
        string $passwordHash,
        array  $roles = ['user']
    ) {
        $this->id           = $id;
        $this->username     = $username;
        $this->passwordHash = $passwordHash;
        $this->roles        = $roles;
    }

    public function getId(): mixed                { return $this->id; }
    public function setId(int $id): void          { $this->id = $id; }
    public function getUsername(): string         { return $this->username; }
    public function getPasswordHash(): string     { return $this->passwordHash; }
    public function getRoles(): array             { return $this->roles; }
    public function isAnonymous(): bool           { return false; }
}
