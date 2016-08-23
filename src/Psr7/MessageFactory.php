<?php

namespace Passmarked\Psr7;

use Passmarked\Psr7\Request;
use Passmarked\Psr7\Response;

class MessageFactory implements \Http\Message\MessageFactory
{
    /**
     * {@inheritdoc}
     */
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        echo "Request Factory";
        return new Request($method, $uri, $headers, $body, $protocolVersion);
    }
    /**
     * {@inheritdoc}
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        echo " Response Factory! ";
        return new Response($statusCode, $headers, $body, $protocolVersion, $reasonPhrase);
    }
}