<?php

namespace Passmarked\Call;

abstract class Call {
    abstract protected function getBaseMethod();
    abstract protected function getBaseUri();
    abstract protected function getBaseHeaders();
    abstract protected function getBaseBody();
    abstract protected function getBaseProtocolVersion();
    abstract public function getMethod();
    abstract public function getUri();
    abstract public function getHeaders();
    abstract public function getBody();
    abstract public function getProtocolVersion();
    abstract protected function getConfig();
    abstract protected function setConfig();
    abstract public function __toRequest();
} 