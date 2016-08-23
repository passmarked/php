<?php

namespace Passmarked\Api;

class User extends \Passmarked\Api\BaseCall {
    private $key;
    public function __construct($key) {
        parent::__construct();
    }

    public function getMethod() {
        return 'GET';
    }

    public function getUri() {
        return $this->getBaseUri() . "user/?token={$this->config['api_token']}";
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
}