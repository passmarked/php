<?php

namespace Passmarked\Helper;

use Psr\Http\Message\ResponseInterface;

class GetWebsites extends Helper {
    public function __construct(ResponseInterface $response){
        parent::__construct($response);
    }
} 