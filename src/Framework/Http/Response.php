<?php
namespace Framework\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    private int $statusCode;
    private array $headers;
    private StreamInterface $body;
    private string $protocolVersion;
    private ?string $reasonPhrase;

    public function __construct(
        int $status = 200,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1',
        ?string $reason = null
    ) {
        $this->statusCode      = $status;
        $this->headers         = $this->normalizeHeaders($headers);
        $this->body            = $body ?? new Stream(fopen('php://memory', 'r+'));
        $this->protocolVersion = $version;
        $this->reasonPhrase    = $reason ?? $this->getDefaultReason($status);
    }

    private function normalizeHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $name => $values) {
            $normalized = strtolower($name);
            $result[$normalized] = is_array($values) ? array_values($values) : [(string)$values];
        }
        return $result;
    }

    private function getDefaultReason(int $status): string
    {
        $phrases = [
            200 => 'OK',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            // … eventueel uitbreiden …
        ];
        return $phrases[$status] ?? '';
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): static
    {
        $clone = clone $this;
        $clone->statusCode   = $code;
        $clone->reasonPhrase = $reasonPhrase ?: $this->getDefaultReason($code);
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)] = is_array($value) ? array_values($value) : [(string)$value];
        return $clone;
    }

    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;
        $key   = strtolower($name);
        $clone->headers[$key] = array_merge(
            $clone->headers[$key] ?? [],
            is_array($value) ? $value : [(string)$value]
        );
        return $clone;
    }

    public function withoutHeader($name): static
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }
}
