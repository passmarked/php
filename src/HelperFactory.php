<?php
#@
/**
 * Passmarked\HelperFactory
 *
 * Creates Passmarked\Helper instances for each API function
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

use Passmarked\Helpers;
use Passmarked\Exception\ApiErrorException;
use Passmarked\Exception\HelperFactoryException;

class HelperFactory {

    public function __call( $method_name, $args ) {

        // Ensure the first argument is not null or false
        if( !($response = $args[0]) ) {
            throw new HelperFactoryException("Argument to {$method_name} must implement {$interface}");
        }

        // Ensure the first argument implements ResponseInterface
        $interface_name ='Psr\Http\\Message\\ResponseInterface';
        if( !in_array($interface_name, class_implements($response)) ) {
            throw new HelperFactoryException("Argument to {$method_name} must implement {$interface}");
        }

        // Check for API reported error
        $http_code = $response->getStatusCode();
        if ( $http_code >= 400 ) {
            $json_body = json_decode($response->getBody()->getContents());
            $api_code = $json_body->code;
            $api_message = $json_body->message;
            throw new ApiErrorException($api_message, $api_code, $http_code);
        }

        // Create the helper
        $namespace = '\\Passmarked\\Helper\\';
        $class_name = $namespace . ucfirst($method_name);
        if( class_exists($class_name) ) {
            return new $class_name($response);
        } else {
            $class_name = $namespace . 'Helper';
            return new $class_name($response);
        }
    }

}
