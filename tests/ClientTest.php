<?php
namespace Passmarked\Tests;

use PHPUnit\Framework\TestCase;
use Passmarked\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class ClientTest extends TestCase {
    private $config = [
        'api_url'      => 'https://api.passmarked.com',
        'api_version'   => '2',
        'api_token'     => 'a76e916065eb11e6acc397d58e05fff63551471597348214',
        'http_version'  => '1.1',
        'telemetry'     => true
    ];

    private $test_client;

    public function __construct() {
        $responses = [
            new Response(200, ['X-Foo' => 'Bar'],'{ "status" : "ok" }'),
            new Response(202, ['Content-Length' => 0]),
        ];
        // Make a test client
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $this->config['handler'] = $handler;
        $this->test_client = new \Passmarked\Client($this->config);

    }
    public function testConstructAllConfig() {
        $client = new \Passmarked\Client($this->config);
        $this->assertInstanceof('Passmarked\\Client',$client);
    }

    public function testConstructEmptyConfig() {
        $client = new \Passmarked\Client([]);
        $this->assertInstanceof('Passmarked\\Client',$client);        
    }

    public function testMock() {
        $websites = $this->test_client->getWebsites();
        // var_dump($websites);
    }

}