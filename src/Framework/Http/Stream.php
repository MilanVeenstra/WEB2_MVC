<?php
namespace Framework\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    /** @var resource|null */
    private $resource;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Stream resource must be a valid PHP resource');
        }
        $this->resource = $resource;
        // Zorg dat we beginnen aan het begin
        rewind($this->resource);
    }

    public function __toString(): string
    {
        if (!$this->resource) {
            return '';
        }
        $this->rewind();
        return stream_get_contents($this->resource);
    }

    public function close(): void
    {
        if ($this->resource) {
            fclose($this->resource);
        }
        $this->resource = null;
    }

    public function detach()
    {
        $res = $this->resource;
        $this->resource = null;
        return $res;
    }

    public function getSize(): ?int
    {
        if (!$this->resource) {
            return null;
        }
        $stats = fstat($this->resource);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        if (!$this->resource) {
            throw new \RuntimeException('No resource available');
        }
        return ftell($this->resource);
    }

    public function eof(): bool
    {
        return !$this->resource || feof($this->resource);
    }

    public function isSeekable(): bool
    {
        if (!$this->resource) {
            return false;
        }
        $meta = stream_get_meta_data($this->resource);
        return $meta['seekable'];
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (! $this->isSeekable()) {
            throw new \RuntimeException('Stream is not seekable');
        }
        fseek($this->resource, $offset, $whence);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if (!$this->resource) {
            return false;
        }
        $meta = stream_get_meta_data($this->resource);
        $mode = $meta['mode'];
        return (strstr($mode, 'w') !== false) || (strstr($mode, '+') !== false);
    }

    public function write($string): int
    {
        if (! $this->isWritable()) {
            throw new \RuntimeException('Stream is not writable');
        }
        $bytes = fwrite($this->resource, $string);
        // **Belangrijk**: na schrijven de pointer terug naar het begin zetten
        $this->rewind();
        return $bytes;
    }

    public function isReadable(): bool
    {
        if (! $this->resource) {
            return false;
        }
        $meta = stream_get_meta_data($this->resource);
        $mode = $meta['mode'];
        return (strstr($mode, 'r') !== false) || (strstr($mode, '+') !== false);
    }

    public function read($length): string
    {
        if (! $this->isReadable()) {
            throw new \RuntimeException('Stream is not readable');
        }
        return fread($this->resource, $length);
    }

    public function getContents(): string
    {
        if (! $this->resource) {
            return '';
        }
        return stream_get_contents($this->resource);
    }

    public function getMetadata($key = null)
    {
        if (! $this->resource) {
            return $key ? null : [];
        }
        $meta = stream_get_meta_data($this->resource);
        if ($key === null) {
            return $meta;
        }
        return $meta[$key] ?? null;
    }
}
