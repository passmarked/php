<?php

namespace Passmarked\Routes;

use Passmarked\Route;

class Websites extends Route {
    public function __construct($args) {
        // parent::__construct();
        $this->setHttpMethod('GET');
        $this->setPath('websites');
    }
}