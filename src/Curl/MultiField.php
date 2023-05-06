<?php

namespace Binkap\Curl\Curl;

use Binkap\Curl\Interface\FieldInterface;

class MultiField implements FieldInterface
{
    /**
     * Array of Request fields Associative array (Key, Value Pairs)
     *
     * @var array
     */
    private array $fields;

    /**
     * Class Constructor
     *
     * @param  array $fields Associative array (Key, Value Pairs) e.g ['name' => 'Binkap', 'age' => 21]
     */
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * Add new Field
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return self
     */
    public function addField(string $key, mixed $value): self
    {
        $this->fields[$key] = $value;
        return $this;
    }

    /**
     * Check if field key already exists in Values
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function KeyExists(string $key): bool
    {
        return \in_array($key, \array_keys($this->fields), true);
    }

    /**
     * Check if field value already exists in Values
     *
     * @param  mixed  $value
     *
     * @return boolean
     */
    public function ValueExists(mixed $value): bool
    {
        return \in_array($value, \array_values($this->fields), true);
    }


    /**
     * Get Formatted fields
     *
     * @return string
     */
    public function getFormatted(): string
    {
        return \json_encode($this->fields);
    }


    /**
     * Get Raw field
     *
     * @return array
     */
    public function getRaw(): array
    {
        return $this->fields;
    }
}
