<?php

namespace Passmarked;

use GuzzleHttp\Psr7\Request;

class RequestFactory {

    private $config;

    public function __construct($config){
        $this->config = $config;
    }

    private function getBaseUri(){
        return "{$this->config['api_scheme']}{$this->config['api_host']}/v{$this->config['api_version']}/";
    }

    public function createReport($url) {

    }

    public function getReport($id) {

    }

    public function getWebsites() {
        return new Request(
            'GET', 
            $this->getBaseUri()."websites?token={$this->config['api_token']}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getWebsite($id) {

    }

    public function getProfile() {

    }

    public function getBalance() {
        return new Request(
            'GET', 
            $this->getBaseUri()."balance?token={$this->config['api_token']}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function createRunner() {

    }
}