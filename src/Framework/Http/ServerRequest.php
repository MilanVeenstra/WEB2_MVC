<?php
namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class ServerRequest implements ServerRequestInterface
{
    private array $serverParams;
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $uploadedFiles = [];
    private ?StreamInterface $body;
    private array $attributes = [];
    private UriInterface $uri;
    private string $method;

    public function __construct(
        array $serverParams,
        UriInterface $uri,
        StreamInterface $body
    ) {
        $this->serverParams = $serverParams;
        $this->uri          = $uri;
        $this->body         = $body;
        $this->method       = $serverParams['REQUEST_METHOD'] ?? 'GET';
    }

    public static function fromGlobals(): self
    {
        $uri = new Uri(
            $_SERVER['REQUEST_URI'] ?? '/',
            $_SERVER['QUERY_STRING'] ?? '',
            $_SERVER['HTTP_HOST'] ?? ''
        );
        $body = new Stream(fopen('php://input', 'r+'));
        return new self($_SERVER, $uri, $body);
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): static
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): static
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): static
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getParsedBody(): mixed
    {
        return $_POST;
    }

    public function withParsedBody($data): static
    {
        $new = clone $this;
        // indien nodig kun je $new->parsedBody opslaan
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): static
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name): static
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }

    public function getRequestTarget(): string
    {
        return $this->uri->getPath();
    }

    public function withRequestTarget($requestTarget): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): static
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }

    public function getProtocolVersion(): string
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function withProtocolVersion($version): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getHeaders(): array
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function hasHeader($name): bool
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getHeader($name): array
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getHeaderLine($name): string
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function withHeader($name, $value): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function withAddedHeader($name, $value): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function withoutHeader($name): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getProtocol(): string
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
