<?php
namespace Framework\AccessControl;

interface UserProviderInterface
{
    public function loadUserByUsername(string $username): ?UserInterface;
    public function loadUserById(mixed $id): ?UserInterface;

    public function get(): ?UserInterface;
}
