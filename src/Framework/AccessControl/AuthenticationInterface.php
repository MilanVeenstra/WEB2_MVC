<?php
namespace Framework\AccessControl;

interface AuthenticationInterface
{
    public function login(string $username, string $password): bool;
    public function logout(): void;
    public function getUser(): ?UserInterface;
    public function authenticate(): void;
}
