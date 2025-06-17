<?php
namespace Framework\AccessControl;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FirewallInterface
{
    public function check(ServerRequestInterface $request): ?ResponseInterface;
}
