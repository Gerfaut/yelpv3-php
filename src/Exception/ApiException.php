<?php

namespace Gerfaut\Yelp\Exception;

use \Exception as BaseException;

/**
 * Class ApiException
 * @package Gerfaut\Yelp\Exception
 */
class ApiException extends BaseException
{
    /**
     * Response body
     *
     * @var string
     */
    protected $responseBody;

    /**
     * Set exception response body from Http request
     *
     * @param string $body
     *
     * @return  ApiException
     */
    public function setResponseBody($body = null)
    {
        $this->responseBody = $body;

        return $this;
    }

    /**
     * Get exception response body
     *
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
