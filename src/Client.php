<?php

/**
 * Passmarked\Client
 *
 * Makes requests to the Passmarked API and returns the results
 * wrapped in Passmarked\Helper objects
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
 * @link       http://packagist.org/packages/passmarked/passmarked
 * @link       https://github.com/passmarked/php
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

    /** @var string Passmarked/Php Client version. */
    private $version = "1.0";

    /** @var Passmarked\RequestFactory  */
    private $request_factory;

    /** @var Passmarked\HelperFactory */
    private $helper_factory;

    /**
     * @param array $config Our config, with any guzzle options included
     */
    public function __construct( array $config = [] ) {

        // Split our options from guzzle options
        // unrecognised options will be passed to guzzle
        $accepted = [ 'telemetry' => '', 'api_url' => '', 'api_version' => '', 'http_version' => '', 'api_token' => '' ];
        $our_options = array_intersect_key( $config, $accepted );
        $guzzle_options = array_diff_key( $config, $our_options );

        // Check config and fallback to defaults as required
        array_key_exists( 'telemetry', $our_options )    || $our_options['telemetry'] = true;
        array_key_exists( 'api_url', $our_options )      || $our_options['api_url'] = 'https://api.passmarked.com';
        array_key_exists( 'api_version', $our_options )  || $our_options['api_version'] = '2';
        array_key_exists( 'http_version', $our_options ) || $our_options['http_version'] = '1.1';
        array_key_exists( 'api_token', $our_options )    || $our_options['api_token'] = '';

        if( !array_key_exists( 'handler', $guzzle_options ) ) {
            $guzzle_options['handler'] = new HandlerStack();
            $guzzle_options['handler']->setHandler( new CurlHandler() );
        }

        // Intialise factories
        $this->request_factory = new RequestFactory( $our_options );
        $this->helper_factory = new HelperFactory();

        // Inject request headers
        $guzzle_options['handler']->push( \GuzzleHttp\Middleware::mapRequest( function ( RequestInterface $request ) {
            // Prepend Passmarked/Php User-Agent info
            $user_agent = $request->getHeader( 'User-Agent' );
            $request = $request->withoutHeader( 'User-Agent' );
            $request = $request->withHeader( 'User-Agent', "Passmarked/Php/{$this->version} {$user_agent[0]}" );
            return $request;
        }));

        // Inject/Exctract response information
        $guzzle_options['handler']->push( \GuzzleHttp\Middleware::mapResponse( function ( ResponseInterface $response ) {
            return $response;
        }));

        parent::__construct( $guzzle_options );
    }

    /**
     * @param string $method_called
     * @param array $args
     */
    public function __call( $method_called, $args ) {

        if(method_exists($this->request_factory, $method_called)){
            $psr7_request = call_user_func_array( [$this->request_factory,$method_called], $args );
            $psr7_response = $this->send( $psr7_request );
            $helper = call_user_func_array( [$this->helper_factory, $method_called], [$psr7_response] );
            return $helper;
        } else {
            // NoSuchAPIMethodException
            throw new \Exception("NoSuchApiMethodException");
        }
    }

}