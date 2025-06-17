<?php
namespace Framework\AccessControl;

class AuthorizationService implements AuthorizationInterface
{
    public function isGranted(UserInterface $user, string $role): bool
    {
        return in_array($role, $user->getRoles(), true);
    }
}
