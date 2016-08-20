<?php

namespace Passmarked;

use GuzzleHttp\Psr7\Response;

class ApiResponse {

    private $response;
    protected $json_object;
    private $properties = [];

    public function __construct(Response $response) {

        $this->response = $response;
        $json_object = json_decode($response->getBody()->getContents());
        if(!$json_object ) throw new \Exception("Failed to decode JSON");
        if(!property_exists($json_object,'status')) throw new \Exception("Status property of JSON is missing");
        $this->status = $json_object->status;
        $this->json_object = $json_object;

        if($this->status !== "error" && $this->status !== "ok") {
            throw new \Exception("Status was neither 'ok' or 'error'");
        }
        if($this->getStatus() === "ok") {
            $item = $this->getItem();
            foreach($item as $k => $v) {                
                $this->properties[$k] = $v;
            }
        } else {
            throw new \Exception("Api Error ");
        }
    }

    public function __get(string $name) {
        return $this->properties[$name];
    }

    public function getStatus() {
        return $this->json_object->status;
    }

    public function getItem(int $id = 0) {
        if(property_exists($this->json_object,'item')) {
            return $this->json_object->item;
        } elseif(property_exists($this->json_object,'items')) {
            return $this->json_object->items[$id];
        } else {
            throw new \Exception("Unexpected response");
        }
    }
    public function toJson() {
        return json_encode($this->json_object);
    }

    private function fill() {
        if($this->response->getStatus() === "ok") {
            $item = $this->response->getItem();
            foreach($item as $k => $v) {                
                $this->properties[$k] = $v;
            }
        } else {
            throw new \Exception("Api Error ");
        }
    }
}