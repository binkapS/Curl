<?php

declare(strict_types=1);

namespace Binkap\Curl\Curl;

/**
 * Request Methods
 */
enum Method: string
{
    /**
     * Get request method
     */
    case GET = "GET";

    /**
     * POST request method
     */
    case POST = "POST";

    /**
     * PATCH request method
     */
    case PATCH = "PATCH";

    /**
     * PUT request method
     */
    case PUT = "PUT";

    /**
     * DELETE request method
     */
    case DELETE = "DELETE";
}
