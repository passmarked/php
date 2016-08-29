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
 * @link       http://packagist.org/packages/passmarked/php
 */

namespace Passmarked\Helper;

use Psr\Http\Message\ResponseInterface;

class Helper {

    /** @var ResponseInterface $response The Psr7 Response */
    protected $response;

    /** @var stdObject $properties The data the API responsed with */
    protected $properties;

    public function __construct( ResponseInterface $response, $error = [] ){
        $this->properties = json_decode($response->getBody());
        if( !$this->properties ) throw new \Exception("Can't parse JSON");
        $this->response = $response;
    }

    public function __get( $property ) {
        return $this->get($property);
    }

    public function get( $property ) {
        if( property_exists($this->properties,$property)) {
            return $this->properties->$property;
        } else {
            if ( property_exists($this->properties->item,$property) ) {
                return $this->properties->item->$property;
            } else {
                return null;
            }
        }
    }

    public function getPsr7Response(){
        return $this->response;
    }
}