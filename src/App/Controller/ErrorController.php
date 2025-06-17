<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Response;
use Framework\Http\Stream;

class ErrorController
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new \Exception('Test Exception for EventDispatcher');
    }
}
