<?php

namespace Passmarked\Tests;

use PHPUnit\Framework\TestCase;
use Passmarked\RequestFactory;

class RequestFactoryTest extends TestCase {

    private $config = [
        'api_url'      => 'https://api.passmarked.com',
        // 'api_url'      => 'https://api.passmarked.com',
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
        $request = $request_factory->getWebsites();
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetWebsite() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $request = $request_factory->getWebsite('1','token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetReport() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $request = $request_factory->getReport('1','token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetBalance() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $request = $request_factory->getBalance('token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetProfile() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
        $request = $request_factory->getProfile('token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreate() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);

        $request = $request_factory->create(
            'http://somesite.com', // url
            'myapitoken',          // token
            false,                 // recursive
            null,                  // limit
            false,                 // bail
            false,                 // level
            [],                    // patterns
            []                     // filters
        );

        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=http://somesite.com&token=myapitoken&limit=0',
            $request->getBody()->getContents()            
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreateDefaultToken() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);

        $request = $request_factory->create(
            'http://somesite.com' // url
        );

        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $contents = $request->getBody()->getContents();
        $this->assertEquals(
            "url=http://somesite.com&token={$this->config['api_token']}&limit=0",            
            $contents
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreateAllOptions() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);

        $request = $request_factory->create(
            'http://somesite.com',          // url
            'myapitoken',                   // token
            true,                           // recursive
            5,                              // limit
            true,                           // bail
            3,                              // level
            ['(\w+)\t','\r\n(\w+)','\w\u0020\w'],   // patterns
            ['character ','allows']                 // filters
        );
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=http://somesite.com&token=myapitoken&recursive=true&limit=5&bail=true&level=3&patterns[]=(\w+)\t&patterns[]=\r\n(\w+)&patterns[]=\w\u0020\w',
            $request->getBody()->getContents()            
        );
    }

    public function testCreateBadOptions() {
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);

        $request = $request_factory->create(
            3833.33,          // url
            333.12,           // token
            'recursion',      // recursive
            'limiting',       // limit
            234234.33,        // bail
            'levels',         // level
            [1,2,2],          // patterns
            [2,44.3]          // filters
        );
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=3833.33&token=333.12&recursive=true&limit=0',
            $request->getBody()->getContents()            
        );
    }

}