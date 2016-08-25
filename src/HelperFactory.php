<?php

namespace Passmarked;


use Passmarked\Helpers;

class HelperFactory {

    public function __call($method_name, $args) {

        // First check for API reported error
        $response = $args[0];
        $code = $response->getStatusCode();
        if ($code >= 400) {
            throw new \Exception("API Response code was {$code} ");
        }
        $namespace = '\\Passmarked\\Helper\\';
        $class_name = $namespace . ucfirst($method_name);
        if(class_exists($class_name)) {
            return new $class_name($args[0]);
        } else {
            $class_name = $namespace . 'Helper';
            return new $class_name($args[0]);
        }
    }
    
}