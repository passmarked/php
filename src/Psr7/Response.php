<?php

namespace Passmarked\Psr7;

use \Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response {

    public function __construct(ResponseInterface $response) {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }
}