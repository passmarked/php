<?php

namespace Passmarked\Exception;

class HelperFactoryException extends \Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}