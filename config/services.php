<?php
declare(strict_types=1);

// config/services.php

use Framework\Http\SessionInterface;
use Framework\Http\Session;
use Framework\Database\ConnectionInterface;
use Framework\Database\Connection;
use App\DataMapper\UserMapper;
use Framework\AccessControl\UserProviderInterface;
use App\Provider\DbUserProvider;
use Framework\AccessControl\AuthenticationInterface;
use Framework\AccessControl\AuthenticationService;
use Framework\AccessControl\AuthorizationInterface;
use Framework\AccessControl\AuthorizationService;
use Framework\AccessControl\FirewallInterface;
use Framework\AccessControl\Firewall;
use Framework\Routing\Router;
use Framework\Templating\TemplateEngineInterface;
use Framework\Templating\TemplateEngine;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Framework\Kernel\Kernel;
use Framework\Middleware\SessionStartMiddleware;
use Framework\Middleware\AuthenticationMiddleware;
use Framework\Middleware\FirewallMiddleware;
use Framework\Middleware\LoggingMiddleware;
use Framework\EventDispatcher\EventDispatcherInterface;
use Framework\EventDispatcher\EventDispatcher;
use App\Subscriber\ErrorLoggingSubscriber;

return [
    // 1) Session
    SessionInterface::class => fn($c) => new Session(),

    // 2) DB-configuratie
    'db.config' => require __DIR__ . '/database.php',

    // 3) PDO Connection
    ConnectionInterface::class => function($c) {
        $cfg = $c->get('db.config');
        return new Connection(
            $cfg['dsn'], $cfg['user'], $cfg['password'], $cfg['options']
        );
    },

    // 4) DataMapper
    UserMapper::class => fn($c) => new UserMapper(
        $c->get(ConnectionInterface::class)
    ),

    // 5) Authentication & Authorization
    UserProviderInterface::class   => fn($c) => new DbUserProvider(
        $c->get(UserMapper::class)
    ),
    AuthenticationInterface::class => fn($c) => new AuthenticationService(
        $c->get(SessionInterface::class),
        $c->get(UserProviderInterface::class)
    ),
    AuthorizationInterface::class  => fn($c) => new AuthorizationService(),

    // 6) Firewall service
    FirewallInterface::class => function($c) {
        $sec = require __DIR__ . '/security.php';
        return new Firewall(
            $c->get(AuthenticationInterface::class),
            $c->get(AuthorizationInterface::class),
            $sec['public_paths'],
            $sec['firewall']
        );
    },

    // 7) Router
    Router::class => fn($c) => new Router(),

    // 8) Templating
    TemplateEngineInterface::class => fn($c) => new TemplateEngine(
        __DIR__ . '/../src/App/View'
    ),

    // 9) Logger (Monolog)
    LoggerInterface::class => fn($c) => new Logger('app'),

    // 10) Concrete middleware
    SessionStartMiddleware::class   => fn($c) => new SessionStartMiddleware(
        $c->get(SessionInterface::class)
    ),
    AuthenticationMiddleware::class => fn($c) => new AuthenticationMiddleware(
        $c->get(AuthenticationInterface::class)
    ),
    FirewallMiddleware::class       => fn($c) => new FirewallMiddleware(
        $c->get(FirewallInterface::class)
    ),
    LoggingMiddleware::class        => function($c) {
        $logger = $c->get(LoggerInterface::class);
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::INFO));
        return new LoggingMiddleware($logger);
    },

    // 11) Event system aanmeldingen (stap 6)
    ErrorLoggingSubscriber::class => fn($c) => new ErrorLoggingSubscriber(
        $c->get(LoggerInterface::class)
    ),
    EventDispatcherInterface::class => fn($c) => new EventDispatcher([
        $c->get(ErrorLoggingSubscriber::class),
    ]),

    // 12) Middleware-pipeline stack
    'middleware.stack' => fn($c) => [
        $c->get(LoggingMiddleware::class),
        $c->get(SessionStartMiddleware::class),
        $c->get(AuthenticationMiddleware::class),
        $c->get(Framework\Middleware\FirewallMiddleware::class),
    ],

    // 13) Kernel (inject pipeline, router, templating, dispatcher)
    Kernel::class => function($c) {
        return new Kernel(
            $c->get('middleware.stack'),
            $c->get(Router::class),
            $c->get(TemplateEngineInterface::class),
            $c->get(EventDispatcherInterface::class)
        );
    },
];
