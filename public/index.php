<?php
// public/index.php

// 1) Fouten tonen (alleen in dev)
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Framework\Http\ServerRequest;
use Framework\DependencyInjection\Container;

// 2) Laad service‐definities en maak container
$serviceDefs = require __DIR__ . '/../config/services.php';
$container   = new Container($serviceDefs);

// 3) Maak PSR-7 Request
$request  = ServerRequest::fromGlobals();

// 4) Haal Kernel uit container en verwerk request
/** @var \Framework\Kernel\Kernel $kernel */
$kernel   = $container->get(\Framework\Kernel\Kernel::class);
$response = $kernel->handle($request);

// 5) Zend HTTP‐status en headers
http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header("{$name}: {$value}", false);
    }
}

// 6) Echo body
echo $response->getBody()->getContents();
