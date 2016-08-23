<?php

namespace Passmarked\Psr7;

class Request extends \GuzzleHttp\Psr7\Request {

    private $response_class;

    public function __construct(
        $response_class,
        $api_call
    ) {
        $this->response_class = $response_class;
        parent::__construct(
            $api_call->getMethod(), 
            $api_call->getUri(), 
            $api_call->getHeaders(), 
            $api_call->getBody(), 
            $api_call->getProtocolVersion()
        );        
    }

    public function getResponseClass(){
        return $this->response_class;
    }
}