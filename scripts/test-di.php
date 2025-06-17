<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\DependencyInjection\Container;

// 1) Laad de service‐definities
$defs = require __DIR__ . '/../config/services.php';

// 2) Maak de container
$container = new Container($defs);

// 3) Haal een paar services op en toon hun klassen
echo "SessionInterface => "
    . get_class($container->get(\Framework\Http\SessionInterface::class)) . PHP_EOL;

echo "ConnectionInterface => "
    . get_class($container->get(\Framework\Database\ConnectionInterface::class)) . PHP_EOL;

echo "UserMapper => "
    . get_class($container->get(\App\DataMapper\UserMapper::class)) . PHP_EOL;

echo "AuthenticationInterface => "
    . get_class($container->get(\Framework\AccessControl\AuthenticationInterface::class)) . PHP_EOL;

echo "Kernel => "
    . get_class($container->get(\Framework\Kernel\Kernel::class)) . PHP_EOL;
