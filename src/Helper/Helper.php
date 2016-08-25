<?php

namespace Passmarked\Helper;

use Psr\Http\Message\ResponseInterface;

class Helper {
    protected $response;
    protected $properties;
    public function __construct(ResponseInterface $response){
        $this->properties = json_decode($response->getBody());
        if( !$this->properties ) throw new \Exception("Can't parse JSON");
        // TODO Here we check status property for errors
        $this->response = $response;
    }

    public function __get($property) {
        return $this->get($property);
    }

    public function get($property) {
        if( property_exists($this->properties,$property)) {
            return $this->properties->$property;
        } else {
            if ( property_exists($this->properties->item,$property) ) {
                return $this->properties->item->$property;
            } else {
                return null;
            }
        }
    }

    public function getPsr7Response(){
        return $this->response;
    }
}