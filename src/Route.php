<?php

namespace Passmarked;

use Passmarked\Response;

abstract class Route {

    private $http_method;
    private $http_methods_allowed = [];
    private $token = true;
    private $path;

    abstract protected function __construct($args);

    protected function setHttpMethod(string $http_method) {
        // Check if allowed
        $this->http_method = $http_method;
    }

    protected function setPath(string $path) {
        $this->path = $path;
    }

    protected function setToken(bool $token) {
        $this->token = $token;
    }

    public function getHttpMethod() : string {
        return $this->http_method;
    }

    public function getPath() : string {
        return $this->path;
    }

    public function getToken() : bool {
        return $this->token;
    }

}