<?php

/**
 * Passmarked\Exception\ApiErrorException
 *
 * ApiErrorException is thrown when the API returns "error" in the JSON response
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
 
namespace Passmarked\Exception;

use Passmarked\Exception\PassmarkedException;

class ApiErrorException extends PassmarkedException {

    private $api_error_code;
    public function __construct( $message, $api_error_code, $http_code ) {
        $this->api_error_code = $api_error_code;
        parent::__construct($message, $http_code);
    }

    public function getApiErrorCode() {
        return $this->api_error_code;
    }
}