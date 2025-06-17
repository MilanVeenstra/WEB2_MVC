<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Templating\TemplateEngine;

class IndexController
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 1) Bepaal pad naar de views-map
        $viewsDir = __DIR__ . '/../View';



        // 2) Maak de template-engine
        $engine = new TemplateEngine($viewsDir);

        // 3) Render de 'index.tpl' met data
        $html = $engine->render('index.html', [
            'message' => 'Welkom bij mijn MVC-framework!'
        ]);

        // 4) Stop de HTML in een Stream
        $body = new Stream(fopen('php://memory', 'r+'));
        $body->write($html);

        // 5) Geef Response terug met juiste content-type
        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=utf-8'],
            $body
        );


    }
}
