<?php
namespace Passmarked;

use GuzzleHttp\Client as GuzzleClient;
use Passmarked\ApiResponse;
use GuzzleHttp\Psr7\Request;

class Client extends GuzzleClient {

    private $api_token;

    public function __construct(array $config = []) {

        if ( !array_key_exists('API_BASE_URI', $config) || !$config['API_BASE_URI'] ) { 
            $config['API_BASE_URI'] = 'https://api.passmarked.com';
        } else { 
            $config['API_BASE_URI'] = trim($config['API_BASE_URI'],'/ ');
        }

        if ( !array_key_exists('API_VERSION', $config) || !$config['API_VERSION'] ) { 
            $config['API_VERSION'] = '2';
        }

        if ( !array_key_exists('API_TOKEN', $config) || !$config['API_TOKEN'] ) { 
            // There's no defaults here
            throw new \Exception; // TODO: Custom exception 
        } else {
            $this->api_token = $config['API_TOKEN'];
        }

        parent::__construct([
            'base_uri' => "{$config['API_BASE_URI']}/v{$config['API_VERSION']}/",
        ]);
    }

    public function __call($method, $args) {
        try{
            if(class_exists('\Passmarked\Routes\\'.ucfirst($method))) {
                $class = '\Passmarked\Routes\\'.ucfirst($method);
                $route = new $class($args);
                $result = $this->makeRequest($route);
                return $result;
            } else {
                echo "class does not exist";
            }
        } catch (\Exception $e){
            echo "CATCH";
        }
        // Try and find the method
        // call_user_func_array()
        // If not throw exception
    }

    private function makeRequest($route) {
        try{
            $path = $route->getPath();
            $path .= $route->getToken() ?  "?token={$this->api_token}" : '';
            $request = new Request($route->getHttpMethod(), $path);
            $response = new ApiResponse( $this->send($request,
                [
                    'timeout' => 10,
                ]
            ));
            // $route->__fill($response); //should fill api response
            // return new Response($this->request($type, $path));       
            return $response;    
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            echo $e->getMessage();
        }
    }
    /**
    * Get the list of websites that a token has access to
    **/
    public function getWebsites() {
        // curl https://api.passmarked.com/v2/websites?token=<token>
    }

    /**
    * Get the list of websites that a token has access to
    **/
    // public function website($id) {
    //     try{
    //         $response = new Response($this->request('GET', "website?token={$this->api_token}"));            
    //     } catch(\GuzzleHttp\Exception\ClientException $e) {
    //         //throw new \Passmarked\ClientException($e);
    //     }
    // }

    /**
    * Returns the current balance of a token
    **/
    public function balance() {

        try{
            $response = new Response($this->request('GET', "balance?token={$this->api_token}"));            
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            //throw new \Passmarked\ClientException($e);
        }
        
    }

    public function user() {
        $response = $this->request('GET', "user?token={$this->api_token}");
        var_dump($response);
    }

    public function report() {
        
    }


}