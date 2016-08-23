<?php 
namespace Passmarked;

class Middleware {

    static function passmarked($var = '')
    {
        return function (callable $handler) use ($var) {
            return function ($request, $options) use ($handler, $var) {
                $promise = $handler($request, $options);
                return $promise->then(
                    function (\GuzzleHttp\Psr7\Response $response) use ($request) {
                 var_dump($response);exit;
                        
                        $class = $request->getResponseClass();
                                echo " \nTWO ";

                        // var_dump($class);exit;
                            if(class_exists($class)) {
                            $response = new $class($response);
                                
                                return $response;
                            } else {
                                // Exceptional!
                                echo "class does not exist".__FILE__;
                            }
                    }
                );
            };
        };
    }
}