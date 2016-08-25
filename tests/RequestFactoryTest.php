<?php

namespace Passmarked\Tests;

use PHPUnit\Framework\TestCase;
use Passmarked\RequestFactory;

class RequestFactoryTest extends TestCase {

    private $config = [
        'api_url'      => 'https://api.passmarked.com',
        'api_version'   => '2',
        'api_token'     => 'a76e916065eb11e6acc397d58e05fff63551471597348214',
        'http_version'  => '1.1',
        'telemetry'     => true
    ];


    public function testCanConstruct(){
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetWebsites() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $websites_request = $request_factory->getWebsites();
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$websites_request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetWebsite() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $websites_request = $request_factory->getWebsite('1');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$websites_request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetReport() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $websites_request = $request_factory->getReport('1');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$websites_request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetBalance() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $websites_request = $request_factory->getBalance('token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$websites_request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetProfile() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $websites_request = $request_factory->getProfile();
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$websites_request);
    }

}