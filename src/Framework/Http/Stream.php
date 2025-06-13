<?php

namespace Framework\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    public function __construct(private $stream)
    {
    }

    public function __toString(): string
    {
        if (!$this->stream) {
            return '';
        }
        rewind($this->stream);
        $result = fread($this->stream, $this->getSize());
        return $result === false ? '' : $result;
    }

    #[\Override] public function close(): void
    {
        fclose($this->stream);
        $this->stream = null;
    }

    #[\Override] public function detach()
    {
        $stream = $this->stream;
        $this->stream = null;
        return $stream;
    }

    #[\Override] public function getSize(): ?int
    {
        if (!$this->stream) {
            return null;
        }
        $stat = fstat($this->stream);
        return $stat['size'] ?? null;
    }

    #[\Override] public function tell(): int
    {
        if (!$this->stream) {
            throw new \RuntimeException();
        }
        $result = ftell($this->stream);
        if ($result === false) {
            throw new \RuntimeException();
        }
        return $result;
    }

    #[\Override] public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    #[\Override] public function isSeekable(): bool
    {
        return $this->stream && $this->getMetadata('seekable');
    }

    #[\Override] public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->stream || fseek($this->stream, $offset, $whence) < 0) {
            throw new \RuntimeException();
        }
    }

    #[\Override] public function rewind(): void
    {
        if (!$this->stream || !rewind($this->stream)) {
            throw new \RuntimeException();
        }
    }

    #[\Override] public function isWritable(): bool
    {
        $mode = $this->getMetadata('mode') ?? 'r';
        return str_contains('+', $mode) || !str_contains('r', $mode);
    }

    #[\Override] public function write(string $string): int
    {
        if (!$this->stream) {
            throw new \RuntimeException();
        }
        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new \RuntimeException();
        }
        return $result;
    }

    #[\Override] public function isReadable(): bool
    {
        $mode = $this->getMetadata('mode') ?? '';
        return str_contains('+', $mode) || str_contains('r', $mode);
    }

    #[\Override] public function read(int $length): string
    {
        if (!$this->stream) {
            throw new \RuntimeException();
        }
        $result = fread($this->stream, $length);
        if ($result === false) {
            throw new \RuntimeException();
        }
        return $result;
    }

    #[\Override] public function getContents(): string
    {
        if (!$this->stream) {
            throw new \RuntimeException();
        }
        $result = fread($this->stream, $this->getSize());
        if ($result === false) {
            throw new \RuntimeException();
        }
        return $result;
    }

    #[\Override] public function getMetadata(?string $key = null)
    {
        if (!$this->stream) {
            return $key ? null : [];
        }
        $data = stream_get_meta_data($this->stream);
        return $key ? ($data[$key] ?? null) : $data;
    }
}
