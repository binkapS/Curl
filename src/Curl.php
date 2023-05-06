<?php

declare(strict_types=1);

namespace Binkap\Curl;

use Binkap\Curl\Curl\Auth;
use Binkap\Curl\Curl\Header;
use Binkap\Curl\Curl\HttpVersion;
use Binkap\Curl\Curl\Method;
use Binkap\Curl\Curl\Response;
use Binkap\Curl\Exception\CurlException;
use Binkap\Curl\Interface\FieldInterface;

/**
 * Object oriented wrapper or cUrl
 */
class Curl
{
    /**
     * 
     *
     * @var \CurlHandle
     */
    private \CurlHandle $curl;

    /**
     * Http Request headers
     *
     * @var \Binkap\Curl\Curl\Headers
     */
    private Header $headers;

    /**
     * Request Url
     *
     * @var string|null
     */
    private string|null $url = null;

    /**
     * Curl response
     *
     * @var string|false|null
     */
    private string|false|null $response = null;

    /**
     * Set to true to enable library in strict mode
     * Set to false and library assist with some methods
     *
     * @var boolean
     */
    private bool $strict;

    /**
     * Curl Constructor
     *
     * @param  string|null $url The URL to fetch.
     */
    public function __construct(string|null $url = null, bool $strict = false)
    {
        if (!extension_loaded('curl')) {
            throw new CurlException('The cURL extensions is not loaded, make sure you have installed the cURL extension: https://php.net/manual/curl.setup.php');
        }
        $this->url = $url;
        $this->strict = $strict;
        $this->init();
    }

    /**
     * Set The URL to fetch.
     *
     * @param  string $url The URL to fetch.
     *
     * @return bool
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this->setOpt(CURLOPT_URL, $this->url);
    }

    /**
     * Set an option for a cURL transfer
     *
     * @param  integer $option
     * @param  mixed   $value
     *
     * @return self
     */
    public function setOpt(int $option, mixed $value): self
    {
        \curl_setopt($this->curl, $option, $value);
        return $this;
    }

    /**
     * Set The contents of the "Referer:" in a HTTP request
     *
     * @param  string $referrer The contents of the "Referer:" header to be used in a HTTP request
     *
     * @return self
     */
    public function setReferrer(string $referrer): self
    {
        return $this->setOpt(CURLOPT_REFERER, $referrer);
    }

    /**
     * TRUE to automatically set the Referer: field in requests where it follows a Location: redirect
     *
     * @param  boolean $auto
     *
     * @return self
     */
    public function setAutoReferrer(bool $auto): self
    {
        return $this->setOpt(\CURLOPT_AUTOREFERER, $auto);
    }

    /**
     * Set Authentication Info
     * 
     * Use the Auth class provided
     *
     * @param  \Binkap\Curl\Curl\Auth $auth
     *
     * @return void
     */
    public function setAuthBasic(Auth $auth)
    {
        $this->setAuthType(\CURLAUTH_BASIC);
        $this->setOpt(\CURLOPT_USERPWD, $auth->get());
    }

    /**
     * Set The contents of the "Cookie: " in a HTTP request 
     * 
     * Note that multiple cookies are separated with a semicolon followed by a space (e.g., "fruit=apple; color=red")
     *
     * @param  string $cookie The contents of the "Cookie: " header to be used in the HTTP request.
     *
     * @return self
     */
    public function setCookie(string $cookie): self
    {
        return $this->setOpt(CURLOPT_COOKIE, $cookie);
    }

    /**
     * Set Content type header
     *
     * @param  string $contentType
     *
     * @return self
     */
    public function setContentType(string $contentType): self
    {
        $this->headers->addHeader("Content-Type:", $contentType);
        return $this;
    }

    /**
     * Set Request Http Version
     *
     * @param  \Binkap\Curl\Curl\HttpVersion $version
     *
     * @return self
     */
    public function setHttpVersion(HttpVersion $version): self
    {
        return $this->setOpt(\CURLOPT_HTTP_VERSION, $version->value);
    }

    /**
     * Set The contents of the "User-Agent: " in a HTTP request
     *
     * @param  string $agent The contents of the "User-Agent: " header to be used in a HTTP request.
     *
     * @return self
     */
    public function setUserAgent(string $agent): self
    {
        return $this->setOpt(CURLOPT_USERAGENT, $agent);
    }

    /**
     * Set timeout in Seconds
     *
     * @param  integer $seconds The maximum number of seconds to allow cURL functions to execute
     *
     * @return self
     */
    public function setTimeout(int $seconds): self
    {
        return $this->setOpt(\CURLOPT_TIMEOUT, $seconds);
    }

    /**
     * Set timeout in Milliseconds
     *
     * @param  integer $milliseconds The maximum number of milliseconds to allow cURL functions to execute.
     *
     * @return self
     */
    public function setTimeoutMS(int $milliseconds): self
    {
        return $this->setOpt(\CURLOPT_TIMEOUT_MS, $milliseconds);
    }

    /**
     * Set  HTTP header fields
     *
     * @param  \Binkap\Curl\Curl\Headers|array $headers
     * If an array should be in the format: ['Content-type' => 'application/json'] Associative array
     *
     * @return self
     */
    public function setHeaders(Header|array $headers): self
    {
        $this->headers->merge($headers);
        return $this;
    }

    /**
     * FALSE to stop cURL from verifying the peer's certificate.
     *
     * @param  boolean $verify
     *
     * @return self
     */
    public function setVerifyPeer(bool $verify): self
    {
        return $this->setOpt(CURLOPT_SSL_VERIFYPEER, $verify);
    }

    /**
     * Supported encodings are "identity", "deflate", and "gzip". If an empty string, "", is set, a header containing all supported encoding types is sent.
     *
     * @param  string $encoding The contents of the "Accept-Encoding: " header. This enables decoding of the response
     *
     * @return self
     */
    public function setEncoding(string $encoding): self
    {
        return $this->setOpt(CURLOPT_ENCODING, $encoding);
    }

    /**
     * Set request method 
     *
     * @param  \Binkap\Curl\Curl\Method $method
     *
     * @return self
     */
    public function setRequestMethod(Method $method): self
    {
        return $this->setOpt(\CURLOPT_CUSTOMREQUEST, $method->value);
    }

    /**
     * TRUE to output verbose information.
     *
     * @param  bool $value
     *
     * @return self
     */
    public function setVerbose(bool $value): self
    {
        return $this->setOpt(\CURLOPT_VERBOSE, $value);
    }

    /**
     * Set whether cUrl to verify peer's certificate
     *
     * @param  boolean $verify FALSE to stop cURL from verifying the peer's certificate
     *
     * @return self
     */
    public function setVerifySSL(bool $verify): self
    {
        return $this->setOpt(\CURLOPT_SSL_VERIFYPEER, $verify);
    }


    /**
     * TRUE to return the transfer as a string
     *
     * @param boolean $return
     *
     * @return self
     */
    public function setReturnTransfer(bool $return): self
    {
        return $this->setOpt(\CURLOPT_RETURNTRANSFER, $return);
    }

    /**
     * Set POST fields for HTTP POST Request
     *
     * @param  \Binkap\Curl\Interface\FieldInterface|string $field
     * * When passing the field as a string Ensure it is in Json format
     * 
     * Its recommended you use the MultiField Class for Multiple and the Field Class for Single
     *
     * @return self
     */
    public function setPOSTFields(FieldInterface|string $field): self
    {
        $field = \is_string($field)
            ? $field
            : $field->getFormatted();
        return $this->setOpt(\CURLOPT_POSTFIELDS, $field);
    }

    /**
     * Set SSL key
     *
     * @param  mixed $key name of a file containing a private SSL key.
     *
     * @return self
     */
    public function setSSLKey(string $key): self
    {
        return $this->setOpt(\CURLOPT_SSLKEY, $key);
    }

    /**
     * Set SSL Engine
     *
     * @param  mixed $engine The identifier for the crypto engine of the private SSL key specified in setSSLKey(string $key)
     *
     * @return self
     */
    public function setSSLEngine(mixed $engine): self
    {
        return $this->setOpt(\CURLOPT_SSLENGINE, $engine);
    }

    /**
     * Set SSL Version
     *
     * @param  integer $version One of CURL_SSLVERSION_DEFAULT (0), CURL_SSLVERSION_TLSv1 (1), CURL_SSLVERSION_SSLv2 (2), CURL_SSLVERSION_SSLv3 (3), CURL_SSLVERSION_TLSv1_0 (4), CURL_SSLVERSION_TLSv1_1 (5) or CURL_SSLVERSION_TLSv1_2 (6)
     *
     * @return self
     */
    public function setSSLVersion(int $version): self
    {
        return $this->setOpt(\CURLOPT_SSLVERSION, $version);
    }

    /**
     * Set SSL certificate
     *
     * @param  string $cert The name of a file containing a PEM formatted certificate.
     *
     * @return self
     */
    public function setSSLCert(string $cert): self
    {
        return $this->setOpt(\CURLOPT_SSLCERT, $cert);
    }

    /**
     * Set An alternative port number to connect to
     *
     * @param  integer $port An alternative port number to connect to
     *
     * @return self
     */
    public function setPort(int $port): self
    {
        return $this->setOpt(\CURLOPT_PORT, $port);
    }

    /**
     * UGet information regarding a specific transfer
     *
     * @param  integer $option
     *
     * @return mixed
     */
    public function getOpt(int $option): mixed
    {
        return \curl_getinfo($this->curl, $option);
    }

    /**
     * Get The last response code
     *
     * @return integer
     */
    public function getResponseCode(): int
    {
        return $this->getOpt(\CURLINFO_RESPONSE_CODE);
    }

    /**
     * Get the current session Url
     *
     * @return string|false
     */
    public function getUrl(): string|false
    {
        return $this->getOpt(\CURLOPT_URL);
    }

    /**
     * Return a string containing the last error for the current session
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return \curl_error($this->curl);
    }

    /**
     * Return the last error number
     *
     * @return integer
     */
    public function getErrorCode(): int
    {
        return \curl_errno($this->curl);
    }

    /**
     * Get Curl Response
     *
     * @param boolean $useResponseObject
     * Set to true to get The Response Object Instead of a string
     *
     * @return \Binkap\Curl\Curl\Response|string
     */
    public function getResponse(bool $useResponseObject = \false): Response|string
    {
        /* Check if curl is executed 
            If false execute curl and strict mode is false 
        */
        if (\is_null($this->response) && !$this->strict) {
            $this->exec();
        } elseif (\is_null($this->response) && $this->strict) {
            $this->throw("Execute Call the (Curl::exec()) before using the Curl::getResponse()", 500);
        }
        return $useResponseObject
            ? new Response($this->response)
            : $this->response;
    }

    /**
     * Gets cURL version information
     *
     * @return array|false
     */
    public function getVersion(): array|false
    {
        return \curl_version();
    }

    /**
     * URL encodes the given string
     *
     *@param string $value — The string to be encoded.
     *
     *@return string|false — Returns escaped string or FALSE on failure.
     */
    public function escape(string $value): string|false
    {
        return \curl_escape($this->curl, $value);
    }

    /**
     * 
     * Decodes the given URL encoded string
     * 
     * @param string $value — The URL encoded string to be decoded.
     *
     * @return string|false — Returns decoded string or FALSE on failure.
     */
    public function unescape(string $value): string|false
    {
        return \curl_unescape($this->curl, $value);
    }

    /**
     * Pause and unpause a connection
     *
     * @param  integer $flags
     *
     * @return integer
     */
    public function pause(int $flags): int
    {
        return \curl_pause($this->curl, $flags);
    }

    /**
     * Perform a cURL session
     *
     * @return self
     */
    public function exec(): self
    {
        $this->validate();
        $this->response = \curl_exec($this->curl);
        $this->close();
        return $this;
    }

    /**
     * Set Http authentication method
     *
     * @param  integer $method
     *
     * @return self
     */
    private function setAuthType(int $method): self
    {
        $this->setOpt(\CURLOPT_HTTPAUTH, $method);
        return $this;
    }

    /**
     * Reset all options of a libcurl session handle
     *
     * @return void
     */
    public function reset()
    {
        return \curl_reset($this->curl);
    }

    /**
     * Initialize a cURL session
     *
     *
     * @return void
     */
    private function init(): void
    {
        $this->headers = new Header();
        $this->curl = \curl_init($this->url);
        if (!$this->strict) {
            $this->setDefault();
        }
    }

    /**
     * Initialize Default Params
     *
     * @return void
     */
    private function setDefault()
    {
        $this->setReturnTransfer(true);
    }

    /**
     * Close a cURL session
     *
     * @return void
     */
    private function close()
    {
        \curl_close($this->curl);
        $this->verifyResponse();
    }

    /**
     * Close a cURL session
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Validate cUrl params
     *
     * @return void
     * @throws Binkap\Curl\Exception\CurlException
     */
    private function validate(): void
    {
        if (\is_null($this->url)) {
            $this->throw("Undefined Request Url", 500);
        }
    }

    /**
     * Ensure Response is not an Error
     *
     * @return void
     */
    private function verifyResponse(): void
    {
        if (($this->getResponseCode() == 200 || $this->getResponseCode() == 201) && $this->getErrorCode() == 0) {
            return;
        }
        $this->throw($this->getErrorMessage(), $this->getResponseCode());
    }

    /**
     * Throws a Curl Exception
     *
     * @param  string  $message
     * @param  integer $code
     *
     * @return never
     */
    private function throw(string|null $message = '', int $code = 0): never
    {
        throw new CurlException($message, $code);
    }
}
