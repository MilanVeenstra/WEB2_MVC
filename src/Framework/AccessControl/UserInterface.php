<?php
namespace Framework\AccessControl;

interface UserInterface
{
    public function getId(): mixed;
    public function getUsername(): string;
    public function getPasswordHash(): string;
    public function getRoles(): array;
    public function isAnonymous(): bool;
}
