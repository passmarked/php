<?php

/**
 * Passmarked\Helper
 *
 * Wraps responses
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

namespace Passmarked\Helper;

use Psr\Http\Message\ResponseInterface;

class Helper {

    /** @var ResponseInterface $response The Psr7 Response */
    protected $response;

    /** @var stdObject $properties The data the API responsed with */
    protected $properties;

    /** @var int The index for the at function */
    private $index;

    /**
     * @param ResponseInterface The Psr7Response
     * @param array Error
     */
    public function __construct( ResponseInterface $response, $error = [] ){
        $this->properties = json_decode($response->getBody());
        if( !$this->properties ) throw new \Exception("Can't parse JSON");
        $this->response = $response;
        $this->index = null;
    }

    /**
     * Magic method access to properties
     */
    public function __get( $property ) {
        return $this->get( $property );
    }

    public function __call( $name, $arguments ) {

        if( strpos($name,'get') === 0 ) {
            $property = strtolower(substr($property,3));
            return $this->get( $property );
        } else {
            return $this->get( strtolower( $name ));
        }
    }

    /**
     * Access properties using the get method
     * @param string The property you want to get
     * @return mixed The property access or null if not available
     */
    public function get( $property ) {

        if( $this->index ) {
            if( property_exists( $this->items[$this->index], $property ) ) {
                return $this->items[$this->index]->$property;
            } else {
                return null;
            }
        } else {
            if( property_exists( $this->properties, $property) ) {
                return $this->properties->$property;
            } else {
                return null;
            }
        }
    }

    /**
     * Set which item you want to access
     * @param int The index of the item
     * @return Passmarked\Helper This instance
     */
    public function at( $index ) {
        $this->index = $index;
        return $this;
    }

    /**
     * Get the Status as reported by the API
     * @return string The status
     */
    public function getStatus() {
        if( property_exists( $this->properties, 'status') ) {
            return $this->properties->status;
        } else {
            throw new \Exception( "Response has no status" );
        }
    }

    /**
     * Get the code of the API response
     * @return string The code
     */
    public function getCode() {
        if( property_exists( $this->properties, 'code' ) ) {
            return $this->properties->code;
        } else {
            return 'OKAY';
        }
    }

    /**
     * Get the message of the API response
     * @return string The message
     */
    public function getMessage() {
        if( property_exists( $this->properties, 'message' ) ) {
            return $this->properties->message;
        } else {
            return "Okay";
        }
    }

    /**
     * Get the size of the response
     * @return int The length of the response body
     */
     public function getSize() {
         if( $this->response->hasHeader( 'content-length' ) ) {
             return (int) $this->response->getHeader( 'content-length' );
         }
     }

    /**
     * Return the GuzzleHttp\Psr7\Response
     * @return GuzzleHttp\Psr7\Response
     */
    public function getPsr7Response() {
        return $this->response;
    }
}
