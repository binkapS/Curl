<?php

declare(strict_types=1);

namespace Binkap\Curl\Curl;

/**
 * Values for the Binkap\Curl\Curl::setHttpVersion(HttpVersion $version);
 */
enum HttpVersion: int
{
    /**
     * Forces HTTP/1.0.
     */
    case VERSION_1_0 = \CURL_HTTP_VERSION_1_0;

    /**
     * Forces HTTP/1.1.
     */
    case VERSION_1_1 = \CURL_HTTP_VERSION_1_1;

    /**
     * Attempts HTTP 2
     */
    case VERSION_2_0 = \CURL_HTTP_VERSION_2_0;

    /**
     * Attempts HTTP 2 over TLS (HTTPS) only
     */
    case VERSION_2TLS = \CURL_HTTP_VERSION_2TLS;

    /**
     * Lets CURL decide which version to use
     */
    case VERSION_NONE = \CURL_HTTP_VERSION_NONE;

    /**
     * Issues non-TLS HTTP requests using HTTP/2 without HTTP/1.1 Upgrade
     */
    case VERSION_2_PRIOR_KNOWLEDGE = \CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE;
}
