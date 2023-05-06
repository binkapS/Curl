<?php

namespace Binkap\Curl\Curl;

use Binkap\Curl\Interface\FieldInterface;

class Field implements FieldInterface
{
    /**
     * Field Key
     *
     * @var string
     */
    private string $key;

    /**
     * Field Value
     *
     * @var mixed
     */
    private mixed $value;

    /**
     * Class Constructor
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function __construct(string $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Get Formatted fields
     *
     * @return string
     */
    public function getFormatted(): string
    {
        return \json_encode([$this->key, $this->value]);
    }

    /**
     * Get Raw field
     *
     * @return array
     */
    public function getRaw(): array
    {
        return [$this->key => $this->value];
    }
}
