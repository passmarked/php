<?php

namespace Passmarked\Exception;

class ApiErrorException extends \Exception {
    private $api_error_code;
    public function __construct($message, $api_error_code, $http_code) {
        $this->api_error_code = $api_error_code;
        parent::__construct($message, $http_code);
    }

    public function getApiErrorCode() {
        return $this->api_error_code;
    }
}