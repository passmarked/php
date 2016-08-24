<?php

/**
 * Passmarked\Client
 *
 * Makes requests to the Passmarked API and returns the results 
 * wrapped in objects
 *
 * PHP version 7
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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use Passmarked\RequestFactory;
use Passmarked\HelperFactory;

class Client extends GuzzleClient {

    private $request_factory;
    private $helper_factory;
    
    public function __construct(array $guzzle_config = []) {
          
        if(!array_key_exists('handler',$guzzle_config)){
            $guzzle_config['handler'] = new HandlerStack();            
        } else {
            if( !is_a($guzzle_config['handler'],'\GuzzleHttp\HandlerStack')) {
                    throw new \Exception("Invalid handler");
            }
        }
        $this->request_factory = new RequestFactory($guzzle_config);
        $this->helper_factory = new HelperFactory($guzzle_config);

        $guzzle_config['handler']->push(\GuzzleHttp\Middleware::mapRequest(function (RequestInterface $request) {
            //Add headers etc here           
            return $request;
        }));

        $guzzle_config['handler']->push(\GuzzleHttp\Middleware::mapResponse(function (ResponseInterface $response) {
            return $response;
            // return new \Passmarked\Psr7\Response($response);
        }));

        $guzzle_config['handler']->setHandler(new CurlHandler());
        
        parent::__construct($guzzle_config);
    }

    public function __call($method_called, $args) {
        $response_name = $method_called;
        $request = call_user_func_array([$this->request_factory,$method_called],$args);
        $psr7_response = $this->send($request);
        $helper = call_user_func_array([$this->helper_factory,$method_called],[$psr7_response]);
        return $helper;

    }

}