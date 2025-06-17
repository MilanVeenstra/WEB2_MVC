<?php
namespace Framework\AccessControl;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Stream;
use Framework\Http\Response;

class Firewall implements FirewallInterface
{
    private AuthenticationInterface  $auth;
    private AuthorizationInterface   $authz;
    private array                    $publicPaths;
    private array                    $rules;

    public function __construct(
        AuthenticationInterface $auth,
        AuthorizationInterface  $authz,
        array                   $publicPaths,
        array                   $rules
    ) {
        $this->auth        = $auth;
        $this->authz       = $authz;
        $this->publicPaths = $publicPaths;
        $this->rules       = $rules;
    }

    public function check(ServerRequestInterface $request): ?ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $this->auth->authenticate();
        $user = $this->auth->getUser();

        // publieke paden
        foreach ($this->publicPaths as $path) {
            if ($uri === $path) {
                return null;
            }
        }

        // niet ingelogd?
        if ($user === null) {
            $body = new Stream(fopen('php://memory','r+'));
            $body->write('Redirecting to login...');
            return new Response(302, ['Location'=>'/login'], $body);
        }

        // rolregels
        foreach ($this->rules as $prefix => $roles) {
            if (str_starts_with($uri, $prefix)) {
                foreach ($roles as $role) {
                    if ($this->authz->isGranted($user, $role)) {
                        return null;
                    }
                }
                // geen permissie
                $body = new Stream(fopen('php://memory','r+'));
                $body->write('403 Forbidden');
                return new Response(403, ['Content-Type'=>'text/plain'], $body);
            }
        }

        return null; // standaard toegestaan
    }
}
