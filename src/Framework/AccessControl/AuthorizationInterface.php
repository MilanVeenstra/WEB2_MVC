<?php
namespace Framework\AccessControl;

interface AuthorizationInterface
{
    public function isGranted(UserInterface $user, string $role): bool;
}
