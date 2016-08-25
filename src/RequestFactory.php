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

    public function create($url, $recursive = false, $limit = null, $bail = false, $patterns = false, $token = '') {
        if( !$token ) {
            $token = $this->getTokenFromConfig();
        }
        $body = [
             'form_params' => [
                    'url'   => $url,
                    'token' => $token,
                    'limit' => $limit,
                    'bail'  => $bail
                ]
        ];
        if( $patterns ) {
            $body['form_params']['patterns'] = $patterns;
        }

        return new Request(
            'POST', 
            $body,
            null, 
            $this->config['http_version']
        );
    }

}