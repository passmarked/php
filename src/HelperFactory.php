<?php

namespace Passmarked;


use Passmarked\Helpers;

class HelperFactory {

    private $config;

    public function __construct($config){
        $this->config = $config;
    }
    public function __call($method_name, $args) {
        $namespace = '\\Passmarked\\Helper\\';
        $class_name = $namespace . ucfirst($method_name);
        if(class_exists($class_name)) {
            return new $class_name($args[0]);
        } else {
            $class_name = $class = $namespace . 'Helper';
            return new $class_name($args[0]);
        }
    }
    
}