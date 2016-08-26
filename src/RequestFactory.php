<?php

/**
 * Passmarked\RequestFactory
 *
 * Creates Guzzle\HttpPsr7\Request instances for each API function
 *
 * PHP version 5.6
 *
 * Copyright 2016 Passmarked Inc
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    Passmarked
 * @author     Werner Roets <werner@io.co.za>
 * @copyright  2016 Passmarked Inc
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link       http://pear.php.net/package/PackageName
 */

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

    public function getReports($token = '') {
        return new Request(
            'GET', 
            $this->getBaseUri()."/reports?token={$token}", 
            [], 
            null, 
            $this->config['http_version']
        );
    }

    public function getReport($key = '',$token = '') {
        return new Request(
            'GET', 
            $this->getBaseUri()."/reports/{$key}?token={$token}", 
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

    public function create($params){

        // This function only accepts an array
        if( !is_array($params) ) {
            throw new \Exception(__METHOD__.' expects type array');
        }  
        // Check that URL was passed
        if( !array_key_exists('url', $params) || !$params['url']  ) {
            throw new \Exception("URL Required");
        }

        // Check that token was passed
        if( !array_key_exists('token', $params) || !$params['token'] ) {
            // Or get from config
            $params['token'] = $this->getTokenFromConfig();
        }

        // Required arguments
        $body = "url={$params['url']}&token={$params['token']}";

        // These were already processed
        unset($params['url']);
        unset($params['token']);

        // Add any other params
        foreach( $params as $param => $v ) {

            switch( $param ) {

                case 'recursive':
                    // Recursive is either true or false                
                    $body .= $v ? '&recursive=true' : '&recursive=false';
                    break;

                case 'limit':
                    // Limit is either limit or 0 (0 is no limit)                
                    $body .= $v ? "&limit={$v}" : '&limit=0';
                    break;
                    
                case 'bail':
                    // Bail is true or false                
                    $body .= $v ? '&bail=true' : '&bail=false';
                    break;

                case 'level':
                    $body .= $v ? "&level={$v}" : '';
                    break;
                
                case 'patterns':
                    foreach( $v as $pattern ) {
                        $body .= $pattern ? "&patterns[]={$pattern}" : '';
                    }
                    break;
            }
        }

        // Return request
        return new Request(
            'POST', 
            $this->getBaseUri()."reports", 
            ['Content-Type' => 'application/x-www-form-urlencoded'], 
            $body, 
            $this->config['http_version']
        );
    }
}