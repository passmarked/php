<?php

namespace Passmarked;

use GuzzleHttp\Psr7\Request;

class RequestFactory {

    private $config;

    public function __construct($config){
        $this->config = $config;
    }

    private function getBaseUri(){
        return "{$this->config['api_url']}/v{$this->config['api_version']}/";
    }

    private function getTokenFromConfig() {
        if(array_key_exists('api_token',$this->config)) {
            return $this->config['api_token'];
        } else {
            throw new \Exception("No API token in config");
        }
    }

    public function getWebsites($token = '') {
        if( !$token ) {
            $token = $this->getTokenFromConfig();
        }
        return new Request(
            'GET', 
            $this->getBaseUri()."websites?token={$token}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getWebsite($id, $token = '') {
        if( !$token ) {
            $token = $this->getTokenFromConfig();
        }
        return new Request(
            'GET', 
            $this->getBaseUri()."websites/{$id}/?token={$token}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getReport($key = '') {
        return new Request(
            'GET', 
            $this->getBaseUri()."/reports/{$key}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getBalance($token = '') {
        if( !$token ) {
            $token = $this->getTokenFromConfig();
        }
        return new Request(
            'GET', 
            $this->getBaseUri()."balance?token={$token}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getProfile($token = '') {
        if( !$token ) {
            $token = $this->getTokenFromConfig();
        }
        return new Request(
            'GET', 
            $this->getBaseUri()."user?token={$token}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function create(
        $url, 
        $token = '', 
        $recursive = false, 
        $limit = 0, 
        $bail = false, 
        $level = 0, 
        $patterns = [], 
        $filters = []
    ) {
        
        if( !$url ) {
            // Url is required
            throw new \Exception("URL Required");
        }

        if( !$token ) {
            $token = $this->getTokenFromConfig();
            if(!$token) {
                // We must have a token
                throw new \Exception("Token Required");
            }
        }
        $body = "url={$url}&token={$token}";
        $body .= $recursive ? '&recursive=true' : '';
        $body .= $limit && is_int($limit) ? "&limit={$limit}" : "&limit=0";
        $body .= $bail && is_bool($bail) ? "&bail=true" : '';
        $body .= $level && is_int($level) ? "&level={$level}" : '';
        if( $patterns ) {
            foreach( $patterns as $pattern) {
                $body .= is_string($pattern) ? "&patterns[]={$pattern}" : '';                
            }
        }

        return new Request(
            'POST',
            $this->getBaseUri()."reports",
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $body,
            $this->config['http_version']
        );
    }

}