<?php
namespace Passmarked\Call;

abstract class BaseCall extends \Passmarked\Call\Call {

    protected $config;

    public function __construct() {
        $this->setConfig();
    }

    protected function setConfig(){
        require_once dirname(__FILE__) . '/../config.php';
        if(count($config) !== 0){
            $this->config = $config;
        } else {
            // We can't work with empty config
        }
    }

    protected function getConfig() {
        return $this->config();
    }

    protected function getBaseMethod() {
        return 'GET';
    }

    protected function getBaseUri() {

        if( array_key_exists('api_host',$this->config) && $this->config['api_host'] ) {
            $host = $this->config['api_host'];
        } else {
            $host = 'api.passmarked.com'; 
        }

        if(array_key_exists('api_scheme',$this->config) && $this->config['api_scheme'] ) {
            $scheme = $this->config['api_scheme']; 
        } else {
            $scheme = 'https://'; 
        }

        if(array_key_exists('api_version', $this->config) && $this->config['api_version']) {
            $version = $this->config['api_version'];
        } else {
            $version = '2';
        }
        return "{$scheme}{$host}/v{$version}/";
    }

    
    protected function getBaseHeaders() {
        return [
            'User-Agent' => 'passmarked/php',
            'Accept' => 'application/json',
        ];
    }


    protected function getBaseBody() {
        return null;
    }


    protected function getBaseProtocolVersion() {
        $http_versions = ['1.0','1.1','2.0','2'];
        if( array_key_exists('http_version',$this->config) &&
            in_array($this->config['http_version'],$http_versions)
        ) {
            $http_version = $this->config['http_version']; 
        } else {
            $http_version = '1.1'; 
        }
        return $http_version;
    }

    public function __toRequest(){
        $response_class = '\\Passmarked\\Reply\\' . explode('\\',get_class($this))[2];
        return new \Passmarked\Psr7\Request(
            $response_class,
            $this
        );
    }

}