<?php

declare(strict_types=1);

namespace Binkap\Curl\Curl;

/**
 * Set Values for Binkap\Curl\Curl::setAuthBasic(Auth $auth);
 */
class Auth
{
    /**
     * Authentication Username
     *
     * @var string
     */
    private string $username;

    /**
     * Authentication Password
     *
     * @var string
     */
    private string $password;

    /**
     * Class Constructor
     *
     * @param  string $username
     * @param  string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get Auth Formatted Values
     *
     * @return string
     */
    public function get(): string
    {
        return $this->format();
    }

    /**
     * Format Username and Password for Binkap\Curl\Curl::setAuthBasic(Auth $auth);
     *
     * @return string
     */
    private function format(): string
    {
        return "{$this->username}:{$this->password}";
    }
}
