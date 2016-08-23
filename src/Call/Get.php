<?php

namespace Passmarked\Call;

class Get extends \Passmarked\Call\BaseCall {
    private $key;
    public function __construct($key) {
        parent::__construct();
    }

    public function getMethod() {
        return 'GET';
    }

    public function getUri() {
        return $this->getBaseUri() . "reports/{$this->key}";
    }

    public function getHeaders() {
        return $this->getBaseHeaders();
    }

    public function getBody() {
        return $this->getBaseBody();
    }

    public function getProtocolVersion() {
        return $this->getBaseProtocolVersion();
    }

    public function __toPsr7Request(){
        // return new Guzzle/Passmarked\Psr7\Request(
        //     $this->getMethod(),
        //     $this->getUri(),
        //     $this->getBody(),
        //     $this->getProtocolVersion()
        // );
        return 'test';
    }
}