<?php

namespace Passmarked\Helper;

use Psr\Http\Message\ResponseInterface;

class Helper {
    protected $response;

    public function __construct(ResponseInterface $response){
        $this->response = $response;
    }

    public function getPsr7Response(){
        return $this->response;
    }
}