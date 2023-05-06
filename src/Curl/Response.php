<?php

namespace Binkap\Curl\Curl;

class Response
{
    /**
     * CUrl response
     *
     * @var string|false
     */
    private string|false $response;

    /**
     * Class Constructor
     *
     * @param  string|false $response
     */
    public function __construct(string|false $response)
    {
        $this->response = $response;
    }

    /**
     * Get the raw response
     *
     * @return string|false
     */
    public function getRaw(): string|false
    {
        return $this->response;
    }

    public function getFormatted(bool $asArray = true): array|object
    {
        return \json_decode($this->response, $asArray);
    }
}
