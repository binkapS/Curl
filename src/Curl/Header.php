<?php

declare(strict_types=1);

namespace Binkap\Curl\Curl;

/**
 * Curl Http Headers
 */
class Header
{
    /**
     * Curl Http Headers
     *
     * @var array
     */
    private array $headers;

    /**
     * Class constructors
     *
     * @param  array $headers Should be in the format: ['Content-type' => 'application/json'] Associative array
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * Add a header e.g $key = 'Content-type'; $value = 'application/json';
     *
     * @param  string $key e.g 'Content-type'
     * @param  string|integer|boolean $value e.g 'application/json'
     *
     * @return self
     */
    public function addHeader(string $key, string|int|bool $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Check if header key already exists in Values
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function KeyExists(string $key): bool
    {
        return \in_array($key, \array_keys($this->headers), true);
    }

    /**
     * Check if header value already exists in Values
     *
     * @param  mixed  $value
     *
     * @return boolean
     */
    public function ValueExists(mixed $value): bool
    {
        return \in_array($value, \array_values($this->headers), true);
    }

    /**
     * Merge headers with existing ones
     *
     * @param  Header|array $headers
     *
     * @return void
     */
    public function merge(Header|array $headers)
    {
        $merge = ($headers instanceof Header)
            ? $headers->getRaw()
            : $headers;
        $this->headers = \array_merge($this->headers, $merge);
        return $this;
    }

    /**
     * Get raw headers as array
     *
     * @return array
     */
    public function getRaw(): array
    {
        return $this->headers;
    }

    /**
     * Get Curl ready Http headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->format();
    }

    /**
     * Format the headers for CURLOPT_HTTPHEADER
     *
     * @return array
     */
    private function format(): array
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = "{$key}: {$value}";
        }
        return $headers;
    }
}
