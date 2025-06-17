<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Http\Session;
use Framework\Database\Connection;
use App\DataMapper\UserMapper;
use App\Provider\DbUserProvider;
use Framework\AccessControl\AuthenticationService;

class LoginController
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 1) Session en DB-connection instantiëren

        $session = new Session();

        $dbConfig = require __DIR__ . '/../../../config/database.php';
        $connection = new Connection(
            $dbConfig['dsn'],
            $dbConfig['user'],
            $dbConfig['password'],
            $dbConfig['options']
        );

        // 2) UserProvider en AuthService
        $userMapper = new UserMapper($connection);
        $provider   = new DbUserProvider($userMapper);
        $auth       = new AuthenticationService($session, $provider);

        // 3) POST-login afhandelen
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';

            if ($auth->login($username, $password)) {
                // redirect naar home
                $body = new Stream(fopen('php://memory','r+'));
                return new Response(302, ['Location' => '/'], $body);
            }
            $error = 'Ongeldige gebruikersnaam of wachtwoord';
        }

        // 4) Toon het formulier
        $html = file_get_contents(__DIR__ . '/../View/login.html');
        $html = str_replace('{{ error }}', $error ?? '', $html);

        $body = new Stream(fopen('php://memory','r+'));
        $body->write($html);
        return new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], $body);
    }
}
