<?php

namespace Passmarked\Response;

class Websites extends \Passmarked\Psr7\Response {
    public function __construct($response){
        parent::__construct($response); 
    }
} 