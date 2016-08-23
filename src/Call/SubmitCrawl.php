<?php

namespace Passmarked\Call;

class SubmitCrawl extends \Passmarked\Call\BaseCall {

    private $id;
    
    public function __construct($id) {
        parent::__construct();
    }

    public function getMethod() {
        return 'POST';
    }

    public function getUri() {
        return $this->getBaseUri() . "reports/{$this->id}?token={$this->config['api_token']}";
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