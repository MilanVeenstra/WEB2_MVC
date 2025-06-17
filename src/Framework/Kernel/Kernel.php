<?php
namespace Framework\Kernel;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Routing\Router;
use Framework\Templating\TemplateEngineInterface;
use Framework\EventDispatcher\EventDispatcherInterface;
use Framework\EventDispatcher\ExceptionEvent;
use Framework\Http\Response;

class Kernel implements KernelInterface
{
    public function __construct(
        private array                      $middleware,
        private Router                     $router,
        private TemplateEngineInterface    $templating,
        private EventDispatcherInterface   $dispatcher
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Core handler voor routing & controller-dispatch
        $coreHandler = new class($this->router, $this->templating) implements RequestHandlerInterface {
            public function __construct(
                private Router                  $router,
                private TemplateEngineInterface $templating
            ) {}

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                // Laad routes
                (require __DIR__ . '/../../../config/routes.php')($this->router);

                try {
                    $route = $this->router->match($request);
                } catch (\RuntimeException $e) {
                    // 404 Response
                    $stream = fopen('php://memory','r+');
                    fwrite($stream, '404 Not Found');
                    rewind($stream);
                    return new Response(
                        404,
                        ['Content-Type' => 'text/plain'],
                        new \Framework\Http\Stream($stream)
                    );
                }

                $controller = new ($route->getHandler())();
                return $controller->handle($request);
            }
        };

        // Bouw middleware-keten
        $handler = $coreHandler;
        foreach (array_reverse($this->middleware) as $mw) {
            $handler = new MiddlewareHandler($mw, $handler);
        }

        // Verwerk request door de pipeline
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            // Dispatch ExceptionEvent
            $this->dispatcher->dispatch(new ExceptionEvent($e));

            // 500 Response
            $stream = fopen('php://memory','r+');
            fwrite($stream, '500 Internal Server Error');
            rewind($stream);

            return new Response(
                500,
                ['Content-Type' => 'text/plain'],
                new \Framework\Http\Stream($stream)
            );
        }
    }
}
