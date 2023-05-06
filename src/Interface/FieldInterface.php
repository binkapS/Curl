<?php

namespace Binkap\Curl\Interface;

interface FieldInterface
{
    /**
     * Get formatted field/fields
     *
     * @return string
     */
    public function getFormatted(): string;

    /**
     * /**
     * Get Raw field
     *
     * @return array
     */
    public function getRaw(): array;
}
