<?php

namespace Passmarked\Call;

class Websites extends \Passmarked\Call\BaseCall {
    public function __construct() {
        parent::__construct();
    }

    public function getMethod() {
        return 'GET';
    }

    public function getUri() {
        return $this->getBaseUri() . "websites?token={$this->config['api_token']}";
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