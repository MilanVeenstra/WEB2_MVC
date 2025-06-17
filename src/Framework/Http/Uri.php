<?php
namespace Framework\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme   = '';
    private string $host     = '';
    private ?int   $port     = null;
    private string $path     = '/';
    private string $query    = '';
    private string $fragment = '';

    public function __construct(string $uri = '')
    {
        $parts = parse_url($uri);
        if (isset($parts['scheme']))   $this->scheme   = $parts['scheme'];
        if (isset($parts['host']))     $this->host     = $parts['host'];
        if (isset($parts['port']))     $this->port     = $parts['port'];
        if (isset($parts['path']))     $this->path     = $parts['path'];
        if (isset($parts['query']))    $this->query    = $parts['query'];
        if (isset($parts['fragment'])) $this->fragment = $parts['fragment'];
    }

    public function getScheme(): string                     { return $this->scheme; }
    public function getAuthority(): string
    {
        $auth = $this->host;
        if ($this->port) {
            $auth .= ':' . $this->port;
        }
        return $auth;
    }
    public function getUserInfo(): string                   { return ''; }
    public function getHost(): string                       { return $this->host; }
    public function getPort(): ?int                         { return $this->port; }
    public function getPath(): string                       { return $this->path; }
    public function getQuery(): string                      { return $this->query; }
    public function getFragment(): string                   { return $this->fragment; }

    public function withScheme($scheme): static
    {
        $clone = clone $this;
        $clone->scheme = $scheme;
        return $clone;
    }
    public function withUserInfo($user, $password = null): static { return clone $this; }
    public function withHost($host): static
    {
        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }
    public function withPort($port): static
    {
        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }
    public function withPath($path): static
    {
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }
    public function withQuery($query): static
    {
        $clone = clone $this;
        $clone->query = $query;
        return $clone;
    }
    public function withFragment($fragment): static
    {
        $clone = clone $this;
        $clone->fragment = $fragment;
        return $clone;
    }

    public function __toString(): string
    {
        $uri = '';
        if ($this->scheme) {
            $uri .= $this->scheme . '://';
        }
        if ($this->host) {
            $uri .= $this->host;
        }
        if ($this->port) {
            $uri .= ':' . $this->port;
        }
        $uri .= $this->path;
        if ($this->query) {
            $uri .= '?' . $this->query;
        }
        if ($this->fragment) {
            $uri .= '#' . $this->fragment;
        }
        return $uri;
    }
}
